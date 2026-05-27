<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Job;
use App\Models\Milestone;
use App\Models\Proposal;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        return $this->reportForRole('overview');
    }

    public function freelancer()
    {
        return $this->renderFreelancerReport('overview');
    }

    public function poster()
    {
        return $this->renderPosterReport('overview');
    }

    public function jobs()
    {
        return $this->reportForRole('jobs');
    }

    public function applications()
    {
        return $this->reportForRole('applications');
    }

    public function earnings()
    {
        return $this->reportForRole('earnings');
    }

    public function contracts()
    {
        return $this->reportForRole('contracts');
    }

    private function reportForRole(string $activeTab)
    {
        $user = auth()->user();

        if ($user && $user->isFreelancer()) {
            return $this->renderFreelancerReport($activeTab);
        }

        if ($user && $user->isJobPoster()) {
            return $this->renderPosterReport($activeTab);
        }

        abort(403, 'This report section is available to freelancers and job posters only.');
    }

    private function renderFreelancerReport(string $activeTab)
    {
        $user = auth()->user();

        abort_unless($user && $user->isFreelancer(), 403, 'This report section is available to freelancers only.');

        $incomingTypes = ['escrow_release', 'completion_payment'];

        $totalEarnings = (float) ($user->wallet?->total_earned ?? 0);
        if ($totalEarnings <= 0) {
            $totalEarnings = (float) Transaction::where('user_id', $user->id)
                ->whereIn('type', $incomingTypes)
                ->where('status', 'completed')
                ->sum('amount');
        }

        $monthlyIncome = (float) Transaction::where('user_id', $user->id)
            ->whereIn('type', $incomingTypes)
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $pendingPayments = (float) Transaction::where('user_id', $user->id)
            ->whereIn('type', $incomingTypes)
            ->whereIn('status', ['pending', 'processing'])
            ->sum('amount');

        $withdrawnAmount = (float) Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->where('status', 'completed')
            ->sum('amount');

        $jobsApplied = Proposal::where('freelancer_id', $user->id)->count();
        $acceptedProposals = Proposal::where('freelancer_id', $user->id)
            ->whereIn('status', ['accepted', 'shortlisted'])
            ->count();

        $completedProjects = Contract::where('freelancer_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $activeContracts = Contract::where('freelancer_id', $user->id)
            ->where('status', 'active')
            ->count();

        $feedback = [
            'average_rating' => (float) Review::where('reviewee_id', $user->id)->avg('rating_overall'),
            'total_reviews' => Review::where('reviewee_id', $user->id)->count(),
            'rating_breakdown' => [
                'communication' => (float) Review::where('reviewee_id', $user->id)->avg('rating_communication'),
                'quality' => (float) Review::where('reviewee_id', $user->id)->avg('rating_quality'),
                'timeliness' => (float) Review::where('reviewee_id', $user->id)->avg('rating_timeliness'),
                'professionalism' => (float) Review::where('reviewee_id', $user->id)->avg('rating_professionalism'),
            ],
            'recent' => Review::with('reviewer')
                ->where('reviewee_id', $user->id)
                ->latest()
                ->take(5)
                ->get(),
        ];

        $hourlySummary = DB::table('contracts')
            ->join('jobs', 'jobs.id', '=', 'contracts.job_id')
            ->where('contracts.freelancer_id', $user->id)
            ->where('jobs.type', 'hourly')
            ->selectRaw('COUNT(*) as hourly_projects, COALESCE(SUM(COALESCE(jobs.duration_days,0) * 8), 0) as estimated_billable_hours')
            ->first();

        $performanceSkills = DB::table('contracts')
            ->join('jobs', 'jobs.id', '=', 'contracts.job_id')
            ->join('job_skills', 'job_skills.job_id', '=', 'jobs.id')
            ->join('skills', 'skills.id', '=', 'job_skills.skill_id')
            ->where('contracts.freelancer_id', $user->id)
            ->where('contracts.status', 'completed')
            ->selectRaw('skills.name as skill_name, COUNT(DISTINCT contracts.id) as completed_projects, COALESCE(SUM(contracts.freelancer_amount), 0) as total_earnings')
            ->groupBy('skills.id', 'skills.name')
            ->orderByDesc('completed_projects')
            ->orderByDesc('total_earnings')
            ->limit(5)
            ->get();

        $performanceCategories = DB::table('contracts')
            ->join('jobs', 'jobs.id', '=', 'contracts.job_id')
            ->leftJoin('categories', 'categories.id', '=', 'jobs.category_id')
            ->where('contracts.freelancer_id', $user->id)
            ->where('contracts.status', 'completed')
            ->selectRaw('COALESCE(categories.name, "Uncategorized") as category_name, COUNT(contracts.id) as completed_projects, COALESCE(AVG(contracts.freelancer_amount), 0) as average_project_value, COALESCE(SUM(contracts.freelancer_amount), 0) as total_earned')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('average_project_value')
            ->limit(5)
            ->get();

        return view('reports.freelancer', [
            'activeTab' => $activeTab,
            'financials' => [
                'total_earnings' => $totalEarnings,
                'monthly_income' => $monthlyIncome,
                'pending_payments' => $pendingPayments,
                'withdrawn_amount' => $withdrawnAmount,
            ],
            'activity' => [
                'jobs_applied' => $jobsApplied,
                'accepted_proposals' => $acceptedProposals,
                'completed_projects' => $completedProjects,
                'active_contracts' => $activeContracts,
            ],
            'feedback' => $feedback,
            'hourly' => [
                'hourly_projects' => (int) ($hourlySummary->hourly_projects ?? 0),
                'estimated_billable_hours' => (float) ($hourlySummary->estimated_billable_hours ?? 0),
            ],
            'performanceSkills' => $performanceSkills,
            'performanceCategories' => $performanceCategories,
        ]);
    }

    private function renderPosterReport(string $activeTab)
    {
        $user = auth()->user();

        abort_unless($user && $user->isJobPoster(), 403, 'This report section is available to job posters only.');

        $contractsBase = Contract::where('poster_id', $user->id);

        $projectStats = [
            'jobs_posted' => Job::where('poster_id', $user->id)->count(),
            'active_projects' => (clone $contractsBase)->where('status', 'active')->count(),
            'completed_contracts' => (clone $contractsBase)->where('status', 'completed')->count(),
            'cancelled_projects' => (clone $contractsBase)->where('status', 'cancelled')->count(),
        ];

        $outgoingTypes = ['escrow_hold', 'platform_fee', 'completion_settlement'];
        $totalOutgoing = (float) Transaction::where('user_id', $user->id)
            ->whereIn('type', $outgoingTypes)
            ->where('status', 'completed')
            ->sum('amount');

        $refunds = (float) Transaction::where('user_id', $user->id)
            ->where('type', 'refund')
            ->where('status', 'completed')
            ->sum('amount');

        $totalSpending = (float) ($user->wallet?->total_spent ?? 0);
        if ($totalSpending <= 0) {
            $totalSpending = max($totalOutgoing - $refunds, 0);
        }

        $monthlyExpenses = (float) Transaction::where('user_id', $user->id)
            ->whereIn('type', $outgoingTypes)
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $pendingPayments = (float) Transaction::whereIn('status', ['pending', 'processing'])
            ->whereIn('type', ['escrow_hold', 'platform_fee', 'escrow_release', 'completion_settlement'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('contract', fn($cq) => $cq->where('poster_id', $user->id));
            })
            ->sum('amount');

        $averageProjectCost = (float) Contract::where('poster_id', $user->id)->avg('total_amount');

        $financials = [
            'total_spending' => $totalSpending,
            'monthly_expenses' => $monthlyExpenses,
            'pending_payments' => $pendingPayments,
            'average_project_cost' => $averageProjectCost,
        ];

        $proposalQuery = Proposal::whereHas('job', fn($q) => $q->where('poster_id', $user->id));

        $proposalAnalytics = [
            'total_applications' => (clone $proposalQuery)->count(),
            'average_bid_amount' => (float) (clone $proposalQuery)->avg('bid_amount'),
            'average_hire_days' => (float) DB::table('contracts')
                ->join('jobs', 'jobs.id', '=', 'contracts.job_id')
                ->where('jobs.poster_id', $user->id)
                ->selectRaw('AVG(TIMESTAMPDIFF(DAY, jobs.created_at, contracts.created_at)) as avg_days')
                ->value('avg_days'),
            'per_job' => Job::where('poster_id', $user->id)
                ->withCount('proposals')
                ->withAvg('proposals', 'bid_amount')
                ->with(['contracts' => fn($q) => $q->select('id', 'job_id', 'created_at')])
                ->latest()
                ->take(8)
                ->get()
                ->map(function ($job) {
                    $hireContract = $job->contracts->sortBy('created_at')->first();
                    $hireDays = $hireContract ? $job->created_at->diffInDays($hireContract->created_at) : null;

                    return [
                        'title' => $job->title,
                        'status' => $job->status,
                        'applications' => (int) $job->proposals_count,
                        'avg_bid' => (float) ($job->proposals_avg_bid_amount ?? 0),
                        'hire_days' => $hireDays,
                    ];
                }),
        ];

        $allPosterContracts = Contract::with('freelancer:id,name,avatar')
            ->where('poster_id', $user->id)
            ->whereNotNull('freelancer_id')
            ->get()
            ->groupBy('freelancer_id');

        $freelancerPerformance = $allPosterContracts->map(function ($contracts, $freelancerId) {
            $freelancer = $contracts->first()->freelancer;
            if (!$freelancer) {
                return null;
            }

            $totalContracts = $contracts->count();
            $completedContracts = $contracts->where('status', 'completed')->count();
            $completionRate = $totalContracts > 0 ? ($completedContracts / $totalContracts) * 100 : 0;

            $onTimeCompleted = $contracts->filter(function ($contract) {
                return $contract->status === 'completed'
                    && $contract->deadline
                    && $contract->completed_at
                    && $contract->completed_at->lte($contract->deadline);
            })->count();

            $reliabilityRate = $completedContracts > 0 ? ($onTimeCompleted / $completedContracts) * 100 : 0;

            $avgRating = (float) Review::whereIn('contract_id', $contracts->pluck('id'))
                ->where('reviewee_id', $freelancerId)
                ->avg('rating_overall');

            $score = ($completionRate * 0.45) + ($reliabilityRate * 0.35) + (($avgRating * 20) * 0.20);

            return [
                'freelancer' => $freelancer,
                'total_contracts' => $totalContracts,
                'completed_contracts' => $completedContracts,
                'completion_rate' => $completionRate,
                'average_rating' => $avgRating,
                'reliability_rate' => $reliabilityRate,
                'score' => $score,
            ];
        })->filter()->sortByDesc('score')->take(8)->values();

        $activeContracts = Contract::with(['job:id,title', 'freelancer:id,name,avatar', 'milestones'])
            ->where('poster_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();

        $activeContractIds = $activeContracts->pluck('id');
        $milestoneBase = Milestone::whereIn('contract_id', $activeContractIds);

        $milestones = [
            'total' => (clone $milestoneBase)->count(),
            'completed' => (clone $milestoneBase)->whereIn('status', ['approved', 'paid'])->count(),
            'in_progress' => (clone $milestoneBase)->whereIn('status', ['pending', 'in_progress', 'submitted', 'revision'])->count(),
            'disputed' => (clone $milestoneBase)->where('status', 'disputed')->count(),
        ];

        $now = now();
        $deadlineMonitoring = [
            'overdue' => Contract::where('poster_id', $user->id)
                ->where('status', 'active')
                ->whereNotNull('deadline')
                ->where('deadline', '<', $now)
                ->count(),
            'due_soon' => Contract::where('poster_id', $user->id)
                ->where('status', 'active')
                ->whereNotNull('deadline')
                ->whereBetween('deadline', [$now, $now->copy()->addDays(7)])
                ->count(),
            'on_track' => Contract::where('poster_id', $user->id)
                ->where('status', 'active')
                ->where(function ($query) use ($now) {
                    $query->whereNull('deadline')
                        ->orWhere('deadline', '>', $now->copy()->addDays(7));
                })
                ->count(),
        ];

        $projectProgress = $activeContracts->map(function ($contract) {
            $totalMilestones = $contract->milestones->count();
            $completedMilestones = $contract->milestones
                ->whereIn('status', ['approved', 'paid'])
                ->count();

            $progress = $totalMilestones > 0
                ? round(($completedMilestones / $totalMilestones) * 100)
                : 0;

            $deadlineState = 'No deadline';
            if ($contract->deadline) {
                if ($contract->deadline->isPast()) {
                    $deadlineState = 'Overdue';
                } elseif ($contract->deadline->diffInDays(now()) <= 7) {
                    $deadlineState = 'Due soon';
                } else {
                    $deadlineState = 'On track';
                }
            }

            return [
                'contract' => $contract,
                'progress' => $progress,
                'total_milestones' => $totalMilestones,
                'completed_milestones' => $completedMilestones,
                'deadline_state' => $deadlineState,
            ];
        });

        return view('reports.poster', [
            'activeTab' => $activeTab,
            'projectStats' => $projectStats,
            'financials' => $financials,
            'freelancerPerformance' => $freelancerPerformance,
            'proposalAnalytics' => $proposalAnalytics,
            'milestones' => $milestones,
            'deadlineMonitoring' => $deadlineMonitoring,
            'projectProgress' => $projectProgress,
        ]);
    }
}
