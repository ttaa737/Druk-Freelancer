<?php $__env->startSection('title', 'Proposals for ' . $job->title); ?>
<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('jobs.my')); ?>" class="text-decoration-none">My Jobs</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('jobs.show', $job->slug)); ?>" class="text-decoration-none"><?php echo e(Str::limit($job->title, 30)); ?></a></li>
        <li class="breadcrumb-item active" aria-current="page">Proposals</li>
    </ol>
</nav>

<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="fw-bold mb-1"><i class="fa fa-file-alt me-2"></i><?php echo e($job->title); ?></h5>
                <p class="text-muted small mb-0">
                    <i class="fa fa-money-bill-wave me-1"></i>Budget: Nu. <?php echo e(number_format($job->budget_min)); ?> - Nu. <?php echo e(number_format($job->budget_max)); ?>

                    <span class="mx-2">•</span>
                    <i class="fa fa-calendar me-1"></i>Posted <?php echo e($job->created_at->diffForHumans()); ?>

                </p>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <span class="badge bg-primary fs-6"><i class="fa fa-paper-plane me-1"></i><?php echo e($proposals->total()); ?> Proposals</span>
                <a href="<?php echo e(route('jobs.show', $job->slug)); ?>" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="fa fa-eye me-1"></i>View Job
                </a>
            </div>
        </div>
    </div>
</div>


<ul class="nav nav-tabs mb-3">
    <?php
        $filter = request('filter', 'all');
        $filterCounts = [
            'all' => $job->proposals()->count(),
            'pending' => $job->proposals()->where('status', 'pending')->count(),
            'shortlisted' => $job->proposals()->where('is_shortlisted', true)->count(),
            'awarded' => $job->proposals()->where('status', 'awarded')->count(),
        ];
    ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e($filter === 'all' ? 'active' : ''); ?>" href="?filter=all">
            All <span class="badge bg-secondary ms-1"><?php echo e($filterCounts['all']); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e($filter === 'pending' ? 'active' : ''); ?>" href="?filter=pending">
            Pending <span class="badge bg-warning text-dark ms-1"><?php echo e($filterCounts['pending']); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e($filter === 'shortlisted' ? 'active' : ''); ?>" href="?filter=shortlisted">
            <i class="fa fa-bookmark me-1"></i>Shortlisted <span class="badge bg-warning ms-1"><?php echo e($filterCounts['shortlisted']); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e($filter === 'awarded' ? 'active' : ''); ?>" href="?filter=awarded">
            <i class="fa fa-trophy me-1"></i>Awarded <span class="badge bg-success ms-1"><?php echo e($filterCounts['awarded']); ?></span>
        </a>
    </li>
</ul>

<?php $__empty_1 = true; $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="card mb-3 shadow-sm <?php echo e($proposal->is_shortlisted ? 'border-warning border-2' : ''); ?>">
    <div class="card-body">
        <div class="row align-items-start g-3">
            
            <div class="col-md-2 text-center">
                <img src="<?php echo e($proposal->freelancer->avatar_url); ?>" class="rounded-circle mb-2" width="80" height="80" style="object-fit: cover;">
                <div>
                    <a href="<?php echo e(route('profile.show', $proposal->freelancer)); ?>" class="text-decoration-none fw-semibold">
                        <?php echo e($proposal->freelancer->name); ?>

                    </a>
                    <?php if($proposal->is_shortlisted): ?>
                    <div class="mt-1">
                        <span class="badge bg-warning text-dark">
                            <i class="fa fa-bookmark"></i> Shortlisted
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="col-md-6">
                <div class="mb-2">
                    <span class="text-muted small me-3">
                        <i class="fa fa-star text-warning me-1"></i>
                        <?php echo e(number_format($proposal->freelancer->profile?->rating ?? 0, 1)); ?> Rating
                    </span>
                    <span class="text-muted small me-3">
                        <i class="fa fa-briefcase me-1"></i>
                        <?php echo e($proposal->freelancer->profile?->experience_years ?? 0); ?> years exp
                    </span>
                    <span class="text-muted small">
                        <i class="fa fa-map-marker-alt me-1"></i>
                        <?php echo e($proposal->freelancer->profile?->dzongkhag ?? 'Bhutan'); ?>

                    </span>
                </div>

                <p class="text-muted small mb-2">
                    <strong>Cover Letter:</strong><br>
                    <?php echo e(Str::limit($proposal->cover_letter, 200)); ?>

                </p>

                <?php if($proposal->milestones->isNotEmpty()): ?>
                <div class="border-start border-3 border-primary ps-2 mb-2">
                    <small class="text-muted fw-semibold d-block mb-1">
                        <i class="fa fa-tasks me-1"></i>Proposed Milestones (<?php echo e($proposal->milestones->count()); ?>):
                    </small>
                    <?php $__currentLoopData = $proposal->milestones->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <small class="text-muted d-block">
                        • <?php echo e($ms->title); ?> - Nu. <?php echo e(number_format($ms->amount)); ?> (<?php echo e($ms->days); ?> days)
                    </small>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($proposal->milestones->count() > 2): ?>
                    <small class="text-primary">+<?php echo e($proposal->milestones->count() - 2); ?> more</small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="text-muted small">
                    <i class="fa fa-clock me-1"></i>Submitted <?php echo e($proposal->created_at->diffForHumans()); ?>

                </div>
            </div>

            
            <div class="col-md-4">
                <div class="text-md-end">
                    <div class="mb-3">
                        <small class="text-muted d-block">Bid Amount</small>
                        <h4 class="fw-bold text-primary mb-0">Nu. <?php echo e(number_format($proposal->bid_amount)); ?></h4>
                        <?php if($proposal->delivery_days): ?>
                        <small class="text-muted">
                            <i class="fa fa-calendar-check me-1"></i><?php echo e($proposal->delivery_days); ?> days delivery
                        </small>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <a href="<?php echo e(route('proposals.show', $proposal)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye me-1"></i>View Full Proposal
                        </a>
                        
                        <?php if($proposal->status === 'pending'): ?>
                        <form method="POST" action="<?php echo e(route('proposals.award', $proposal)); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-success w-100" onclick="return confirm('Award this project to <?php echo e($proposal->freelancer->name); ?>?')">
                                <i class="fa fa-trophy me-1"></i>Award Project
                            </button>
                        </form>
                        <form method="POST" action="<?php echo e(route('proposals.shortlist', $proposal)); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-outline-warning w-100">
                                <i class="fa fa-bookmark me-1"></i><?php echo e($proposal->is_shortlisted ? 'Remove Shortlist' : 'Shortlist'); ?>

                            </button>
                        </form>
                        <form method="POST" action="<?php echo e(route('proposals.reject', $proposal)); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="reason" value="Not selected">
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Reject this proposal?')">
                                <i class="fa fa-times me-1"></i>Reject
                            </button>
                        </form>
                        <?php else: ?>
                        <span class="badge bg-<?php echo e($proposal->status === 'awarded' ? 'success' : 'secondary'); ?> w-100">
                            <?php echo e(ucfirst($proposal->status)); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="card text-center py-5 shadow-sm">
    <div class="card-body">
        <i class="fa fa-inbox fa-4x text-muted mb-3" style="opacity: 0.3"></i>
        <h6 class="text-muted mb-2">
            <?php if($filter === 'all'): ?>
                No proposals received yet for this job.
            <?php elseif($filter === 'pending'): ?>
                No pending proposals.
            <?php elseif($filter === 'shortlisted'): ?>
                No shortlisted proposals.
            <?php elseif($filter === 'awarded'): ?>
                No awarded proposals.
            <?php endif; ?>
        </h6>
        <p class="text-muted small">
            <?php if($filter === 'all'): ?>
                Freelancers will submit proposals soon. Check back later!
            <?php endif; ?>
        </p>
    </div>
</div>
<?php endif; ?>

<?php if($proposals->hasPages()): ?>
<div class="mt-3">
    <?php echo e($proposals->links()); ?>

</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/proposals/index.blade.php ENDPATH**/ ?>