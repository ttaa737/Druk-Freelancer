<?php $__env->startSection('title', 'My Proposals'); ?>
<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">My Proposals</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fa fa-paper-plane me-2"></i>My Proposals</h5>
    <a href="<?php echo e(route('jobs.index')); ?>" class="btn btn-primary btn-sm">
        <i class="fa fa-search me-1"></i>Browse Jobs
    </a>
</div>


<ul class="nav nav-tabs mb-3">
    <?php
        $filter = request('filter', 'all');
        $statusCounts = [
            'all' => $proposals->total(),
            'pending' => \App\Models\Proposal::where('freelancer_id', auth()->id())->where('status', 'pending')->count(),
            'shortlisted' => \App\Models\Proposal::where('freelancer_id', auth()->id())->where('is_shortlisted', true)->count(),
            'awarded' => \App\Models\Proposal::where('freelancer_id', auth()->id())->where('status', 'awarded')->count(),
            'rejected' => \App\Models\Proposal::where('freelancer_id', auth()->id())->where('status', 'rejected')->count(),
        ];
    ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e($filter === 'all' ? 'active' : ''); ?>" href="?filter=all">
            All <span class="badge bg-secondary ms-1"><?php echo e($statusCounts['all']); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e($filter === 'pending' ? 'active' : ''); ?>" href="?filter=pending">
            Pending <span class="badge bg-warning text-dark ms-1"><?php echo e($statusCounts['pending']); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e($filter === 'shortlisted' ? 'active' : ''); ?>" href="?filter=shortlisted">
            <i class="fa fa-bookmark me-1"></i>Shortlisted <span class="badge bg-warning ms-1"><?php echo e($statusCounts['shortlisted']); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e($filter === 'awarded' ? 'active' : ''); ?>" href="?filter=awarded">
            <i class="fa fa-trophy me-1"></i>Awarded <span class="badge bg-success ms-1"><?php echo e($statusCounts['awarded']); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e($filter === 'rejected' ? 'active' : ''); ?>" href="?filter=rejected">
            Rejected <span class="badge bg-danger ms-1"><?php echo e($statusCounts['rejected']); ?></span>
        </a>
    </li>
</ul>

<?php $__empty_1 = true; $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="card mb-3 shadow-sm <?php echo e($proposal->is_shortlisted ? 'border-warning' : ''); ?>">
    <div class="card-body">
        <div class="row align-items-start g-3">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-1">
                        <a href="<?php echo e(route('jobs.show', $proposal->job->slug)); ?>" class="text-decoration-none"><?php echo e($proposal->job->title); ?></a>
                    </h6>
                    <?php
                        if ($proposal->status === 'pending') {
                            $statusConfig = ['class' => 'warning text-dark', 'icon' => 'clock'];
                        } elseif ($proposal->status === 'awarded') {
                            $statusConfig = ['class' => 'success', 'icon' => 'trophy'];
                        } elseif ($proposal->status === 'rejected') {
                            $statusConfig = ['class' => 'danger', 'icon' => 'times-circle'];
                        } elseif ($proposal->status === 'withdrawn') {
                            $statusConfig = ['class' => 'secondary', 'icon' => 'undo'];
                        } else {
                            $statusConfig = ['class' => 'secondary', 'icon' => 'circle'];
                        }
                    ?>
                    <span class="badge bg-<?php echo e($statusConfig['class']); ?>">
                        <i class="fa fa-<?php echo e($statusConfig['icon']); ?> me-1"></i><?php echo e(ucfirst($proposal->status)); ?>

                    </span>
                </div>

                <div class="mb-2">
                    <span class="text-muted small me-3">
                        <i class="fa fa-user me-1"></i><?php echo e($proposal->job->poster->name); ?>

                    </span>
                    <span class="text-muted small me-3">
                        <i class="fa fa-calendar me-1"></i>Submitted <?php echo e($proposal->created_at->diffForHumans()); ?>

                    </span>
                    <?php if($proposal->delivery_days): ?>
                    <span class="text-muted small me-3">
                        <i class="fa fa-clock me-1"></i><?php echo e($proposal->delivery_days); ?> days delivery
                    </span>
                    <?php endif; ?>
                    <?php if($proposal->is_shortlisted): ?>
                    <span class="badge bg-warning text-dark">
                        <i class="fa fa-bookmark me-1"></i>Shortlisted
                    </span>
                    <?php endif; ?>
                </div>

                <p class="text-muted small mb-2"><?php echo e(Str::limit($proposal->cover_letter, 180)); ?></p>

                <?php if($proposal->milestones->isNotEmpty()): ?>
                <div class="border-start border-3 border-primary ps-2 mt-2">
                    <small class="text-muted fw-semibold d-block mb-1">Proposed Milestones:</small>
                    <?php $__currentLoopData = $proposal->milestones->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <small class="text-muted d-block">• <?php echo e($ms->title); ?> - Nu. <?php echo e(number_format($ms->amount)); ?></small>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($proposal->milestones->count() > 2): ?>
                    <small class="text-primary">+<?php echo e($proposal->milestones->count() - 2); ?> more milestones</small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <div class="text-lg-end">
                    <div class="mb-2">
                        <small class="text-muted d-block">Your Bid Amount</small>
                        <h5 class="fw-bold text-primary mb-0">Nu. <?php echo e(number_format($proposal->bid_amount)); ?></h5>
                    </div>

                    <div class="d-flex gap-2 justify-content-lg-end mt-3">
                        <a href="<?php echo e(route('proposals.show', $proposal)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye me-1"></i>View Details
                        </a>
                        <?php if($proposal->status === 'pending'): ?>
                        <form method="POST" action="<?php echo e(route('proposals.withdraw', $proposal)); ?>" class="d-inline" onsubmit="return confirm('Withdraw this proposal? This cannot be undone.')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fa fa-times me-1"></i>Withdraw
                            </button>
                        </form>
                        <?php endif; ?>
                        <?php if($proposal->status === 'awarded'): ?>
                        <a href="<?php echo e(route('contracts.index')); ?>" class="btn btn-sm btn-success">
                            <i class="fa fa-file-contract me-1"></i>View Contract
                        </a>
                        <?php endif; ?>
                    </div>

                    <?php if($proposal->rejection_reason && $proposal->status === 'rejected'): ?>
                    <div class="alert alert-danger alert-sm mt-2 mb-0 text-start">
                        <small><strong>Reason:</strong> <?php echo e($proposal->rejection_reason); ?></small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="card text-center py-5 shadow-sm">
    <div class="card-body">
        <i class="fa fa-paper-plane fa-4x text-muted mb-3" style="opacity: 0.3"></i>
        <h6 class="text-muted mb-2">
            <?php if($filter === 'all'): ?>
                You haven't submitted any proposals yet.
            <?php elseif($filter === 'pending'): ?>
                No pending proposals.
            <?php elseif($filter === 'shortlisted'): ?>
                No shortlisted proposals.
            <?php elseif($filter === 'awarded'): ?>
                No awarded proposals yet.
            <?php elseif($filter === 'rejected'): ?>
                No rejected proposals.
            <?php endif; ?>
        </h6>
        <p class="text-muted small mb-3">Start browsing jobs and submit proposals to win projects!</p>
        <a href="<?php echo e(route('jobs.index')); ?>" class="btn btn-primary">
            <i class="fa fa-search me-1"></i>Browse Available Jobs
        </a>
    </div>
</div>
<?php endif; ?>

<?php if($proposals->hasPages()): ?>
<div class="mt-3">
    <?php echo e($proposals->links()); ?>

</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/proposals/my-proposals.blade.php ENDPATH**/ ?>