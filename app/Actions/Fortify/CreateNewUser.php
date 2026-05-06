<?php

namespace App\Actions\Fortify;

use App\Models\Profile;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name'     => ['required', 'string', 'max:255'],
            // Allow same email across different roles by scoping uniqueness to the selected role
            'email'    => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->where(function ($query) use ($input) {
                    return $query->where('role', $input['role'] ?? null);
                }),
            ],
            // Bhutan phone validation: must be 8 digits and start with 16, 17 or 77
            'phone'    => [
                'nullable', 'string', 'regex:/^(16|17|77)\\d{6}$/', Rule::unique(User::class),
            ],
            'role'     => ['required', Rule::in(['freelancer', 'job_poster'])],
            'password' => $this->passwordRules(),
            'terms'    => ['accepted'],
        ], [
            'role.in'          => 'Please select a valid account type.',
            'terms.accepted'   => 'You must accept the Terms of Service and Privacy Policy.',
            'phone.regex'      => 'Phone number must be 8 digits and start with 16, 17, or 77.',
            'email.unique'     => 'An account with this email and role already exists.',
        ])->validate();

        // If the same email exists for a different role, ensure the password differs
        $existingDifferentRole = User::where('email', $input['email'])
            ->where('role', '!=', $input['role'])
            ->first();

        if ($existingDifferentRole && isset($input['password'])) {
            if (Hash::check($input['password'], $existingDifferentRole->password)) {
                throw ValidationException::withMessages([
                    'password' => ['Password must be different from the existing account using this email.'],
                ]);
            }
        }

        return DB::transaction(function () use ($input) {
            $user = User::create([
                'name'     => $input['name'],
                'email'    => $input['email'],
                'phone'    => $input['phone'] ?? null,
                'password' => Hash::make($input['password']),
                'role'     => $input['role'],
            ]);

            // Assign Spatie permission role
            $user->assignRole($input['role']);

            // Create blank profile
            Profile::create(['user_id' => $user->id]);

            // Create platform wallet
            Wallet::create(['user_id' => $user->id]);

            return $user;
        });
    }
}

