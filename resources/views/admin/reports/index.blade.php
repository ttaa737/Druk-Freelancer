@extends('layouts.admin')
@section('title', 'Reports')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h4 class="fw-bold mb-1">Admin Reports</h4>
        <p class="text-muted mb-0">Operational and financial insights for {{ $reportMonthLabel }}</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">New Registrations (Month)</div>
                        <div class="fs-4 fw-bold">{{ number_format($userReports['new_registrations_month']) }}</div>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
                        <i class="fa fa-user-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Active Accounts</div>
                        <div class="fs-4 fw-bold">{{ number_format($userReports['active_accounts']) }}</div>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
                        <i class="fa fa-user-check"></i>
                    </div>
                </div>
                <div class="text-muted small mt-2">Inactive total: {{ number_format($userReports['inactive_accounts']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Freelancers</div>
                        <div class="fs-4 fw-bold">{{ number_format($userReports['freelancers']) }}</div>
                    </div>
                    <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
                        <i class="fa fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Job Posters</div>
                        <div class="fs-4 fw-bold">{{ number_format($userReports['job_posters']) }}</div>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
                        <i class="fa fa-briefcase"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-bold">Account Status Summary</div>
            <div class="card-body">
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span>Inactive</span>
                    <span class="fw-semibold">{{ number_format($userReports['status_breakdown']['inactive']) }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span>Suspended</span>
                    <span class="fw-semibold">{{ number_format($userReports['status_breakdown']['suspended']) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Banned</span>
                    <span class="fw-semibold">{{ number_format($userReports['status_breakdown']['banned']) }}</span>
                </div>
                <div class="text-muted small mt-3">
                    Last 30 days registrations: {{ number_format($userReports['new_registrations_30_days']) }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-bold">Job Monitoring Overview</div>
            <div class="card-body">
                <div class="row text-center g-2">
                    <div class="col-6">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Posted</div>
                            <div class="fs-5 fw-bold">{{ number_format($jobReports['total_posted']) }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Still Active</div>
                            <div class="fs-5 fw-bold text-success">{{ number_format($jobReports['active']) }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Completed</div>
                            <div class="fs-5 fw-bold text-primary">{{ number_format($jobReports['completed']) }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Cancelled</div>
                            <div class="fs-5 fw-bold text-danger">{{ number_format($jobReports['cancelled']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="text-muted small mt-3">
                    Includes open, in-progress, and on-hold jobs as active.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">Financial Reports</span>
        <span class="badge bg-light text-dark border">{{ $reportMonthLabel }}</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Metric</th>
                        <th class="text-end">Amount (Nu.)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Platform Revenue</td>
                        <td class="text-end fw-semibold">{{ number_format($financialReports['platform_revenue'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Commissions Earned</td>
                        <td class="text-end fw-semibold">{{ number_format($financialReports['commissions_earned'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Pending Payments</td>
                        <td class="text-end fw-semibold">{{ number_format($financialReports['pending_payments'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Freelancer Withdrawals</td>
                        <td class="text-end fw-semibold">{{ number_format($financialReports['freelancer_withdrawals'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Refunds</td>
                        <td class="text-end fw-semibold">{{ number_format($financialReports['refunds'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="alert alert-info mb-0">
                    <div class="small text-uppercase fw-semibold">Current Month Revenue</div>
                    <div class="fs-5 fw-bold">Nu. {{ number_format($financialReports['month_revenue'], 2) }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-warning mb-0">
                    <div class="small text-uppercase fw-semibold">Pending Withdrawal Requests</div>
                    <div class="fs-5 fw-bold">{{ number_format($financialReports['pending_withdrawals_count']) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
