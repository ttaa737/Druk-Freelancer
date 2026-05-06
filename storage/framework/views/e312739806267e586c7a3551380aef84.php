<?php $__env->startSection('title', 'My Disputes'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="fa fa-gavel me-2"></i>Disputes</h5>
</div>
<?php $__empty_1 = true; $__currentLoopData = $disputes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dispute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between gap-2">
            <div>
                <h6 class="fw-semibold mb-1"><?php echo e($dispute->subject); ?></h6>
                <small class="text-muted">Contract: <?php echo e($dispute->contract?->title); ?> · Raised <?php echo e($dispute->created_at->diffForHumans()); ?></small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-<?php echo e(match($dispute->status) { 'open'=>'warning','under_review'=>'info','resolved'=>'success','closed'=>'secondary', default=>'secondary'}); ?>">
                    <?php echo e(ucfirst(str_replace('_',' ',$dispute->status))); ?>

                </span>
                <a href="<?php echo e(route('disputes.show', $dispute)); ?>" class="btn btn-sm btn-outline-primary">View</a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="text-center py-5 text-muted">
    <i class="fa fa-balance-scale fa-3x mb-3 opacity-25"></i>
    <p>No disputes raised.</p>
</div>
<?php endif; ?>
<?php echo e($disputes->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\disputes\index.blade.php ENDPATH**/ ?>