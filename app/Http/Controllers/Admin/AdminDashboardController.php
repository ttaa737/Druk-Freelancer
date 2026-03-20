<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\DisputeCase;
use App\Models\Job;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VerificationDocument;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        // User stats
        $totalUsers      = User::count();
        $freelancers     = User::role('freelancer')->count();
        $jobPosters      = User::role('job_poster')->count();
        $pendingVerify   = VerificationDocument::where('status', 'pending')->count();

        // Job stats
        $totalJobs       = Job::count();
        $openJobs        = Job::where('status', 'open')->count();

        // Contract stats
        $activeContracts    = Contract::where('status', 'active')->count();
        $completedContracts = Contract::where('status', 'completed')->count();

        // Financial stats
        $totalPlatformFees = Transaction::where('type', 'platform_fee')
            ->where('status', 'completed')
            ->sum('amount');

        $totalEscrowHeld = Transaction::where('type', 'escrow_hold')
            ->where('status', 'completed')
            ->sum('amount');

        $totalWithdrawals = Transaction::where('type', 'withdrawal')
            ->where('status', 'completed')
            ->sum('amount');

        // Dispute stats
        $openDisputes   = DisputeCase::where('status', 'open')->count();
        $resolvedDisputes = DisputeCase::where('status', 'resolved')->count();

        // Monthly revenue for current month
        $monthlyRevenue = Transaction::where('type', 'platform_fee')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Revenue over last 12 months for chart
        $revenueData = Transaction::where('type', 'platform_fee')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Recent registrations
        $recentUsers = User::with('profile')
            ->latest()
            ->take(10)
            ->get();

        // Build stats array
        $stats = [
            'total_users'           => $totalUsers,
            'freelancers'           => $freelancers,
            'job_posters'           => $jobPosters,
            'pending_verifications' => $pendingVerify,
            'total_jobs'            => $totalJobs,
            'active_jobs'           => $openJobs,
            'active_contracts'      => $activeContracts,
            'completed_contracts'   => $completedContracts,
            'total_platform_fees'   => $totalPlatformFees,
            'total_escrow_held'     => $totalEscrowHeld,
            'total_withdrawals'     => $totalWithdrawals,
            'open_disputes'         => $openDisputes,
            'resolved_disputes'     => $resolvedDisputes,
            'monthly_revenue'       => $monthlyRevenue,
        ];

        return view('admin.dashboard', compact('stats', 'recentUsers', 'revenueData'));
    }
}

