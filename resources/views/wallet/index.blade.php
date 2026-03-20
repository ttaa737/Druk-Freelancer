@extends('layouts.app')
@section('title', 'My Wallet')
@section('content')
<h4 class="fw-bold mb-4">My Wallet</h4>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa fa-wallet fa-2x opacity-75"></i>
                    <div>
                        <div class="small opacity-75">Available Balance</div>
                        <div class="fw-bold fs-4">Nu. {{ number_format($wallet->available_balance) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa fa-lock fa-2x opacity-75"></i>
                    <div>
                        <div class="small opacity-75">In Escrow</div>
                        <div class="fw-bold fs-4">Nu. {{ number_format($wallet->escrow_balance) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa fa-chart-line fa-2x opacity-75"></i>
                    <div>
                        <div class="small opacity-75">Total Earned</div>
                        <div class="fw-bold fs-4">Nu. {{ number_format($wallet->total_earned) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Payment Methods + Actions -->
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Quick Actions</h6>
                <a href="{{ route('wallet.deposit.form') }}" class="btn btn-success w-100 mb-2"><i class="fa fa-plus me-1"></i>Deposit Funds</a>
                <a href="{{ route('wallet.withdraw.form') }}" class="btn btn-outline-primary w-100"><i class="fa fa-arrow-up me-1"></i>Withdraw</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Payment Methods</h6>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addMethodModal">+ Add</button>
                </div>
                @foreach($paymentMethods as $pm)
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div>
                        <strong class="small">{{ strtoupper($pm->provider) }}</strong>
                        @if($pm->account_name) <div class="text-muted" style="font-size:.75rem">{{ $pm->account_name }}</div> @endif
                        <div class="text-muted" style="font-size:.75rem">••••{{ substr($pm->account_number, -4) }}</div>
                    </div>
                    @if($pm->is_default) <span class="badge bg-success">Default</span> @endif
                </div>
                @endforeach
                @if($paymentMethods->isEmpty())
                <p class="text-muted small text-center mt-2">No payment methods added yet.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Transaction History</h6>
                @forelse($transactions as $txn)
                <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle p-2 bg-{{ in_array($txn->type, ['deposit','escrow_release']) ? 'success' : 'danger' }} bg-opacity-10">
                            <i class="fa fa-{{ in_array($txn->type, ['deposit','escrow_release']) ? 'arrow-down text-success' : 'arrow-up text-danger' }}"></i>
                        </div>
                        <div>
                            <div class="fw-semibold small">{{ ucwords(str_replace('_', ' ', $txn->type)) }}</div>
                            <div class="text-muted" style="font-size:.75rem">{{ $txn->transaction_ref }} • {{ $txn->created_at->format('d M Y H:i') }}</div>
                            @if($txn->description) <div class="text-muted" style="font-size:.75rem">{{ $txn->description }}</div> @endif
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold {{ in_array($txn->type, ['deposit','escrow_release']) ? 'text-success' : 'text-danger' }}">
                            {{ in_array($txn->type, ['deposit','escrow_release']) ? '+' : '-' }}Nu. {{ number_format($txn->amount) }}
                        </div>
                        <span class="badge bg-{{ $txn->status === 'completed' ? 'success' : ($txn->status === 'pending' ? 'warning text-dark' : 'danger') }}">{{ ucfirst($txn->status) }}</span>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3">No transactions yet.</p>
                @endforelse
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Method Modal -->
<div class="modal fade" id="addMethodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('wallet.payment-method.add') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Payment Provider</label>
                        <select name="provider" class="form-select" required>
                            <option value="">Select Provider</option>
                            @foreach($providers as $key => $provider)
                            <option value="{{ $key }}">{{ $provider['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account/Phone Number</label>
                        <input type="text" name="account_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Name (optional)</label>
                        <input type="text" name="account_name" class="form-control">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_default" value="1" class="form-check-input" id="isDefault">
                        <label class="form-check-label small" for="isDefault">Set as default payment method</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Method</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
