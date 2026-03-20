@extends('layouts.guest')
@section('title', 'Two-Factor Authentication')
@section('content')
<div class="text-center mb-3">
    <i class="fa fa-shield-alt fa-3x" style="color:var(--druk-orange)"></i>
</div>
<h5 class="fw-bold text-center mb-2">Two-Factor Authentication</h5>
<p class="text-muted text-center small mb-4" id="2fa-hint">
    Enter the 6-digit code from your authenticator app.
</p>

<form method="POST" action="{{ route('two-factor.login') }}" id="2fa-form">
    @csrf
    <div id="code-section">
        <div class="mb-3">
            <label class="form-label small fw-semibold">Authentication Code</label>
            <input type="text" name="code" class="form-control text-center fs-5 letter-spacing-2" maxlength="6" pattern="[0-9]{6}" autofocus>
        </div>
    </div>
    <div id="recovery-section" class="d-none">
        <div class="mb-3">
            <label class="form-label small fw-semibold">Recovery Code</label>
            <input type="text" name="recovery_code" class="form-control">
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100 fw-semibold">Verify</button>
</form>

<div class="text-center mt-3">
    <button type="button" class="btn btn-link btn-sm text-decoration-none" id="toggle-2fa" style="color:var(--druk-orange)">
        Use recovery code instead
    </button>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('toggle-2fa').addEventListener('click', function() {
    const useCode = document.getElementById('code-section').classList.toggle('d-none');
    document.getElementById('recovery-section').classList.toggle('d-none', useCode);
    document.getElementById('2fa-hint').textContent = useCode
        ? 'Enter one of your emergency recovery codes.'
        : 'Enter the 6-digit code from your authenticator app.';
    this.textContent = useCode ? 'Use authentication code instead' : 'Use recovery code instead';
});
</script>
@endpush
