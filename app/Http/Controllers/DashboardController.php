<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\DisputeCase;
use App\Models\Job;
use App\Models\Proposal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function index()
    {
        $user = Auth::user()->load('profile', 'wallet');

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isFreelancer()) {
            return $this->freelancerDashboard($user);
        }

        return $this->posterDashboard($user);
    }

    private function freelancerDashboard($user)
    {
        $stats = [
            'active_contracts'    => Contract::where('freelancer_id', $user->id)->where('status', 'active')->count(),
            'pending_proposals'   => Proposal::where('freelancer_id', $user->id)->where('status', 'pending')->count(),
            'completed_contracts' => Contract::where('freelancer_id', $user->id)->where('status', 'completed')->count(),
            'total_earned'        => $user->wallet?->total_earned ?? 0,
            'available_balance'   => $user->wallet?->available_balance ?? 0,
            'escrow_balance'      => $user->wallet?->escrow_balance ?? 0,
            'average_rating'      => $user->profile?->average_rating ?? 0,
            'total_reviews'       => $user->profile?->total_reviews ?? 0,
        ];

        $recentJobs = Job::with(['category', 'poster.profile'])
                         ->open()
                         ->latest()
                         ->limit(8)
                         ->get();

        $activeContracts = Contract::with(['job', 'poster.profile', 'milestones'])
                                   ->where('freelancer_id', $user->id)
                                   ->where('status', 'active')
                                   ->latest()
                                   ->limit(5)
                                   ->get();

        $recentTransactions = Transaction::where('user_id', $user->id)
                                         ->latest()
                                         ->limit(5)
                                         ->get();

        return view('dashboard.freelancer', compact('user', 'stats', 'recentJobs', 'activeContracts', 'recentTransactions'));
    }

    private function posterDashboard($user)
    {
        $stats = [
            'active_jobs'         => Job::where('poster_id', $user->id)->where('status', 'open')->count(),
            'active_contracts'    => Contract::where('poster_id', $user->id)->where('status', 'active')->count(),
            'completed_contracts' => Contract::where('poster_id', $user->id)->where('status', 'completed')->count(),
            'total_spent'         => $user->wallet?->total_spent ?? 0,
            'available_balance'   => $user->wallet?->available_balance ?? 0,
            'escrow_balance'      => $user->wallet?->escrow_balance ?? 0,
            'pending_proposals'   => Proposal::whereHas('job', fn($q) => $q->where('poster_id', $user->id))
                                             ->where('status', 'pending')
                                             ->count(),
        ];

        $recentJobs = Job::with(['proposals', 'category'])
                         ->where('poster_id', $user->id)
                         ->latest()
                         ->limit(5)
                         ->get();

        $activeContracts = Contract::with(['job', 'freelancer.profile', 'milestones'])
                                   ->where('poster_id', $user->id)
                                   ->where('status', 'active')
                                   ->latest()
                                   ->limit(5)
                                   ->get();

        $recentTransactions = Transaction::where('user_id', $user->id)
                                         ->latest()
                                         ->limit(5)
                                         ->get();

        return view('dashboard.poster', compact('user', 'stats', 'recentJobs', 'activeContracts', 'recentTransactions'));
    }
}

