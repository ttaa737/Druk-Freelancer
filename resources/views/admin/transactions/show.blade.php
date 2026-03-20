@extends('layouts.admin')
@section('title', 'Transaction ' . $transaction->transaction_ref)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">Transaction Detail</h4>
    <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fa fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header fw-semibold">Transaction Info</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted small">Reference</div>
                        <div class="small fw-semibold font-monospace">{{ $transaction->transaction_ref }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Status</div>
                        <span class="badge bg-{{ match($transaction->status){ 'completed'=>'success','pending'=>'warning','failed','cancelled'=>'danger', default=>'secondary'} }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Type</div>
                        <div class="small fw-semibold">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Payment Provider</div>
                        <div class="small fw-semibold">{{ ucfirst($transaction->payment_provider ?? 'N/A') }}</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small">Amount</div>
                        <div class="fw-bold text-primary">Nu. {{ number_format($transaction->amount, 2) }}</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small">Fee</div>
                        <div class="small fw-semibold text-danger">Nu. {{ number_format($transaction->fee, 2) }}</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small">Net Amount</div>
                        <div class="small fw-semibold text-success">Nu. {{ number_format($transaction->net_amount, 2) }}</div>
                    </div>
                    @if($transaction->balance_before !== null)
                    <div class="col-sm-6">
                        <div class="text-muted small">Balance Before</div>
                        <div class="small fw-semibold">Nu. {{ number_format($transaction->balance_before, 2) }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Balance After</div>
                        <div class="small fw-semibold">Nu. {{ number_format($transaction->balance_after, 2) }}</div>
                    </div>
                    @endif
                    <div class="col-sm-6">
                        <div class="text-muted small">Date</div>
                        <div class="small fw-semibold">{{ $transaction->created_at->format('d M Y, H:i:s') }}</div>
                    </div>
                    @if($transaction->ip_address)
                    <div class="col-sm-6">
                        <div class="text-muted small">IP Address</div>
                        <div class="small fw-semibold font-monospace">{{ $transaction->ip_address }}</div>
                    </div>
                    @endif
                </div>

                @if($transaction->notes)
                <div class="mt-3">
                    <div class="text-muted small mb-1">Notes</div>
                    <div class="small p-2 bg-light rounded">{{ $transaction->notes }}</div>
                </div>
                @endif

                @if($transaction->payment_provider_ref)
                <div class="mt-3">
                    <div class="text-muted small mb-1">Provider Reference</div>
                    <div class="small font-monospace">{{ $transaction->payment_provider_ref }}</div>
                </div>
                @endif
            </div>
        </div>

        @if($transaction->contract)
        <div class="card">
            <div class="card-header fw-semibold">Related Contract</div>
            <div class="card-body">
                <div class="fw-semibold small">{{ $transaction->contract->title ?? 'Contract #' . $transaction->contract_id }}</div>
                <div class="text-muted small">Status: {{ ucfirst(str_replace('_', ' ', $transaction->contract->status)) }}</div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-semibold">User</div>
            <div class="card-body text-center">
                <img src="{{ $transaction->user?->avatar_url }}" class="rounded-circle mb-2" style="width:56px;height:56px;object-fit:cover" alt="">
                <div class="fw-semibold">{{ $transaction->user?->name }}</div>
                <div class="text-muted small">{{ $transaction->user?->email }}</div>
                @if($transaction->user)
                <a href="{{ route('admin.users.show', $transaction->user) }}" class="btn btn-sm btn-outline-primary mt-2">View User</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
