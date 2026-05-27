<?php $__env->startSection('title', 'Verify Email'); ?>
<?php $__env->startSection('content'); ?>
<div class="text-center mb-3">
    <i class="fa fa-envelope-open-text fa-3x" style="color:var(--druk-orange)"></i>
</div>
<h5 class="fw-bold text-center mb-2">Verify Your Email Address</h5>
<p class="text-muted text-center small mb-4">
    Thanks for signing up! Please verify your email address by clicking the link we just emailed you.
</p>

<?php if(session('status') == 'verification-link-sent'): ?>
    <div class="alert alert-success small text-center">A new verification link has been sent.</div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('verification.send')); ?>">
    <?php echo csrf_field(); ?>
    <button type="submit" class="btn btn-primary w-100 fw-semibold">Resend Verification Email</button>
</form>

<form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-3">
    <?php echo csrf_field(); ?>
    <button type="submit" class="btn btn-outline-secondary w-100 btn-sm">Log Out</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>