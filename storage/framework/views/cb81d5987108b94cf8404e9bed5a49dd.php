<?php $__env->startSection('title', 'My Proposals'); ?>
<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-4">My Proposals</h4>
<?php $__empty_1 = true; $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="fw-bold mb-1">
                    <a href="<?php echo e(route('jobs.show', $proposal->job->slug)); ?>" class="text-dark text-decoration-none"><?php echo e($proposal->job->title); ?></a>
                </h6>
                <div class="text-muted small mb-2">
                    <span class="me-3"><i class="fa fa-user me-1"></i><?php echo e($proposal->job->poster->name); ?></span>
                    <span class="me-3"><i class="fa fa-clock me-1"></i><?php echo e($proposal->created_at->diffForHumans()); ?></span>
                </div>
                <p class="text-muted small"><?php echo e(Str::limit($proposal->cover_letter, 100)); ?></p>
            </div>
            <div class="text-end ms-3" style="min-width:140px">
                <div class="fw-bold text-primary mb-1">Nu. <?php echo e(number_format($proposal->bid_amount)); ?></div>
                <span class="badge bg-<?php echo e($proposal->status === 'pending' ? 'warning text-dark' : ($proposal->status === 'awarded' ? 'success' : ($proposal->status === 'rejected' ? 'danger' : 'secondary'))); ?>">
                    <?php echo e(ucfirst($proposal->status)); ?>

                </span>
                <div class="mt-2">
                    <a href="<?php echo e(route('proposals.show', $proposal)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                    <?php if($proposal->status === 'pending'): ?>
                    <form method="POST" action="<?php echo e(route('proposals.withdraw', $proposal)); ?>" class="d-inline" onsubmit="return confirm('Withdraw this proposal?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger ms-1">Withdraw</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="card text-center py-5">
    <div class="card-body">
        <i class="fa fa-paper-plane fa-3x text-muted mb-3"></i>
        <h6 class="text-muted">You haven't submitted any proposals yet.</h6>
        <a href="<?php echo e(route('jobs.index')); ?>" class="btn btn-primary mt-2">Browse Jobs</a>
    </div>
</div>
<?php endif; ?>
<?php echo e($proposals->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\proposals\my.blade.php ENDPATH**/ ?>