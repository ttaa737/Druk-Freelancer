
<?php $__env->startSection('title', 'Browse Jobs'); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <!-- Filters Sidebar -->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-filter me-2"></i>Filter Jobs</h6>
                <form method="GET" action="<?php echo e(route('jobs.index')); ?>">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Search</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Keywords..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Category</label>
                        <select name="category" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Job Type</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">All Types</option>
                            <option value="fixed" <?php echo e(request('type')=='fixed'?'selected':''); ?>>Fixed Price</option>
                            <option value="hourly" <?php echo e(request('type')=='hourly'?'selected':''); ?>>Hourly</option>
                            <option value="milestone" <?php echo e(request('type')=='milestone'?'selected':''); ?>>Milestone</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Location (Dzongkhag)</label>
                        <select name="dzongkhag" class="form-select form-select-sm">
                            <option value="">All Dzongkhags</option>
                            <?php $__currentLoopData = \App\Models\Profile::DZONGKHAGS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dz); ?>" <?php echo e(request('dzongkhag')==$dz?'selected':''); ?>><?php echo e($dz); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Budget Range (Nu.)</label>
                        <div class="row g-1">
                            <div class="col-6"><input type="number" name="budget_min" class="form-control form-control-sm" placeholder="Min" value="<?php echo e(request('budget_min')); ?>"></div>
                            <div class="col-6"><input type="number" name="budget_max" class="form-control form-control-sm" placeholder="Max" value="<?php echo e(request('budget_max')); ?>"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Apply Filters</button>
                    <a href="<?php echo e(route('jobs.index')); ?>" class="btn btn-outline-secondary btn-sm w-100 mt-1">Clear</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Job Listings -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><?php echo e($jobs->total()); ?> Jobs Found</h5>
            <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->hasRole('job_poster')): ?>
            <a href="<?php echo e(route('jobs.create')); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i>Post a Job</a>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">
                            <a href="<?php echo e(route('jobs.show', $job->slug)); ?>" class="text-dark text-decoration-none"><?php echo e($job->title); ?></a>
                        </h6>
                        <div class="text-muted small mb-2">
                            <span class="me-3"><i class="fa fa-user me-1"></i><?php echo e($job->poster->name); ?></span>
                            <?php if($job->profile?->dzongkhag ?? $job->location): ?>
                            <span class="me-3"><i class="fa fa-map-marker-alt me-1"></i><?php echo e($job->location ?? 'Bhutan'); ?></span>
                            <?php endif; ?>
                            <span class="me-3"><i class="fa fa-clock me-1"></i><?php echo e($job->created_at->diffForHumans()); ?></span>
                        </div>
                        <p class="text-muted small mb-2"><?php echo e(Str::limit($job->description, 150)); ?></p>
                        <?php if($job->deadline || $job->job_deadline || $job->duration_days): ?>
                        <?php
                            $proposalDeadlinePast = $job->deadline ? ($job->deadline->isToday() || $job->deadline->isPast()) : false;
                            $completionDeadline = $job->job_deadline ?: (($job->deadline && $job->duration_days) ? $job->deadline->copy()->addDays((int) $job->duration_days) : null);
                        ?>
                        <div class="small mb-2 text-muted">
                            <?php if($job->deadline): ?>
                            <div class="<?php echo e($proposalDeadlinePast ? 'text-danger fw-semibold' : ''); ?>">
                                <i class="fa fa-calendar-alt me-1"></i>
                                Proposal deadline: <?php echo e($job->deadline->format('d/m/Y')); ?>

                                <?php if($proposalDeadlinePast): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-1">Due now</span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            <?php if($completionDeadline): ?>
                            <div class="mt-1">
                                <i class="fa fa-flag-checkered me-1"></i>
                                Project deadline: <?php echo e($completionDeadline->format('d/m/Y')); ?>

                            </div>
                            <?php elseif($job->duration_days): ?>
                            <div class="mt-1">
                                <i class="fa fa-flag-checkered me-1"></i>
                                Job deadline: within <?php echo e((int) $job->duration_days); ?> days
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex flex-wrap gap-1">
                            <?php $__currentLoopData = $job->skills->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-light text-dark border"><?php echo e($skill->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="text-end ms-3" style="min-width:120px">
                        <div class="fw-bold text-primary"><?php echo e($job->budgetRange); ?></div>
                        <div class="badge bg-secondary mb-1"><?php echo e(ucfirst($job->type)); ?></div>
                        <div class="text-muted small"><?php echo e($job->proposals_count); ?> proposals</div>
                        <a href="<?php echo e(route('jobs.show', $job->slug)); ?>" class="btn btn-outline-primary btn-sm mt-2 d-block">View Job</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fa fa-search fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">No jobs found matching your criteria.</h6>
                <a href="<?php echo e(route('jobs.index')); ?>" class="btn btn-outline-primary mt-2">Clear Filters</a>
            </div>
        </div>
        <?php endif; ?>

        <div class="d-flex justify-content-center">
            <?php echo e($jobs->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/jobs/index.blade.php ENDPATH**/ ?>