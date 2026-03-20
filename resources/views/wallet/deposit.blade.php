@extends('layouts.app')
@section('title', 'Deposit Funds')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-plus-circle me-2"></i>Deposit Funds</h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info mb-4">
                    <i class="fa fa-info-circle me-2"></i><strong>Current Balance:</strong> Nu. {{ number_format(auth()->user()->wallet?->available_balance ?? 0, 2) }}
                </div>

                <div class="alert alert-light border mb-4">
                    <h6 class="fw-bold mb-2"><i class="fa fa-lightbulb text-warning me-2"></i>How to Deposit</h6>
                    <ol class="mb-0 small">
                        <li>Complete payment through your Bhutanese mobile banking or digital wallet app</li>
                        <li>Copy the transaction reference/ID from your payment app</li>
                        <li>Enter the details below and submit</li>
                        <li>Funds will be credited instantly upon verification</li>
                    </ol>
                </div>

                <form method="POST" action="{{ route('wallet.deposit') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Payment Provider <span class="text-danger">*</span></label>
                        <select name="provider" class="form-select @error('provider') is-invalid @enderror" required>
                            <option value="">-- Select Your Payment Provider --</option>
                            @foreach($providers as $key => $provider)
                            <option value="{{ $key }}" {{ old('provider') == $key ? 'selected' : '' }}>
                                {{ $provider['name'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('provider') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Amount (Nu.) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control form-control-lg @error('amount') is-invalid @enderror" 
                               min="100" max="1000000" step="1" value="{{ old('amount', 1000) }}" 
                               placeholder="Enter amount" required>
                        <div class="form-text"><i class="fa fa-info-circle me-1"></i>Minimum: Nu. 100 | Maximum: Nu. 100,000</div>
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Transaction Reference (from your payment app) <span class="text-danger">*</span></label>
                        <input type="text" name="provider_ref" class="form-control @error('provider_ref') is-invalid @enderror" 
                               placeholder="e.g. TXN12345 or REF789456" value="{{ old('provider_ref') }}" 
                               maxlength="100" required>
                        <div class="form-text"><i class="fa fa-info-circle me-1"></i>Enter the transaction ID/reference from your payment confirmation</div>
                        @error('provider_ref') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg fw-semibold">
                            <i class="fa fa-check-circle me-2"></i>Confirm Deposit
                        </button>
                        <a href="{{ route('wallet.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left me-2"></i>Back to Wallet
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-shield-alt text-success me-2"></i>Supported Payment Methods</h6>
                <div class="row g-2">
                    @foreach($providers as $key => $provider)
                    <div class="col-6">
                        <div class="border rounded p-2 text-center">
                            <i class="fa fa-mobile-alt text-primary mb-1"></i>
                            <div class="small fw-semibold">{{ explode(' - ', $provider['name'])[0] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-control-lg { font-size: 1.1rem; padding: 0.75rem; }
</style>
@endpush
