<?php $__env->startSection('title', 'Withdraw Funds'); ?>
<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-arrow-circle-up me-2"></i>Withdraw Funds</h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fa fa-wallet me-2"></i><strong>Available Balance:</strong></span>
                        <span class="fw-bold fs-5">Nu. <?php echo e(number_format($wallet?->available_balance ?? 0, 2)); ?></span>
                    </div>
                </div>

                <?php if(($wallet?->available_balance ?? 0) < 500): ?>
                <div class="alert alert-warning">
                    <i class="fa fa-exclamation-triangle me-2"></i>
                    Minimum withdrawal amount is Nu. 500. Please deposit funds or earn more to withdraw.
                </div>
                <div class="text-center mt-4">
                    <a href="<?php echo e(route('wallet.deposit.form')); ?>" class="btn btn-success">
                        <i class="fa fa-plus-circle me-2"></i>Deposit Funds
                    </a>
                    <a href="<?php echo e(route('wallet.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left me-2"></i>Back to Wallet
                    </a>
                </div>
                <?php else: ?>
                
                <div class="alert alert-light border mb-4">
                    <h6 class="fw-bold mb-2"><i class="fa fa-lightbulb text-warning me-2"></i>How to Withdraw</h6>
                    <ol class="mb-0 small">
                        <li>Enter withdrawal amount and select your Bhutanese payment provider</li>
                        <li>Enter your account/mobile number registered with the provider</li>
                        <li>We'll send an OTP to your email for security verification</li>
                        <li>Enter the OTP to confirm your withdrawal request</li>
                        <li>Funds will be transferred within 1-2 business days</li>
                    </ol>
                </div>

                <form method="POST" action="<?php echo e(route('wallet.withdraw')); ?>" id="withdrawForm">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Withdrawal Amount (Nu.) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="amount" class="form-control form-control-lg <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               min="500" max="<?php echo e($wallet?->available_balance ?? 0); ?>" step="1" 
                               value="<?php echo e(old('amount')); ?>" placeholder="Enter amount" required>
                        <div class="form-text"><i class="fa fa-info-circle me-1"></i>Minimum: Nu. 500 | Available: Nu. <?php echo e(number_format($wallet?->available_balance ?? 0, 2)); ?></div>
                        <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Payment Provider <span class="text-danger">*</span></label>
                        <select name="provider" id="provider" class="form-select <?php $__errorArgs = ['provider'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Select Your Payment Provider --</option>
                            <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(old('provider') == $key ? 'selected' : ''); ?>>
                                <?php echo e($provider['name']); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['provider'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Account Number / Mobile Number <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" id="account_number" class="form-control <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               placeholder="e.g., 1234567890 or 17XXXXXX" value="<?php echo e(old('account_number')); ?>" 
                               maxlength="50" required>
                        <div class="form-text"><i class="fa fa-info-circle me-1"></i>Enter the account/mobile number registered with your payment provider</div>
                        <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <button type="button" class="btn btn-warning w-100" onclick="sendOTP()" id="sendOtpBtn">
                            <i class="fa fa-shield-alt me-2"></i>Send OTP to Email
                        </button>
                    </div>

                    <div class="mb-4" id="otp-section" style="display: <?php echo e($errors->has('otp') ? 'block' : 'none'); ?>;">
                        <label class="form-label fw-semibold">Enter OTP Code <span class="text-danger">*</span></label>
                        <input type="text" name="otp" id="otp" class="form-control form-control-lg text-center fw-bold <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               maxlength="6" pattern="[0-9]{6}" placeholder="000000" style="letter-spacing: 0.5em;">
                        <div class="form-text"><i class="fa fa-clock me-1"></i>OTP is valid for 10 minutes</div>
                        <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold" id="submit-btn" <?php echo e($errors->has('otp') ? '' : 'disabled'); ?>>
                            <i class="fa fa-check-circle me-2"></i>Confirm Withdrawal
                        </button>
                        <a href="<?php echo e(route('wallet.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left me-2"></i>Back to Wallet
                        </a>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <?php if(($wallet?->available_balance ?? 0) >= 500): ?>
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-shield-alt text-success me-2"></i>Supported Payment Methods</h6>
                <div class="row g-2">
                    <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6">
                        <div class="border rounded p-2 text-center">
                            <i class="fa fa-mobile-alt text-primary mb-1"></i>
                            <div class="small fw-semibold"><?php echo e(explode(' - ', $provider['name'])[0]); ?></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="alert alert-warning mt-3 mb-0">
                    <small><i class="fa fa-info-circle me-1"></i><strong>Processing Time:</strong> Withdrawals are processed within 1-2 business days. You will receive a confirmation email once the transfer is complete.</small>
                </div>
            </div>
        </div>

        <?php if($paymentMethods->count() > 0): ?>
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-bookmark text-primary me-2"></i>Your Saved Payment Methods</h6>
                <p class="text-muted small mb-3">Quick access to your previously used payment accounts</p>
                <div class="list-group">
                    <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold text-primary"><?php echo e(strtoupper($pm->provider)); ?></div>
                                <div class="text-muted small"><?php echo e($pm->account_number); ?></div>
                                <?php if($pm->account_name): ?>
                                <div class="text-muted small"><?php echo e($pm->account_name); ?></div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?php if($pm->is_default): ?>
                                <span class="badge bg-success mb-1">Primary</span>
                                <?php endif; ?>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="usePaymentMethod('<?php echo e($pm->provider); ?>', '<?php echo e($pm->account_number); ?>')">
                                    <i class="fa fa-arrow-right"></i> Use
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let otpSent = <?php echo e($errors->has('otp') ? 'true' : 'false'); ?>;

function sendOTP() {
    const amount = document.getElementById('amount').value;
    const provider = document.getElementById('provider').value;
    const accountNumber = document.getElementById('account_number').value;

    if (!amount || !provider || !accountNumber) {
        showAlert('Please fill in all withdrawal details first', 'warning');
        return;
    }

    if (parseFloat(amount) < 500) {
        showAlert('Minimum withdrawal amount is Nu. 500', 'warning');
        return;
    }

    const maxAmount = <?php echo e($wallet?->available_balance ?? 0); ?>;
    if (parseFloat(amount) > maxAmount) {
        showAlert('Withdrawal amount exceeds available balance', 'warning');
        return;
    }

    // Disable button and show loading
    const btn = document.getElementById('sendOtpBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Sending OTP...';

    fetch('<?php echo e(route("wallet.withdraw.otp")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ amount, provider, account_number: accountNumber })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('otp-section').style.display = 'block';
            document.getElementById('submit-btn').disabled = false;
            document.getElementById('otp').focus();
            otpSent = true;
            showAlert('OTP sent to your email. Please check your inbox and enter the code below.', 'success');
            btn.innerHTML = '<i class="fa fa-check-circle me-2"></i>OTP Sent';
        } else {
            btn.disabled = false;
            btn.innerHTML = originalText;
            showAlert(data.message || 'Failed to send OTP. Please try again.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = originalText;
        showAlert('An error occurred. Please try again.', 'danger');
    });
}

function usePaymentMethod(provider, accountNumber) {
    document.getElementById('provider').value = provider;
    document.getElementById('account_number').value = accountNumber;
    window.scrollTo({ top: 0, behavior: 'smooth' });
    showAlert('Payment method selected. Enter withdrawal amount and send OTP.', 'info');
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fa fa-info-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    const form = document.getElementById('withdrawForm');
    form.parentNode.insertBefore(alertDiv, form);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-control-lg { 
        font-size: 1.1rem; 
        padding: 0.75rem; 
    }
    .list-group-item {
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }
    .list-group-item:hover {
        border-left-color: var(--druk-orange);
        background-color: #f8f9fa;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/wallet/withdraw.blade.php ENDPATH**/ ?>