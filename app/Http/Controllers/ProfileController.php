<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Profile;
use App\Models\Review;
use App\Models\Skill;
use App\Models\User;
use App\Models\VerificationDocument;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class ProfileController extends Controller
{
    public function __construct(private OTPService $otp)
    {
        $this->middleware('auth');
    }

    /**
     * View a user's public profile.
     */
    public function show(User $user)
    {
        $user->load(['profile', 'skills', 'portfolioItems', 'certifications', 'contractsAsFreelancer', 'contractsAsPoster']);
        if ($user->profile) {
            try {
                if (Schema::hasColumn('profiles', 'profile_views')) {
                    $user->profile->increment('profile_views');
                }
            } catch (\Exception $e) {
                // If the DB schema cannot be read for any reason, skip increment to avoid errors in environments
            }
        }

        $portfolioItems  = $user->portfolioItems;
        $certifications  = $user->certifications;
        $reviews = Review::where('reviewee_id', $user->id)
            ->with('reviewer.profile')
            ->latest()
            ->paginate(5);

        return view('profile.show', compact('user', 'portfolioItems', 'certifications', 'reviews'));
    }

    /**
     * Edit own profile.
     */
    public function edit()
    {
        $user       = Auth::user()->load('profile', 'skills', 'portfolioItems', 'certifications', 'verificationDocuments', 'paymentMethods');
        $categories = Category::with(['skills' => fn($q) => $q->where('is_active', true)->orderBy('name')])
                        ->whereHas('skills', fn($q) => $q->where('is_active', true))
                        ->orderBy('name')
                        ->get();
        $dzongkhags = Profile::DZONGKHAGS;

        return view('profile.edit', compact('user', 'categories', 'dzongkhags'));
    }

    /**
     * Update profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'phone'             => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'preferred_language' => 'nullable|in:en,dz',
            'bio'               => 'nullable|string|max:1000',
            'dzongkhag'         => 'nullable|string',
            'gewog'             => 'nullable|string|max:100',
            'address'           => 'nullable|string|max:500',
            'website'           => 'nullable|url|max:255',
            'headline'          => 'nullable|string|max:200',
            'hourly_rate'       => 'nullable|numeric|min:0',
            'availability'      => 'nullable|in:available,busy,not_available',
            'experience_years'  => 'nullable|integer|min:0|max:60',
            'company_name'      => 'nullable|string|max:255',
            'industry'          => 'nullable|string|max:100',
            'skills'            => 'nullable|array',
            'skills.*'          => 'exists:skills,id',
            'avatar'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update([
            'name'               => $validated['name'],
            'phone'              => $validated['phone'] ?? $user->phone,
            'preferred_language' => $validated['preferred_language'] ?? $user->preferred_language,
            'avatar'             => $validated['avatar'] ?? $user->avatar,
        ]);

        $profileData = array_filter([
            'bio'              => $validated['bio'] ?? null,
            'dzongkhag'        => $validated['dzongkhag'] ?? null,
            'gewog'            => $validated['gewog'] ?? null,
            'address'          => $validated['address'] ?? null,
            'website'          => $validated['website'] ?? null,
            'headline'         => $validated['headline'] ?? null,
            'hourly_rate'      => $validated['hourly_rate'] ?? null,
            'availability'     => $validated['availability'] ?? null,
            'experience_years' => $validated['experience_years'] ?? null,
            'company_name'     => $validated['company_name'] ?? null,
            'industry'         => $validated['industry'] ?? null,
        ], fn($v) => !is_null($v));

        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

        if (!empty($validated['skills'])) {
            $skillsWithLevel = [];
            foreach ($validated['skills'] as $skillId) {
                $skillsWithLevel[$skillId] = ['level' => $request->input("skill_level_{$skillId}", 'intermediate')];
            }
            $user->skills()->sync($skillsWithLevel);
        }

        AuditLogService::log('profile.updated', $user);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Upload verification document (CID/BRN).
     */
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type'   => 'required|in:cid,brn,tax_certificate,license,other',
            'document_number' => 'nullable|string|max:50',
            'document_file'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('document_file')->store('verification-docs', 'public');

        $doc = VerificationDocument::create([
            'user_id'         => Auth::id(),
            'document_type'   => $request->document_type,
            'document_number' => $request->document_number,
            'file_path'       => $path,
            'original_name'   => $request->file('document_file')->getClientOriginalName(),
            'status'          => 'pending',
        ]);

        NotificationService::adminNewVerificationRequest(Auth::user());
        AuditLogService::log('document.uploaded', $doc, notes: $request->document_type);

        return back()->with('success', 'Document uploaded. Our team will verify it within 1-2 business days.');
    }

    /**
     * Send phone OTP for verification.
     */
    public function sendPhoneOTP()
    {
        $user = Auth::user();
        if (!$user->phone) {
            return back()->with('error', 'Please add a phone number to your profile first.');
        }

        $this->otp->sendSmsOTP($user, 'phone_verify');

        return back()->with('success', 'OTP sent to your phone. Valid for 10 minutes.');
    }

    /**
     * Verify phone with OTP.
     */
    public function verifyPhone(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $user = Auth::user();
        if ($this->otp->verify($user->phone, 'phone_verify', $request->otp)) {
            $user->update(['phone_verified_at' => now()]);
            AuditLogService::log('phone.verified', $user);

            return back()->with('success', 'Phone number verified successfully!');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }
}

