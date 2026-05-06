<?php $__env->startSection('title', 'Deposit Funds'); ?>
<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-plus-circle me-2"></i>Deposit Funds</h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info mb-4">
                    <i class="fa fa-info-circle me-2"></i><strong>Current Balance:</strong> Nu. <?php echo e(number_format(auth()->user()->wallet?->available_balance ?? 0, 2)); ?>

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

                <form method="POST" action="<?php echo e(route('wallet.deposit')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Payment Provider <span class="text-danger">*</span></label>
                        <select name="provider" class="form-select <?php $__errorArgs = ['provider'];
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
                        <label class="form-label fw-semibold">Amount (Nu.) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control form-control-lg <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               min="100" max="1000000" step="1" value="<?php echo e(old('amount', 1000)); ?>" 
                               placeholder="Enter amount" required>
                        <div class="form-text"><i class="fa fa-info-circle me-1"></i>Minimum: Nu. 100 | Maximum: Nu. 100,000</div>
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
                        <label class="form-label fw-semibold">Account Number / Mobile Number <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" class="form-control <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="e.g., 1234567890 or 17XXXXXX" value="<?php echo e(old('account_number')); ?>"
                               maxlength="50" required>
                        <div class="form-text"><i class="fa fa-info-circle me-1"></i>Enter the account/mobile number used for this deposit</div>
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
                        <label class="form-label fw-semibold">Transaction Reference (from your payment app) <span class="text-danger">*</span></label>
                        <input type="text" name="provider_ref" class="form-control <?php $__errorArgs = ['provider_ref'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               placeholder="e.g. TXN12345 or REF789456" value="<?php echo e(old('provider_ref')); ?>" 
                               maxlength="100" required>
                        <div class="form-text"><i class="fa fa-info-circle me-1"></i>Enter the transaction ID/reference from your payment confirmation</div>
                        <?php $__errorArgs = ['provider_ref'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg fw-semibold">
                            <i class="fa fa-check-circle me-2"></i>Confirm Deposit
                        </button>
                        <a href="<?php echo e(route('wallet.index')); ?>" class="btn btn-outline-secondary">
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
                    <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6">
                        <div class="border rounded p-2 text-center">
                            <i class="fa fa-mobile-alt text-primary mb-1"></i>
                            <div class="small fw-semibold"><?php echo e(explode(' - ', $provider['name'])[0]); ?></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-control-lg { font-size: 1.1rem; padding: 0.75rem; }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\wallet\deposit.blade.php ENDPATH**/ ?>