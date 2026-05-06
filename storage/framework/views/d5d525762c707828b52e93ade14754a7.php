<?php $__env->startSection('title', 'Contract ' . $contract->contract_number); ?>
<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('contracts.index')); ?>" class="text-decoration-none">Contracts</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo e($contract->contract_number); ?></li>
    </ol>
</nav>

<div class="row g-3">
    <div class="col-lg-8">
        
        <div class="card mb-3 shadow-sm">
            <div class="card-body p-4" style="border-bottom: 3px solid var(--bs-primary);">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mb-1">FREELANCE SERVICE CONTRACT</h3>
                    <p class="text-muted mb-0">Contract No: #<?php echo e($contract->contract_number); ?></p>
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <p class="text-muted small mb-2"><strong>CLIENT (Job Poster)</strong></p>
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?php echo e($contract->poster->avatar_url); ?>" class="rounded-circle" width="40" height="40">
                                <div>
                                    <p class="mb-0 fw-semibold"><?php echo e($contract->poster->name); ?></p>
                                    <p class="mb-0 text-muted small"><?php echo e($contract->poster->email); ?></p>
                                </div>
                            </div>
                            <?php if($contract->poster_signed): ?>
                            <div class="mt-2 text-success small">
                                <i class="fa fa-check-circle me-1"></i>Signed on <?php echo e($contract->updated_at->format('d M Y, h:i A')); ?>

                            </div>
                            <?php else: ?>
                            <div class="mt-2 text-warning small">
                                <i class="fa fa-clock me-1"></i>Signature Pending
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <p class="text-muted small mb-2"><strong>FREELANCER (Service Provider)</strong></p>
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?php echo e($contract->freelancer->avatar_url); ?>" class="rounded-circle" width="40" height="40">
                                <div>
                                    <p class="mb-0 fw-semibold"><?php echo e($contract->freelancer->name); ?></p>
                                    <p class="mb-0 text-muted small"><?php echo e($contract->freelancer->email); ?></p>
                                </div>
                            </div>
                            <?php if($contract->freelancer_signed): ?>
                            <div class="mt-2 text-success small">
                                <i class="fa fa-check-circle me-1"></i>Signed on <?php echo e($contract->updated_at->format('d M Y, h:i A')); ?>

                            </div>
                            <?php else: ?>
                            <div class="mt-2 text-warning small">
                                <i class="fa fa-clock me-1"></i>Signature Pending
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="alert alert-light border">
                    <div class="row g-3 text-center">
                        <div class="col-md-3">
                            <small class="text-muted d-block">Contract Status</small>
                            <?php
                                if ($contract->status === 'pending') {
                                    $statusConfig = ['class' => 'warning text-dark', 'icon' => 'clock'];
                                } elseif ($contract->status === 'active') {
                                    $statusConfig = ['class' => 'success', 'icon' => 'play-circle'];
                                } elseif ($contract->status === 'completed') {
                                    $statusConfig = ['class' => 'primary', 'icon' => 'check-circle'];
                                } elseif ($contract->status === 'disputed') {
                                    $statusConfig = ['class' => 'danger', 'icon' => 'exclamation-triangle'];
                                } elseif ($contract->status === 'cancelled') {
                                    $statusConfig = ['class' => 'secondary', 'icon' => 'times-circle'];
                                } else {
                                    $statusConfig = ['class' => 'secondary', 'icon' => 'circle'];
                                }
                            ?>
                            <span class="badge bg-<?php echo e($statusConfig['class']); ?> mt-1">
                                <i class="fa fa-<?php echo e($statusConfig['icon']); ?> me-1"></i><?php echo e(ucfirst($contract->status)); ?>

                            </span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Total Contract Value</small>
                            <strong class="text-primary d-block mt-1">Nu. <?php echo e(number_format($contract->total_amount)); ?></strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Platform Fee (10%)</small>
                            <strong class="d-block mt-1">Nu. <?php echo e(number_format($contract->platform_fee)); ?></strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Freelancer Receives</small>
                            <strong class="text-success d-block mt-1">Nu. <?php echo e(number_format($contract->freelancer_amount)); ?></strong>
                        </div>
                    </div>
                </div>

                <div class="row g-3 text-center small">
                    <div class="col-md-4">
                        <i class="fa fa-calendar text-primary me-1"></i>
                        <strong>Start Date:</strong> <?php echo e($contract->start_date?->format('d M Y') ?? 'TBD'); ?>

                    </div>
                    <div class="col-md-4">
                        <i class="fa fa-flag-checkered text-primary me-1"></i>
                        <strong>Deadline:</strong> <?php echo e($contract->deadline?->format('d M Y') ?? 'Flexible'); ?>

                    </div>
                    <div class="col-md-4">
                        <i class="fa fa-tasks text-primary me-1"></i>
                        <strong>Milestones:</strong> <?php echo e($contract->milestones->count()); ?>

                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-file-alt me-2"></i>Scope of Work</h6>
            </div>
            <div class="card-body">
                <h6 class="fw-semibold mb-2"><?php echo e($contract->job?->title); ?></h6>
                <?php if($contract->job?->description): ?>
                <p class="text-muted mb-3"><?php echo nl2br(e($contract->job->description)); ?></p>
                <?php endif; ?>
                
                <?php if($contract->terms): ?>
                <hr>
                <h6 class="fw-semibold mb-2">Special Terms & Conditions</h6>
                <p class="text-muted small mb-0"><?php echo nl2br(e($contract->terms)); ?></p>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-tasks me-2"></i>Project Milestones</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40">#</th>
                                <th>Milestone Description</th>
                                <th width="120">Amount</th>
                                <th width="120">Due Date</th>
                                <th width="120">Status</th>
                                <th width="140">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $contract->milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center">
                                    <?php if($ms->status === 'paid'): ?>
                                        <i class="fa fa-check-circle fa-lg text-success"></i>
                                    <?php elseif($ms->status === 'approved'): ?>
                                        <i class="fa fa-check fa-lg text-primary"></i>
                                    <?php elseif($ms->status === 'submitted'): ?>
                                        <i class="fa fa-clock fa-lg text-warning"></i>
                                    <?php elseif($ms->status === 'disputed'): ?>
                                        <i class="fa fa-exclamation-circle fa-lg text-danger"></i>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e($index + 1); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo e($ms->title); ?></strong>
                                    <?php if($ms->description): ?>
                                    <p class="text-muted small mb-0"><?php echo e($ms->description); ?></p>
                                    <?php endif; ?>
                                    <?php if($ms->escrow_held): ?>
                                    <span class="badge bg-success-subtle text-success small mt-1">
                                        <i class="fa fa-lock me-1"></i>Funds in Escrow
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td><strong class="text-primary">Nu. <?php echo e(number_format($ms->amount)); ?></strong></td>
                                <td class="small"><?php echo e($ms->due_date?->format('d M Y') ?? 'Flexible'); ?></td>
                                <td>
                                    <?php
                                        if ($ms->status === 'paid') {
                                            $msStatus = ['class' => 'success', 'label' => 'Paid'];
                                        } elseif ($ms->status === 'approved') {
                                            $msStatus = ['class' => 'primary', 'label' => 'Approved'];
                                        } elseif ($ms->status === 'submitted') {
                                            $msStatus = ['class' => 'warning text-dark', 'label' => 'Review'];
                                        } elseif ($ms->status === 'disputed') {
                                            $msStatus = ['class' => 'danger', 'label' => 'Disputed'];
                                        } elseif ($ms->status === 'in_progress') {
                                            $msStatus = ['class' => 'info', 'label' => 'In Progress'];
                                        } else {
                                            $msStatus = ['class' => 'secondary', 'label' => 'Pending'];
                                        }
                                    ?>
                                    <span class="badge bg-<?php echo e($msStatus['class']); ?>"><?php echo e($msStatus['label']); ?></span>
                                </td>
                                <td>
                                    <?php if(auth()->user()->id === $contract->freelancer_id && $ms->status === 'pending'): ?>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#submitMilestone<?php echo e($ms->id); ?>">
                                        <i class="fa fa-upload me-1"></i>Submit
                                    </button>
                                    <?php endif; ?>
                                    <?php if(auth()->user()->id === $contract->poster_id && $ms->status === 'submitted'): ?>
                                    <div class="btn-group btn-group-sm">
                                        <form method="POST" action="<?php echo e(route('milestones.approve', $ms)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Release payment for this milestone?')">
                                                <i class="fa fa-check me-1"></i>Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('milestones.revision', $ms)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="fa fa-redo me-1"></i>Revision
                                            </button>
                                        </form>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($ms->status === 'paid'): ?>
                                    <small class="text-success"><i class="fa fa-check-double me-1"></i>Complete</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                <td><strong class="text-primary">Nu. <?php echo e(number_format($contract->milestones->sum('amount'))); ?></strong></td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-money-bill-wave me-2"></i>Payment Terms</h6>
            </div>
            <div class="card-body">
                <ul class="mb-0 small">
                    <li>All payments shall be held in escrow until milestone approval</li>
                    <li>Platform fee of 10% is deducted from total contract value</li>
                    <li>Freelancer receives <?php echo e(number_format(($contract->freelancer_amount / $contract->total_amount) * 100, 1)); ?>% of contract value after fees</li>
                    <li>Payment released within 24-48 hours of milestone approval</li>
                    <li>Refund policy applies as per platform terms for cancelled contracts</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-cog me-2"></i>Contract Actions</h6>
            </div>
            <div class="card-body">
                <?php if($contract->status === 'pending' && auth()->user()->id === $contract->poster_id): ?>
                <div class="alert alert-warning small mb-3">
                    <i class="fa fa-info-circle me-1"></i>
                    Please fund escrow to activate this contract
                </div>
                <form method="POST" action="<?php echo e(route('contracts.fund', $contract)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success w-100 mb-2" onclick="return confirm('Fund escrow with Nu. <?php echo e(number_format($contract->total_amount)); ?>?')">
                        <i class="fa fa-lock me-1"></i> Fund Escrow (Nu. <?php echo e(number_format($contract->total_amount)); ?>)
                    </button>
                </form>
                <?php endif; ?>

                <?php if($contract->status === 'pending'): ?>
                <form method="POST" action="<?php echo e(route('contracts.sign', $contract)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary w-100 mb-2" <?php echo e((auth()->user()->id === $contract->poster_id && $contract->poster_signed) || (auth()->user()->id === $contract->freelancer_id && $contract->freelancer_signed) ? 'disabled' : ''); ?>>
                        <i class="fa fa-signature me-1"></i>
                        <?php if(auth()->user()->id === $contract->poster_id): ?>
                            <?php echo e($contract->poster_signed ? 'Contract Signed ✓' : 'Sign Contract'); ?>

                        <?php else: ?>
                            <?php echo e($contract->freelancer_signed ? 'Contract Signed ✓' : 'Sign Contract'); ?>

                        <?php endif; ?>
                    </button>
                </form>
                <?php endif; ?>

                <?php if(in_array($contract->status, ['pending', 'active'])): ?>
                <hr>
                <form method="POST" action="<?php echo e(route('contracts.cancel', $contract)); ?>" onsubmit="return confirm('Cancel this contract? Escrow will be refunded.')">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-danger w-100 mb-2">
                        <i class="fa fa-times-circle me-1"></i>Cancel Contract
                    </button>
                </form>
                <?php endif; ?>

                <?php if($contract->status === 'active'): ?>
                <hr>
                <a href="<?php echo e(route('disputes.create', $contract)); ?>" class="btn btn-outline-warning w-100 mb-2">
                    <i class="fa fa-gavel me-1"></i> Raise Dispute
                </a>
                <?php endif; ?>

                <?php if($contract->status === 'completed' && !$contract->reviews()->where('reviewer_id', auth()->id())->exists()): ?>
                <hr>
                <a href="<?php echo e(route('reviews.create', $contract)); ?>" class="btn btn-success w-100">
                    <i class="fa fa-star me-1"></i> Leave Review
                </a>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($contract->status !== 'cancelled'): ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-shield-alt me-2"></i>Escrow Status</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Progress</small>
                        <small class="text-muted"><?php echo e($contract->milestones->where('status', 'paid')->count()); ?>/<?php echo e($contract->milestones->count()); ?> Milestones</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: <?php echo e($contract->milestones->count() > 0 ? ($contract->milestones->where('status', 'paid')->count() / $contract->milestones->count()) * 100 : 0); ?>%"></div>
                    </div>
                </div>
                <div class="small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Contract:</span>
                        <strong>Nu. <?php echo e(number_format($contract->total_amount)); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Released:</span>
                        <strong class="text-success">Nu. <?php echo e(number_format($contract->milestones->where('status', 'paid')->sum('amount'))); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">In Escrow:</span>
                        <strong class="text-warning">Nu. <?php echo e(number_format($contract->milestones->where('escrow_held', true)->sum('amount'))); ?></strong>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-comments me-2"></i>Communication</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Stay in touch with your <?php echo e(auth()->user()->id === $contract->poster_id ? 'freelancer' : 'client'); ?></p>
                <a href="<?php echo e(route('messages.start')); ?>" class="btn btn-outline-primary btn-sm w-100"
                   onclick="event.preventDefault(); document.getElementById('contract-msg-form').submit();">
                    <i class="fa fa-envelope me-1"></i>Send Message
                </a>
                <form id="contract-msg-form" method="POST" action="<?php echo e(route('messages.start')); ?>" class="d-none">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="recipient_id" value="<?php echo e(auth()->user()->id === $contract->poster_id ? $contract->freelancer_id : $contract->poster_id); ?>">
                    <input type="hidden" name="job_id" value="<?php echo e($contract->job_id); ?>">
                </form>
            </div>
        </div>

        
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-info-circle me-2"></i>Contract Information</h6>
            </div>
            <div class="card-body small">
                <div class="mb-2">
                    <strong>Contract ID:</strong><br>
                    <span class="text-muted"><?php echo e($contract->contract_number); ?></span>
                </div>
                <div class="mb-2">
                    <strong>Created:</strong><br>
                    <span class="text-muted"><?php echo e($contract->created_at->format('d M Y, h:i A')); ?></span>
                </div>
                <?php if($contract->start_date): ?>
                <div class="mb-2">
                    <strong>Started:</strong><br>
                    <span class="text-muted"><?php echo e($contract->start_date->format('d M Y')); ?></span>
                </div>
                <?php endif; ?>
                <?php if($contract->completed_at): ?>
                <div class="mb-2">
                    <strong>Completed:</strong><br>
                    <span class="text-muted"><?php echo e($contract->completed_at->format('d M Y, h:i A')); ?></span>
                </div>
                <?php endif; ?>
                <?php if($contract->job): ?>
                <div class="mb-0">
                    <strong>Related Job:</strong><br>
                    <a href="<?php echo e(route('jobs.show',$contract->job->slug)); ?>" class="text-decoration-none">View Job Posting</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\contracts\show.blade.php ENDPATH**/ ?>