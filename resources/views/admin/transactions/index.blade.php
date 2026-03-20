@extends('layouts.admin')
@section('title', 'Transactions')
@section('content')
<h4 class="fw-bold mb-4">Transactions</h4>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total Revenue','value'=>'Nu. '.number_format($summary['total_revenue']),'color'=>'success'],
        ['label'=>'Pending Withdrawals','value'=>$summary['pending_withdrawals'],'color'=>'warning'],
        ['label'=>'Total Deposits','value'=>'Nu. '.number_format($summary['total_deposits']),'color'=>'info'],
        ['label'=>'Total Withdrawn','value'=>'Nu. '.number_format($summary['total_withdrawn']),'color'=>'danger'],
    ] as $card)
    <div class="col-sm-6 col-xl-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-bold fs-5 text-{{ $card['color'] }}">{{ $card['value'] }}</div>
                <div class="text-muted small">{{ $card['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2">
            <div class="col-sm-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="User or Ref..." value="{{ request('search') }}"></div>
            <div class="col-sm-2">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach(['deposit','withdrawal','escrow_hold','escrow_release','platform_fee','refund'] as $t)
                    <option value="{{ $t }}" @selected(request('type')===$t)>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach(['pending','completed','failed','cancelled'] as $s)
                    <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2"><input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}"></div>
            <div class="col-sm-2"><input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}"></div>
            <div class="col-auto d-flex gap-1">
                <button class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr>
                <th>User</th><th>Type</th><th>Amount</th><th>Provider</th><th>Ref</th><th>Status</th><th>Date</th><th class="text-end">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($transactions as $tx)
                <tr>
                    <td><small class="fw-semibold">{{ $tx->user?->name }}</small></td>
                    <td><span class="badge bg-light text-dark border" style="font-size:10px">{{ ucfirst(str_replace('_',' ',$tx->type)) }}</span></td>
                    <td><span class="fw-semibold {{ $tx->amount < 0 ? 'text-danger' : 'text-success' }}">Nu. {{ number_format(abs($tx->amount)) }}</span></td>
                    <td><small class="text-muted">{{ $tx->payment_provider ?? '—' }}</small></td>
                    <td><small class="text-muted font-monospace">{{ Str::limit($tx->transaction_ref, 14) }}</small></td>
                    <td><span class="badge bg-{{ match($tx->status){ 'completed'=>'success','pending'=>'warning','failed'=>'danger','cancelled'=>'secondary', default=>'secondary'} }}" style="font-size:10px">{{ ucfirst($tx->status) }}</span></td>
                    <td><small class="text-muted">{{ $tx->created_at->format('d M Y') }}</small></td>
                    <td class="text-end">
                        @if($tx->type === 'withdrawal' && $tx->status === 'pending')
                        <div class="d-flex gap-1 justify-content-end">
                            <form method="POST" action="{{ route('admin.transactions.approve', $tx) }}">@csrf <button class="btn btn-sm btn-success" title="Approve">✓</button></form>
                            <form method="POST" action="{{ route('admin.transactions.reject', $tx) }}" class="d-flex gap-1">
                                @csrf
                                <input type="text" name="reason" class="form-control form-control-sm" placeholder="Reason" required style="width:100px">
                                <button class="btn btn-sm btn-danger" title="Reject">✗</button>
                            </form>
                        </div>
                        @else
                        <span class="text-muted small">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No transactions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0">{{ $transactions->withQueryString()->links() }}</div>
</div>
@endsection
