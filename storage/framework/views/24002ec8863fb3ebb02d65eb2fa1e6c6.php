
<?php $__env->startSection('title', 'Forgot Password'); ?>
<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-1 text-center">Reset Your Password</h4>
<p class="text-muted text-center small mb-4">Enter your email and we will send you a password reset link.</p>

<?php if(session('status')): ?>
    <div class="alert alert-success small mb-3">
        <i class="fa fa-check-circle me-1"></i><?php echo e(session('status')); ?>

    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('password.email')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <label class="form-label small fw-semibold">Email Address</label>
        <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               value="<?php echo e(old('email')); ?>" required autofocus>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <button type="submit" class="btn btn-primary w-100 fw-semibold">
        <i class="fa fa-paper-plane me-1"></i>Send Reset Link
    </button>
</form>

<hr class="my-3">
<p class="text-center small mb-0">
    <a href="<?php echo e(route('login')); ?>" class="text-decoration-none fw-semibold" style="color:var(--druk-orange)">
        <i class="fa fa-arrow-left me-1"></i>Back to Login
    </a>
</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>