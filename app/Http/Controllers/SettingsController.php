<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show settings page.
     */
    public function index()
    {
        $user = Auth::user()->load('paymentMethods');
        return view('settings.index', compact('user'));
    }

    /**
     * Update account settings (email, password, language).
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'preferred_language' => 'nullable|in:en,dz',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Update basic account info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->preferred_language = $validated['preferred_language'] ?? 'en';

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        AuditLogService::log('account.settings.updated', $user);

        return back()->with('success', 'Account settings updated successfully!');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'notify_new_messages' => 'boolean',
            'notify_proposals' => 'boolean',
            'notify_milestones' => 'boolean',
            'notify_payments' => 'boolean',
            'notify_reviews' => 'boolean',
            'email_notifications' => 'boolean',
        ]);

        $preferences = [
            'new_messages' => $request->boolean('notify_new_messages'),
            'proposals' => $request->boolean('notify_proposals'),
            'milestones' => $request->boolean('notify_milestones'),
            'payments' => $request->boolean('notify_payments'),
            'reviews' => $request->boolean('notify_reviews'),
            'email_notifications' => $request->boolean('email_notifications'),
        ];

        $user->notification_preferences = $preferences;
        $user->save();

        AuditLogService::log('notification.settings.updated', $user);

        return back()->with('success', 'Notification preferences updated successfully!');
    }

    /**
     * Update privacy settings.
     */
    public function updatePrivacy(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'profile_visibility' => 'required|in:public,private,freelancers_only',
            'show_email' => 'boolean',
            'show_phone' => 'boolean',
            'allow_messages' => 'boolean',
        ]);

        $privacy = [
            'profile_visibility' => $validated['profile_visibility'],
            'show_email' => $request->boolean('show_email'),
            'show_phone' => $request->boolean('show_phone'),
            'allow_messages' => $request->boolean('allow_messages'),
        ];

        $user->privacy_settings = $privacy;
        $user->save();

        AuditLogService::log('privacy.settings.updated', $user);

        return back()->with('success', 'Privacy settings updated successfully!');
    }

    /**
     * Delete payment method.
     */
    public function deletePaymentMethod(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403);
        }

        $paymentMethod->delete();
        AuditLogService::log('payment.method.deleted', $paymentMethod);

        return back()->with('success', 'Payment method removed successfully!');
    }

    /**
     * Request account deletion.
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'delete_confirmation' => 'required|in:DELETE',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect.']);
        }

        // Check for active contracts
        $activeContracts = $user->contractsAsFreelancer()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        $activePosterContracts = $user->contractsAsPoster()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        if ($activeContracts > 0 || $activePosterContracts > 0) {
            return back()->withErrors(['delete_confirmation' => 'You cannot delete your account while you have active contracts.']);
        }

        AuditLogService::log('account.deleted', $user);
        
        // Soft delete user
        $user->delete();
        Auth::logout();

        return redirect()->route('jobs.index')->with('success', 'Your account has been deleted successfully.');
    }
}
