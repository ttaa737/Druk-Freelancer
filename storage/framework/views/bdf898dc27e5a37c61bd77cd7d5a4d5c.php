<?php $__env->startSection('title', 'My Jobs'); ?>
<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">My Jobs</li>
    </ol>
</nav>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="mb-1"><i class="fa fa-briefcase me-2"></i>My Job Postings</h3>
        <p class="text-muted small mb-0">Manage and track all your job listings</p>
    </div>
    <a href="<?php echo e(route('jobs.create')); ?>" class="btn btn-primary">
        <i class="fa fa-plus me-1"></i> Post New Job
    </a>
</div>

<?php $__empty_1 = true; $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <div class="row align-items-start">
            <div class="col-lg-8">
                <h5 class="card-title mb-2">
                    <a href="<?php echo e(route('jobs.show', $job->slug)); ?>" class="text-dark text-decoration-none"><?php echo e($job->title); ?></a>
                </h5>
                
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <?php
                        $statusClasses = match($job->status) {
                            'open'        => 'bg-success',
                            'in_progress' => 'bg-info',
                            'closed'      => 'bg-secondary',
                            default       => 'bg-danger',
                        };
                    ?>
                    <span class="badge <?php echo e($statusClasses); ?>">
                        <?php echo e(ucfirst(str_replace('_', ' ', $job->status))); ?>

                    </span>
                    <?php if($job->category): ?>
                    <span class="text-muted small">
                        <i class="fa fa-folder"></i> <?php echo e($job->category->name); ?>

                    </span>
                    <?php endif; ?>
                    <span class="text-muted small">
                        <i class="fa fa-inbox"></i> <strong><?php echo e($job->proposals_count); ?></strong> <?php echo e($job->proposals_count == 1 ? 'proposal' : 'proposals'); ?>

                    </span>
                    <span class="text-muted small">
                        <i class="fa fa-clock"></i> <?php echo e($job->created_at->diffForHumans()); ?>

                    </span>
                </div>

                <?php if($job->deadline || $job->duration_days): ?>
                <?php
                    $completionDate = ($job->deadline && $job->duration_days) ? $job->deadline->copy()->addDays((int) $job->duration_days) : null;
                ?>
                <div class="small text-muted mb-2">
                    <?php if($job->deadline): ?>
                    <div><i class="fa fa-calendar-alt me-1"></i>Proposal deadline: <?php echo e($job->deadline->format('d/m/Y')); ?></div>
                    <?php endif; ?>
                    <?php if($job->duration_days): ?>
                    <div class="mt-1"><i class="fa fa-flag-checkered me-1"></i>Job completion: <?php echo e($completionDate ? $completionDate->format('d/m/Y') : ('within ' . (int) $job->duration_days . ' days')); ?></div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if($job->description): ?>
                <p class="text-muted small mb-0"><?php echo e(Str::limit($job->description, 150)); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-4 mt-3 mt-lg-0">
                <div class="text-lg-end mb-2">
                    <h5 class="text-primary mb-0"><?php echo e($job->budgetRange); ?></h5>
                </div>
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <?php if($job->proposals_count > 0): ?>
                    <a href="<?php echo e(route('jobs.proposals', $job)); ?>" class="btn btn-info btn-sm">
                        <i class="fa fa-inbox me-1"></i> Proposals (<?php echo e($job->proposals_count); ?>)
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('jobs.edit', $job)); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-edit me-1"></i> Edit
                    </a>
                    <a href="<?php echo e(route('jobs.show', $job->slug)); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-eye me-1"></i> View
                    </a>
                    <form method="POST" action="<?php echo e(route('jobs.destroy', $job)); ?>" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this job posting? This action cannot be undone.')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fa fa-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="card shadow-sm">
    <div class="card-body text-center py-5">
        <div class="mb-4">
            <i class="fa fa-briefcase text-muted" style="font-size: 4rem;"></i>
        </div>
        <h5 class="mb-2">No Job Postings Yet</h5>
        <p class="text-muted mb-4">
            Start hiring talented freelancers by posting your first job. It only takes a few minutes!
        </p>
        <a href="<?php echo e(route('jobs.create')); ?>" class="btn btn-primary">
            <i class="fa fa-plus-circle me-1"></i> Post Your First Job
        </a>
        
        <div class="row g-3 mt-4">
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <i class="fa fa-search text-primary mb-2" style="font-size: 2rem;"></i>
                    <h6 class="mb-1">Find Talent</h6>
                    <small class="text-muted">Browse qualified freelancers</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <i class="fa fa-handshake text-primary mb-2" style="font-size: 2rem;"></i>
                    <h6 class="mb-1">Review Proposals</h6>
                    <small class="text-muted">Get proposals from experts</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <i class="fa fa-check-circle text-primary mb-2" style="font-size: 2rem;"></i>
                    <h6 class="mb-1">Hire & Pay</h6>
                    <small class="text-muted">Secure payment via escrow</small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="mt-4">
    <?php echo e($jobs->links()); ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/jobs/my-jobs.blade.php ENDPATH**/ ?>