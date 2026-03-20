<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = User::with('profile')
            ->withTrashed();

        if ($request->filled('role')) {
            $query->role($request->role);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%")
                   ->orWhere('phone', 'like', "%{$q}%")
                   ->orWhere('cid_number', 'like', "%{$q}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'profile', 'skills', 'wallet.transactions',
            'verificationDocuments', 'reviewsReceived',
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function suspend(Request $request, User $user)
    {
        abort_if($user->isAdmin(), 403, 'Cannot suspend an admin account.');
        $request->validate(['reason' => 'required|string|max:500']);

        $user->update(['status' => 'suspended']);
        // Freeze wallet and store reason (guard if DB column missing)
        try {
            if ($user->wallet && Schema::hasColumn('wallets', 'is_frozen')) {
                $user->wallet->update(['is_frozen' => true, 'freeze_reason' => $request->reason]);
            }
        } catch (QueryException $e) {
            // log and continue - don't block admin action if migration not applied
            report($e);
        }
        AuditLogService::log('user.suspended', $user, notes: $request->reason);

        return back()->with('success', "User {$user->name} has been suspended.");
    }

    public function ban(Request $request, User $user)
    {
        abort_if($user->isAdmin(), 403);
        $request->validate(['reason' => 'required|string|max:500']);

        $user->update(['status' => 'banned']);
        // Freeze wallet and save reason (guard if DB column missing)
        try {
            if ($user->wallet && Schema::hasColumn('wallets', 'is_frozen')) {
                $user->wallet->update(['is_frozen' => true, 'freeze_reason' => $request->reason]);
            }
        } catch (QueryException $e) {
            report($e);
        }
        AuditLogService::log('user.banned', $user, notes: $request->reason);

        return back()->with('success', "User {$user->name} has been banned and wallet frozen.");
    }

    public function activate($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        if ($user->trashed()) {
            $user->restore();
        }
        $user->update(['status' => 'active']);
        // Unfreeze wallet and clear reason (guard if DB column missing)
        try {
            if ($user->wallet && Schema::hasColumn('wallets', 'is_frozen')) {
                $user->wallet->update(['is_frozen' => false, 'freeze_reason' => null]);
            }
        } catch (QueryException $e) {
            report($e);
        }
        AuditLogService::log('user.activated', $user);

        return back()->with('success', "User {$user->name} has been reactivated.");
    }

    public function verify(User $user)
    {
        $user->update(['verification_status' => 'verified']);
        AuditLogService::log('user.verified', $user);

        return back()->with('success', "User {$user->name} marked as verified.");
    }

    public function destroy(User $user)
    {
        abort_if($user->isAdmin(), 403);
        $user->delete(); // soft delete
        AuditLogService::log('user.deleted', $user);

        return redirect()->route('admin.users.index')->with('success', 'User account deleted.');
    }
}

