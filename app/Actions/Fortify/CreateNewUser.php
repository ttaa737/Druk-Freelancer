<?php

namespace App\Actions\Fortify;

use App\Models\Profile;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'phone'    => ['nullable', 'string', 'max:20', Rule::unique(User::class)],
            'role'     => ['required', Rule::in(['freelancer', 'job_poster'])],
            'password' => $this->passwordRules(),
            'terms'    => ['accepted'],
        ], [
            'role.in'       => 'Please select a valid account type.',
            'terms.accepted' => 'You must accept the Terms of Service and Privacy Policy.',
        ])->validate();

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

