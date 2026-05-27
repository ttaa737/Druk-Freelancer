<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Carbon;

class AdminReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $userReports = [
            'new_registrations_month' => User::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
            'new_registrations_30_days' => User::where('created_at', '>=', $now->copy()->subDays(30))->count(),
            'active_accounts' => User::where('status', 'active')->count(),
            'inactive_accounts' => User::where('status', '!=', 'active')->count(),
            'freelancers' => User::role('freelancer')->count(),
            'job_posters' => User::role('job_poster')->count(),
            'status_breakdown' => [
                'inactive' => User::where('status', 'inactive')->count(),
                'suspended' => User::where('status', 'suspended')->count(),
                'banned' => User::where('status', 'banned')->count(),
            ],
        ];

        $jobReports = [
            'total_posted' => Job::count(),
            'completed' => Job::where('status', 'completed')->count(),
            'cancelled' => Job::where('status', 'cancelled')->count(),
            'active' => Job::whereIn('status', ['open', 'in_progress', 'on_hold'])->count(),
            'status_breakdown' => Job::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
        ];

        $platformRevenue = Transaction::whereIn('type', ['platform_fee', 'platform_fee_earned', 'penalty'])
            ->where('status', 'completed')
            ->sum('amount');

        $commissionsEarned = Transaction::whereIn('type', ['platform_fee', 'platform_fee_earned'])
            ->where('status', 'completed')
            ->sum('amount');

        $pendingPayments = Transaction::whereIn('status', ['pending', 'processing'])
            ->whereIn('type', ['escrow_release', 'withdrawal', 'refund', 'completion_payment'])
            ->sum('amount');

        $withdrawals = Transaction::where('type', 'withdrawal')
            ->where('status', 'completed')
            ->sum('amount');

        $refunds = Transaction::where('type', 'refund')
            ->where('status', 'completed')
            ->sum('amount');

        $financialReports = [
            'platform_revenue' => $platformRevenue,
            'commissions_earned' => $commissionsEarned,
            'pending_payments' => $pendingPayments,
            'freelancer_withdrawals' => $withdrawals,
            'refunds' => $refunds,
            'pending_withdrawals_count' => Transaction::where('type', 'withdrawal')
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'month_revenue' => Transaction::whereIn('type', ['platform_fee', 'platform_fee_earned', 'penalty'])
                ->where('status', 'completed')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('amount'),
        ];

        return view('admin.reports.index', [
            'userReports' => $userReports,
            'jobReports' => $jobReports,
            'financialReports' => $financialReports,
            'reportMonthLabel' => $monthStart->format('F Y'),
        ]);
    }
}
