<?php $__env->startSection('title', $user->name . ' – Profile'); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-4">
    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="card text-center mb-4">
            <div class="card-body pt-4">
                <img src="<?php echo e($user->avatar_url); ?>" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;" alt="<?php echo e($user->name); ?>">
                <h5 class="fw-bold mb-0"><?php echo e($user->name); ?></h5>
                <p class="text-muted small mb-1"><?php echo e($user->profile?->headline ?? ''); ?></p>
                <p class="text-muted small mb-2"><i class="fa fa-map-marker-alt me-1"></i><?php echo e($user->profile?->dzongkhag ?? 'Bhutan'); ?></p>
                <?php $avgRating = $user->profile?->average_rating ?? 0; ?>
                <div class="d-flex justify-content-center gap-1 mb-2">
                    <?php for($i=1;$i<=5;$i++): ?>
                    <i class="fa fa-star<?php echo e($i <= round($avgRating) ? '' : '-o'); ?> text-warning small"></i>
                    <?php endfor; ?>
                    <span class="text-muted small ms-1"><?php echo e(number_format($avgRating, 1)); ?> / 5.0</span>
                </div>
                <span class="badge <?php echo e($user->verification_status === 'verified' ? 'bg-success' : 'bg-secondary'); ?> mb-3">
                    <i class="fa fa-<?php echo e($user->verification_status === 'verified' ? 'check' : 'clock'); ?> me-1"></i><?php echo e(ucfirst($user->verification_status)); ?>

                </span>
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->id() !== $user->id): ?>
                    <div class="d-grid gap-2">
                        <form method="POST" action="<?php echo e(route('messages.start')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="recipient_id" value="<?php echo e($user->id); ?>">
                            <button type="submit" class="btn btn-outline-primary btn-sm w-100"><i class="fa fa-envelope me-1"></i>Message</button>
                        </form>
                    </div>
                    <?php else: ?>
                    <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-outline-secondary btn-sm w-100"><i class="fa fa-edit me-1"></i>Edit Profile</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if($user->profile?->hourly_rate): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Rate</h6>
                <p class="mb-0 fw-bold text-primary">Nu. <?php echo e(number_format($user->profile->hourly_rate)); ?> <small class="text-muted fw-normal">/ hr</small></p>
            </div>
        </div>
        <?php endif; ?>

        <?php if($user->skills->count()): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Skills</h6>
                <div class="d-flex flex-wrap gap-1">
                    <?php $__currentLoopData = $user->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="badge bg-light text-dark border small"><?php echo e($skill->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Stats</h6>
                <div class="row text-center">
                    <?php
                        $totalContracts     = $user->contractsAsFreelancer->count() + $user->contractsAsPoster->count();
                        $completedContracts = $user->contractsAsFreelancer->where('status','completed')->count() + $user->contractsAsPoster->where('status','completed')->count();
                    ?>
                    <div class="col-4"><div class="fw-bold text-primary"><?php echo e($totalContracts); ?></div><div class="text-muted" style="font-size:11px">Jobs</div></div>
                    <div class="col-4"><div class="fw-bold text-success"><?php echo e($completedContracts); ?></div><div class="text-muted" style="font-size:11px">Done</div></div>
                    <div class="col-4"><div class="fw-bold text-warning"><?php echo e(number_format($avgRating, 1)); ?></div><div class="text-muted" style="font-size:11px">Rating</div></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-8">
        <?php if($user->profile?->bio): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-2">About</h6>
                <p class="text-muted mb-0" style="white-space:pre-line"><?php echo e($user->profile->bio); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php if($portfolioItems->count()): ?>
        <div class="card mb-4">
            <div class="card-header fw-bold">Portfolio</div>
            <div class="card-body">
                <div class="row g-3">
                    <?php $__currentLoopData = $portfolioItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-sm-6 col-md-4">
                        <div class="border rounded overflow-hidden">
                            <?php if($item->image_path): ?>
                            <img src="<?php echo e(Storage::url($item->image_path)); ?>" class="w-100" style="height:140px;object-fit:cover;" alt="<?php echo e($item->title); ?>">
                            <?php endif; ?>
                            <div class="p-2">
                                <div class="fw-semibold small"><?php echo e($item->title); ?></div>
                                <p class="text-muted" style="font-size:11px;margin-bottom:0"><?php echo e(Str::limit($item->description, 80)); ?></p>
                                <?php if($item->url): ?><a href="<?php echo e($item->url); ?>" target="_blank" class="btn btn-link btn-sm p-0" style="font-size:11px">View <i class="fa fa-external-link-alt ms-1"></i></a><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($certifications->isNotEmpty()): ?>
        <div class="card mb-4">
            <div class="card-header fw-bold">Certifications</div>
            <ul class="list-group list-group-flush">
                <?php $__currentLoopData = $certifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item d-flex align-items-center gap-3">
                    <i class="fa fa-certificate text-warning"></i>
                    <div>
                        <div class="fw-semibold small"><?php echo e($cert->title); ?></div>
                        <div class="text-muted" style="font-size:11px"><?php echo e($cert->issuer); ?><?php echo e($cert->year ? ' – ' . $cert->year : ''); ?></div>
                    </div>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Reviews -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Reviews <span class="badge bg-secondary ms-1"><?php echo e($reviews->total()); ?></span></div>
            <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card-body border-bottom">
                <div class="d-flex gap-3">
                    <img src="<?php echo e($review->reviewer?->avatar_url ?? asset('images/default-avatar.png')); ?>" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" alt="">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold small"><?php echo e($review->is_anonymous ? 'Anonymous' : ($review->reviewer?->name ?? 'User')); ?></span>
                            <small class="text-muted"><?php echo e($review->created_at->diffForHumans()); ?></small>
                        </div>
                        <div class="d-flex gap-1 mb-1">
                            <?php for($i=1;$i<=5;$i++): ?><i class="fa fa-star<?php echo e($i <= $review->overall_rating ? '' : '-o'); ?> text-warning" style="font-size:11px"></i><?php endfor; ?>
                        </div>
                        <?php if($review->comment): ?><p class="text-muted small mb-0"><?php echo e($review->comment); ?></p><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card-body text-center text-muted small">No reviews yet.</div>
            <?php endif; ?>
            <?php if($reviews->hasPages()): ?>
            <div class="card-body pt-0"><?php echo e($reviews->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\profile\show.blade.php ENDPATH**/ ?>