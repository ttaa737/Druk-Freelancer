<?php $__env->startSection('title', $user->name . ' - Reviews'); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-4">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex align-items-center gap-3 mb-4">
            <img src="<?php echo e($user->avatarUrl); ?>" class="rounded-circle" style="width:56px;height:56px;object-fit:cover" alt="">
            <div>
                <h5 class="fw-bold mb-0"><?php echo e($user->name); ?></h5>
                <div class="text-muted small">
                    <?php if($user->profile && $user->profile->rating): ?>
                        <i class="fa fa-star text-warning"></i>
                        <?php echo e(number_format($user->profile->rating, 1)); ?>

                        (<?php echo e($user->profile->total_reviews ?? $reviews->total()); ?> reviews)
                    <?php else: ?>
                        No reviews yet
                    <?php endif; ?>
                </div>
            </div>
            <a href="<?php echo e(route('profile.show', $user)); ?>" class="btn btn-sm btn-outline-secondary ms-auto">
                <i class="fa fa-arrow-left me-1"></i> Back to Profile
            </a>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <img src="<?php echo e($review->reviewer->avatarUrl); ?>" class="rounded-circle flex-shrink-0" style="width:38px;height:38px;object-fit:cover" alt="">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold small">
                                <?php if($review->is_anonymous): ?>
                                    Anonymous
                                <?php else: ?>
                                    <?php echo e($review->reviewer->name); ?>

                                <?php endif; ?>
                            </span>
                            <span class="text-muted" style="font-size:11px"><?php echo e($review->created_at->format('d M Y')); ?></span>
                        </div>
                        <div class="mb-2">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fa fa-star <?php echo e($i <= $review->overall_rating ? 'text-warning' : 'text-muted'); ?>" style="font-size:13px"></i>
                            <?php endfor; ?>
                            <span class="ms-1 small text-muted"><?php echo e(number_format($review->overall_rating, 1)); ?>/5</span>
                        </div>

                        <?php if($review->comment): ?>
                        <p class="mb-2 small"><?php echo e($review->comment); ?></p>
                        <?php endif; ?>

                        <?php if($review->communication_rating || $review->quality_rating || $review->timeliness_rating || $review->professionalism_rating): ?>
                        <div class="row g-2 mt-1">
                            <?php $__currentLoopData = [
                                'communication_rating' => 'Communication',
                                'quality_rating' => 'Quality',
                                'timeliness_rating' => 'Timeliness',
                                'professionalism_rating' => 'Professionalism',
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($review->$field): ?>
                                <div class="col-6 col-sm-3">
                                    <div class="text-muted" style="font-size:10px"><?php echo e($label); ?></div>
                                    <div class="d-flex align-items-center gap-1">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa fa-star <?php echo e($i <= $review->$field ? 'text-warning' : 'text-muted'); ?>" style="font-size:10px"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fa fa-star-half-alt fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">No reviews yet</h6>
                <p class="text-muted small"><?php echo e($user->name); ?> hasn't received any reviews yet.</p>
            </div>
        </div>
        <?php endif; ?>

        <?php echo e($reviews->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\reviews\index.blade.php ENDPATH**/ ?>