@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<h4 class="fw-bold mb-4">Platform Overview</h4>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total Users','value'=>$stats['total_users'],'icon'=>'users','color'=>'primary'],
        ['label'=>'Active Jobs','value'=>$stats['active_jobs'],'icon'=>'briefcase','color'=>'success'],
        ['label'=>'Active Contracts','value'=>$stats['active_contracts'],'icon'=>'file-contract','color'=>'info'],
        ['label'=>'Open Disputes','value'=>$stats['open_disputes'],'icon'=>'gavel','color'=>'danger'],
        ['label'=>'Revenue (Month)','value'=>'Nu. '.number_format($stats['monthly_revenue']),'icon'=>'dollar-sign','color'=>'warning'],
        ['label'=>'Pending Verifications','value'=>$stats['pending_verifications'],'icon'=>'id-card','color'=>'secondary'],
    ] as $card)
    <div class="col-sm-6 col-xl-4">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-{{ $card['color'] }} bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px">
                    <i class="fa fa-{{ $card['icon'] }} text-{{ $card['color'] }}"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5">{{ $card['value'] }}</div>
                    <div class="text-muted small">{{ $card['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    <!-- Revenue Chart -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header fw-bold">Monthly Revenue (Nu.)</div>
            <div class="card-body">
                <canvas id="revenueChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <!-- Recent Users -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">Recent Users</span>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($recentUsers as $user)
                <li class="list-group-item d-flex align-items-center gap-3">
                    <img src="{{ $user->avatar_url }}" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;">
                    <div class="flex-grow-1">
                        <div class="small fw-semibold">{{ $user->name }}</div>
                        <div class="text-muted" style="font-size:11px">{{ $user->getRoleNames()->first() }} · {{ $user->created_at->diffForHumans() }}</div>
                    </div>
                    <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}" style="font-size:9px">{{ ucfirst($user->status) }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('revenueChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($revenueData->pluck('month')->toArray()) !!},
        datasets: [{
            label: 'Revenue (Nu.)',
            data: {!! json_encode($revenueData->pluck('total')->toArray()) !!},
            backgroundColor: 'rgba(255,107,53,0.7)',
            borderColor: '#FF6B35',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
@endsection
