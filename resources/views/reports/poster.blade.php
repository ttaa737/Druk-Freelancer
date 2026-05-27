@extends('layouts.app')
@section('title', 'Hiring Reports')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Job Poster Reports</h5>
        <p class="text-muted mb-0">Hiring, budget, freelancer performance, and project progress insights.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 stat-card">
            <div class="card-body">
                <div class="text-muted small">Jobs Posted</div>
                <div class="fs-4 fw-bold">{{ number_format($projectStats['jobs_posted']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100" style="border-left:4px solid #10b981;">
            <div class="card-body">
                <div class="text-muted small">Active Projects</div>
                <div class="fs-4 fw-bold">{{ number_format($projectStats['active_projects']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100" style="border-left:4px solid #3b82f6;">
            <div class="card-body">
                <div class="text-muted small">Completed Contracts</div>
                <div class="fs-4 fw-bold">{{ number_format($projectStats['completed_contracts']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100" style="border-left:4px solid #ef4444;">
            <div class="card-body">
                <div class="text-muted small">Cancelled Projects</div>
                <div class="fs-4 fw-bold">{{ number_format($projectStats['cancelled_projects']) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header fw-bold">Financial Reports</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-muted small">Total Spending</div>
                    <div class="fs-5 fw-bold">Nu. {{ number_format($financials['total_spending'], 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-muted small">Monthly Expenses</div>
                    <div class="fs-5 fw-bold">Nu. {{ number_format($financials['monthly_expenses'], 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-muted small">Pending Payments</div>
                    <div class="fs-5 fw-bold">Nu. {{ number_format($financials['pending_payments'], 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-muted small">Avg Project Cost</div>
                    <div class="fs-5 fw-bold">Nu. {{ number_format($financials['average_project_cost'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header fw-bold">Freelancer Performance Reports</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Freelancer</th>
                            <th class="text-end">Rating</th>
                            <th class="text-end">Completion Rate</th>
                            <th class="text-end">Reliability</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($freelancerPerformance as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $item['freelancer']->avatar_url }}" alt="" width="28" height="28" class="rounded-circle" style="object-fit:cover;">
                                        <span>{{ $item['freelancer']->name }}</span>
                                    </div>
                                </td>
                                <td class="text-end">{{ number_format($item['average_rating'], 1) }}</td>
                                <td class="text-end">{{ number_format($item['completion_rate'], 1) }}%</td>
                                <td class="text-end">{{ number_format($item['reliability_rate'], 1) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Not enough contract history to rank freelancers yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header fw-bold">Proposal Analytics</div>
            <div class="card-body">
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>Total Applications</span>
                    <span class="fw-semibold">{{ number_format($proposalAnalytics['total_applications']) }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>Average Bid Amount</span>
                    <span class="fw-semibold">Nu. {{ number_format($proposalAnalytics['average_bid_amount'], 2) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>Average Time to Hire</span>
                    <span class="fw-semibold">{{ number_format($proposalAnalytics['average_hire_days'], 1) }} days</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header fw-bold">Per-Job Proposal Insights</div>
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>Job</th>
                    <th>Status</th>
                    <th class="text-end">Applications</th>
                    <th class="text-end">Avg Bid (Nu.)</th>
                    <th class="text-end">Time to Hire</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proposalAnalytics['per_job'] as $job)
                    <tr>
                        <td>{{ $job['title'] }}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary">{{ ucfirst(str_replace('_', ' ', $job['status'])) }}</span></td>
                        <td class="text-end">{{ number_format($job['applications']) }}</td>
                        <td class="text-end">{{ number_format($job['avg_bid'], 2) }}</td>
                        <td class="text-end">{{ $job['hire_days'] === null ? 'Not hired yet' : number_format($job['hire_days']) . ' days' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No jobs posted yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header fw-bold">Project Progress Summary</div>
            <div class="card-body">
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>Milestones Total</span>
                    <span class="fw-semibold">{{ number_format($milestones['total']) }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>Milestones Completed</span>
                    <span class="fw-semibold">{{ number_format($milestones['completed']) }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>Milestones In Progress</span>
                    <span class="fw-semibold">{{ number_format($milestones['in_progress']) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>Milestones Disputed</span>
                    <span class="fw-semibold">{{ number_format($milestones['disputed']) }}</span>
                </div>

                <hr>

                <div class="small text-muted mb-2">Deadline Monitoring</div>
                <div class="d-flex justify-content-between border-bottom py-1">
                    <span>Overdue Projects</span>
                    <span class="fw-semibold text-danger">{{ number_format($deadlineMonitoring['overdue']) }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom py-1">
                    <span>Due in 7 Days</span>
                    <span class="fw-semibold text-warning">{{ number_format($deadlineMonitoring['due_soon']) }}</span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>On Track</span>
                    <span class="fw-semibold text-success">{{ number_format($deadlineMonitoring['on_track']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header fw-bold">Active Project Progress Tracking</div>
            <div class="card-body p-0">
                @forelse($projectProgress as $row)
                    <div class="px-3 py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                            <div>
                                <div class="fw-semibold">{{ $row['contract']->job?->title ?? 'Project' }}</div>
                                <div class="text-muted small">Freelancer: {{ $row['contract']->freelancer?->name ?? 'N/A' }}</div>
                            </div>
                            <div class="text-end">
                                <div class="small fw-semibold">{{ $row['progress'] }}%</div>
                                <div class="small text-muted">{{ $row['deadline_state'] }}</div>
                            </div>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar" style="width: {{ $row['progress'] }}%;"></div>
                        </div>
                        <div class="small text-muted mt-1">
                            {{ $row['completed_milestones'] }} / {{ $row['total_milestones'] }} milestones completed
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">No active projects to track right now.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
