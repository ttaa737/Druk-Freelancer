
<?php $__env->startSection('title', 'Forgot Password'); ?>
<?php $__env->startSection('content'); ?>

<div class="text-center mb-6">
    <div class="w-16 h-16 bg-druk-orange/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <i class="fa fa-key text-druk-orange text-2xl"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-800">Reset Your Password</h2>
    <p class="text-gray-500 text-sm mt-1">Enter your email and we'll send you a reset link.</p>
</div>

<?php if(session('status')): ?>
<div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm mb-4">
    <i class="fa fa-check-circle text-green-500"></i> <?php echo e(session('status')); ?>

</div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('password.email')); ?>" class="space-y-4">
    <?php echo csrf_field(); ?>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
               class="w-full border <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php else: ?> border-gray-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-druk-orange/20 focus:border-druk-orange transition-all">
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <button type="submit" class="w-full bg-druk-orange hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition-all hover:scale-[1.01] shadow-lg shadow-druk-orange/25">
        <i class="fa fa-paper-plane mr-2"></i>Send Reset Link
    </button>
</form>

<p class="text-center text-sm mt-5">
    <a href="<?php echo e(route('login')); ?>" class="font-medium text-druk-orange hover:text-orange-600 transition-colors">
        <i class="fa fa-arrow-left mr-1"></i>Back to Login
    </a>
</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>