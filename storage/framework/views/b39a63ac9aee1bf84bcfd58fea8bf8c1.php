<?php $__env->startSection('title', 'Proposal from ' . $proposal->freelancer->name); ?>
<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none">Dashboard</a></li>
        <?php if(auth()->user()->id === $proposal->freelancer_id): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('proposals.my')); ?>" class="text-decoration-none">My Proposals</a></li>
        <?php else: ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('jobs.my')); ?>" class="text-decoration-none">My Jobs</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('jobs.proposals', $proposal->job)); ?>" class="text-decoration-none">Proposals</a></li>
        <?php endif; ?>
        <li class="breadcrumb-item active" aria-current="page">Proposal Details</li>
    </ol>
</nav>

<div class="row g-3">
    <div class="col-lg-8">
        
        <div class="card mb-3 shadow-sm">
            <div class="card-body p-4" style="border-bottom: 3px solid var(--bs-primary);">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mb-1">PROJECT PROPOSAL</h3>
                    <p class="text-muted mb-0">For: <?php echo e($proposal->job->title); ?></p>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <p class="text-muted small mb-2"><strong>SUBMITTED BY (Freelancer)</strong></p>
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?php echo e($proposal->freelancer->avatar_url); ?>" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                <div>
                                    <p class="mb-0 fw-semibold"><?php echo e($proposal->freelancer->name); ?></p>
                                    <p class="mb-0 text-muted small"><?php echo e($proposal->freelancer->email); ?></p>
                                    <p class="mb-0 text-muted small">
                                        <?php echo e($proposal->freelancer->profile?->headline ?? 'Freelancer'); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <p class="text-muted small mb-2"><strong>SUBMITTED TO (Client)</strong></p>
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?php echo e($proposal->job->poster->avatar_url); ?>" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                <div>
                                    <p class="mb-0 fw-semibold"><?php echo e($proposal->job->poster->name); ?></p>
                                    <p class="mb-0 text-muted small"><?php echo e($proposal->job->poster->email); ?></p>
                                    <p class="mb-0 text-muted small">Job Poster</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-light border">
                    <div class="row g-3 text-center">
                        <div class="col-md-3">
                            <small class="text-muted d-block">Proposal Status</small>
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
                            <span class="badge bg-<?php echo e($statusConfig['class']); ?> mt-1">
                                <i class="fa fa-<?php echo e($statusConfig['icon']); ?> me-1"></i><?php echo e(ucfirst($proposal->status)); ?>

                            </span>
                            <?php if($proposal->is_shortlisted): ?>
                            <div class="mt-2">
                                <span class="badge bg-warning text-dark">
                                    <i class="fa fa-bookmark me-1"></i>Shortlisted
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Bid Amount</small>
                            <strong class="text-primary d-block mt-1 h5 mb-0">Nu. <?php echo e(number_format($proposal->bid_amount)); ?></strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Delivery Time</small>
                            <strong class="d-block mt-1"><?php echo e($proposal->delivery_days ?? 'Flexible'); ?> <?php echo e($proposal->delivery_days ? 'days' : ''); ?></strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Submitted On</small>
                            <strong class="d-block mt-1"><?php echo e($proposal->created_at->format('d M Y')); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-file-alt me-2"></i>Cover Letter</h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0" style="white-space: pre-line;"><?php echo e($proposal->cover_letter); ?></p>
            </div>
        </div>

        
        <?php if($proposal->milestones->isNotEmpty()): ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-tasks me-2"></i>Proposed Project Milestones</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40">#</th>
                                <th>Milestone Title</th>
                                <th>Description</th>
                                <th width="120">Amount</th>
                                <th width="100">Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $proposal->milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center">
                                    <span class="badge bg-primary"><?php echo e($index + 1); ?></span>
                                </td>
                                <td><strong><?php echo e($ms->title); ?></strong></td>
                                <td class="text-muted small"><?php echo e($ms->description ?? '-'); ?></td>
                                <td><strong class="text-primary">Nu. <?php echo e(number_format($ms->amount)); ?></strong></td>
                                <td class="text-muted small"><?php echo e($ms->days); ?> days</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total Project Value:</strong></td>
                                <td><strong class="text-primary">Nu. <?php echo e(number_format($proposal->milestones->sum('amount'))); ?></strong></td>
                                <td><strong><?php echo e($proposal->milestones->sum('days')); ?> days</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-briefcase me-2"></i>Project Details</h6>
            </div>
            <div class="card-body">
                <h6 class="fw-semibold mb-2"><?php echo e($proposal->job->title); ?></h6>
                <?php if($proposal->job->description): ?>
                <p class="text-muted small mb-3"><?php echo nl2br(e($proposal->job->description)); ?></p>
                <?php endif; ?>
                <div class="row g-2">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Budget Range</small>
                        <strong>Nu. <?php echo e(number_format($proposal->job->budget_min)); ?> - Nu. <?php echo e(number_format($proposal->job->budget_max)); ?></strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Category</small>
                        <strong><?php echo e($proposal->job->category->name ?? 'Other'); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-cog me-2"></i>Actions</h6>
            </div>
            <div class="card-body">
                <?php if(auth()->user()->id === $proposal->job->poster_id && $proposal->status === 'pending'): ?>
                <form method="POST" action="<?php echo e(route('proposals.award', $proposal)); ?>" class="mb-2">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Award this project to <?php echo e($proposal->freelancer->name); ?>?')">
                        <i class="fa fa-trophy me-1"></i> Award Project
                    </button>
                </form>
                <form method="POST" action="<?php echo e(route('proposals.shortlist', $proposal)); ?>" class="mb-2">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-warning w-100">
                        <i class="fa fa-bookmark me-1"></i> <?php echo e($proposal->is_shortlisted ? 'Remove from Shortlist' : 'Add to Shortlist'); ?>

                    </button>
                </form>
                <form method="POST" action="<?php echo e(route('proposals.reject', $proposal)); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="reason" value="Not selected">
                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Reject this proposal?')">
                        <i class="fa fa-times me-1"></i> Reject Proposal
                    </button>
                </form>
                <?php endif; ?>

                <?php if(auth()->user()->id === $proposal->freelancer_id && $proposal->status === 'pending'): ?>
                <form method="POST" action="<?php echo e(route('proposals.withdraw', $proposal)); ?>" onsubmit="return confirm('Withdraw this proposal? This cannot be undone.')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fa fa-times-circle me-1"></i> Withdraw Proposal
                    </button>
                </form>
                <?php endif; ?>

                <?php if($proposal->status === 'awarded'): ?>
                <div class="alert alert-success mb-2">
                    <i class="fa fa-trophy me-1"></i> This proposal has been awarded!
                </div>
                <a href="<?php echo e(route('contracts.index')); ?>" class="btn btn-primary w-100">
                    <i class="fa fa-file-contract me-1"></i> View Contract
                </a>
                <?php endif; ?>

                <?php if($proposal->status === 'rejected' && $proposal->rejection_reason): ?>
                <div class="alert alert-danger small mb-0">
                    <strong>Rejection Reason:</strong><br>
                    <?php echo e($proposal->rejection_reason); ?>

                </div>
                <?php endif; ?>

                <hr class="my-3">

                <a href="<?php echo e(route('messages.start')); ?>" class="btn btn-outline-secondary btn-sm w-100"
                   onclick="event.preventDefault(); document.getElementById('msg-form').submit();">
                    <i class="fa fa-comments me-1"></i> Message <?php echo e(auth()->user()->id === $proposal->freelancer_id ? 'Client' : 'Freelancer'); ?>

                </a>
                <form id="msg-form" method="POST" action="<?php echo e(route('messages.start')); ?>" class="d-none">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="recipient_id" value="<?php echo e(auth()->user()->id === $proposal->freelancer_id ? $proposal->job->poster_id : $proposal->freelancer_id); ?>">
                    <input type="hidden" name="job_id" value="<?php echo e($proposal->job_id); ?>">
                </form>
            </div>
        </div>

        
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-user me-2"></i>Freelancer Profile</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="<?php echo e($proposal->freelancer->avatar_url); ?>" class="rounded-circle mb-2" width="80" height="80" style="object-fit: cover;">
                    <h6 class="fw-semibold mb-0"><?php echo e($proposal->freelancer->name); ?></h6>
                    <p class="text-muted small mb-0"><?php echo e($proposal->freelancer->profile?->headline ?? 'Freelancer'); ?></p>
                </div>

                <div class="small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Rating:</span>
                        <span>
                            <i class="fa fa-star text-warning"></i>
                            <?php echo e(number_format($proposal->freelancer->profile?->rating ?? 0, 1)); ?>

                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Experience:</span>
                        <span><?php echo e($proposal->freelancer->profile?->experience_years ?? 0); ?> years</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Location:</span>
                        <span><?php echo e($proposal->freelancer->profile?->dzongkhag ?? 'Bhutan'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Projects:</span>
                        <span><?php echo e($proposal->freelancer->profile?->completed_jobs ?? 0); ?> completed</span>
                    </div>

                    <a href="<?php echo e(route('profile.show', $proposal->freelancer)); ?>" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fa fa-eye me-1"></i>View Full Profile
                    </a>
                </div>
            </div>
        </div>

        
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-info-circle me-2"></i>Proposal Information</h6>
            </div>
            <div class="card-body small">
                <div class="mb-2">
                    <strong>Submitted:</strong><br>
                    <span class="text-muted"><?php echo e($proposal->created_at->format('d M Y, h:i A')); ?></span>
                </div>
                <div class="mb-2">
                    <strong>Last Updated:</strong><br>
                    <span class="text-muted"><?php echo e($proposal->updated_at->diffForHumans()); ?></span>
                </div>
                <?php if($proposal->awarded_at): ?>
                <div class="mb-2">
                    <strong>Awarded On:</strong><br>
                    <span class="text-muted"><?php echo e($proposal->awarded_at->format('d M Y, h:i A')); ?></span>
                </div>
                <?php endif; ?>
                <div class="mb-0">
                    <strong>Related Job:</strong><br>
                    <a href="<?php echo e(route('jobs.show', $proposal->job->slug)); ?>" class="text-decoration-none">View Job Posting</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/proposals/show.blade.php ENDPATH**/ ?>