<?php $__env->startSection('title', $job->title); ?>
<?php $__env->startSection('content'); ?>

<div class="row g-4">

    
    <div class="col-lg-8">

        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        <?php if($job->is_featured): ?>
                        <span class="badge bg-warning text-dark mb-2"><i class="fa fa-star me-1"></i>Featured Job</span>
                        <?php endif; ?>
                        <h2 class="fw-bold mb-2" style="font-size:28px;"><?php echo e($job->title); ?></h2>
                    </div>
                </div>

                
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-auto">
                        <div class="text-muted small fw-semibold text-uppercase">Category</div>
                        <div class="fw-semibold"><?php echo e($job->category?->name ?? 'Uncategorized'); ?></div>
                    </div>
                    <div class="col-6 col-md-auto">
                        <div class="text-muted small fw-semibold text-uppercase">Type</div>
                        <div class="fw-semibold"><span class="badge bg-primary bg-opacity-20 text-primary"><?php echo e(ucfirst($job->type)); ?></span></div>
                    </div>
                    <div class="col-6 col-md-auto">
                        <div class="text-muted small fw-semibold text-uppercase">Posted</div>
                        <div class="fw-semibold"><?php echo e($job->created_at->diffForHumans()); ?></div>
                    </div>
                    <div class="col-6 col-md-auto">
                        <div class="text-muted small fw-semibold text-uppercase">Views</div>
                        <div class="fw-semibold"><i class="fa fa-eye me-1 text-primary"></i><?php echo e($job->views_count); ?></div>
                    </div>
                    <div class="col-6 col-md-auto">
                        <div class="text-muted small fw-semibold text-uppercase">Proposals</div>
                        <div class="fw-semibold"><i class="fa fa-paper-plane me-1 text-primary"></i><?php echo e($job->proposals_count ?? $job->proposals->count()); ?></div>
                    </div>
                </div>

                <?php if($job->deadline || $job->job_deadline || $job->duration_days): ?>
                <?php
                    $proposalDeadlinePast = $job->deadline ? ($job->deadline->isToday() || $job->deadline->isPast()) : false;
                    $completionDeadline = $job->job_deadline ?: (($job->deadline && $job->duration_days) ? $job->deadline->copy()->addDays((int) $job->duration_days) : null);
                ?>
                <div class="row g-3 mb-0">
                    <?php if($job->deadline): ?>
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100 <?php echo e($proposalDeadlinePast ? 'border-danger bg-danger bg-opacity-10' : 'border-warning bg-warning bg-opacity-10'); ?>">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fa fa-calendar-alt text-<?php echo e($proposalDeadlinePast ? 'danger' : 'warning'); ?>"></i>
                                <span class="fw-semibold">Proposal Deadline</span>
                            </div>
                            <div class="fs-6 fw-bold <?php echo e($proposalDeadlinePast ? 'text-danger' : 'text-dark'); ?>"><?php echo e($job->deadline->format('d/m/Y')); ?></div>
                            <small class="text-muted d-block mt-1">Last date to submit proposals</small>
                            <?php if($proposalDeadlinePast): ?>
                            <small class="text-danger fw-semibold d-block mt-1">This deadline has passed</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($completionDeadline || $job->duration_days): ?>
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100 border-primary bg-primary bg-opacity-10">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fa fa-flag-checkered text-primary"></i>
                                <span class="fw-semibold">Project Deadline</span>
                            </div>
                            <div class="fs-6 fw-bold text-primary">
                                <?php echo e($completionDeadline ? $completionDeadline->format('d/m/Y') : 'within ' . (int) $job->duration_days . ' days'); ?>

                            </div>
                            <small class="text-muted d-block mt-1">Expected job completion date</small>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">


                <div class="mb-4">
                    <h6 class="fw-bold mb-3 pb-2 border-bottom"><i class="fa fa-align-left me-2 text-primary"></i>Project Description</h6>
                    <div class="text-secondary" style="line-height:1.8; font-size:15px;"><?php echo nl2br(e($job->description)); ?></div>
                </div>


                <?php if($job->attachments()->exists()): ?>
                <div class="mb-4">
                    <h6 class="fw-bold mb-3 pb-2 border-bottom"><i class="fa fa-paperclip me-2 text-primary"></i>Attachments</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = $job->attachments()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(Storage::url($attachment->file_path)); ?>" target="_blank" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2">
                            <i class="fa fa-download"></i><?php echo e($attachment->original_name); ?>

                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>


                <?php if($job->skills->isNotEmpty()): ?>
                <div>
                    <h6 class="fw-bold mb-3 pb-2 border-bottom"><i class="fa fa-star me-2 text-primary"></i>Required Skills</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = $job->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2"><?php echo e($skill->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>

        
        <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->id === $job->poster_id): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3 border-bottom">
                <span class="fw-bold fs-5">
                    <i class="fa fa-inbox me-2 text-primary"></i>Received Proposals
                    <span class="badge bg-primary ms-2"><?php echo e($job->proposals_count); ?></span>
                </span>
                <a href="<?php echo e(route('jobs.proposals', $job)); ?>" class="btn btn-primary btn-sm">View All</a>
            </div>
            <div class="card-body p-0">
                <?php $__currentLoopData = $job->proposals()->with('freelancer.profile')->latest()->take(10)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-start gap-3 px-4 py-3 border-bottom hover" style="background:transparent; transition: background 0.2s;">
                    <img src="<?php echo e($proposal->freelancer->avatar_url); ?>" class="rounded-circle flex-shrink-0 object-fit-cover" width="48" height="48" alt="">
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="fw-bold"><?php echo e($proposal->freelancer->name); ?></div>
                                <small class="text-muted">
                                    <?php if($proposal->freelancer->profile?->title): ?>
                                        <?php echo e($proposal->freelancer->profile->title); ?>

                                    <?php else: ?>
                                        Freelancer
                                    <?php endif; ?>
                                </small>
                            </div>
                            <span class="fw-bold text-primary text-nowrap">Nu. <?php echo e(number_format($proposal->bid_amount)); ?></span>
                        </div>
                        <p class="text-muted small mb-2" style="line-height:1.5;"><?php echo e(Str::limit($proposal->cover_letter, 120)); ?></p>
                        <?php
                            if ($proposal->status === 'pending') {
                                $pClass = 'bg-warning text-dark';
                            } elseif ($proposal->status === 'awarded') {
                                $pClass = 'bg-success';
                            } else {
                                $pClass = 'bg-secondary';
                            }
                        ?>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge <?php echo e($pClass); ?> small"><?php echo e(ucfirst($proposal->status)); ?></span>
                            <a href="<?php echo e(route('proposals.show', $proposal)); ?>" class="btn btn-link btn-sm p-0 text-primary">View Full →</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        
        <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->hasRole('freelancer') && $job->status === 'open' && !$alreadyApplied && $job->poster_id !== auth()->id()): ?>
            <?php if(auth()->user()->verification_status === 'verified'): ?>
            <div class="card border-0 shadow-sm" id="proposal-form">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-1"><i class="fa fa-paper-plane me-2 text-primary"></i>Submit Your Proposal</h5>
                    <p class="text-muted small mb-0">Write a compelling proposal that showcases your experience and understanding of the project.</p>
                    <div class="alert alert-info py-2 px-3 mt-3 mb-0 small d-flex align-items-center gap-2">
                        <i class="fa fa-info-circle"></i>
                        <span><strong>Proposal limits:</strong> Nu. 300 to Nu. 500,000</span>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('proposals.store', $job)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Bid Amount (Nu.) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0">Nu.</span>
                                    <input type="number" name="bid_amount" class="form-control border-start-0" required min="300" max="500000" placeholder="15,000" value="<?php echo e(old('bid_amount')); ?>" style="font-size:16px;">
                                </div>
                                <small class="text-muted d-block mt-2">Cannot be less than Nu. 300 or exceed Nu. 500,000</small>
                                <?php $__errorArgs = ['bid_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger d-block mt-1"><i class="fa fa-exclamation-circle me-1"></i><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Delivery Time (days)</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="delivery_days" class="form-control" min="1" placeholder="7" value="<?php echo e(old('delivery_days')); ?>" style="font-size:16px;">
                                    <span class="input-group-text bg-light">days</span>
                                </div>
                                <?php $__errorArgs = ['delivery_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger d-block mt-1"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Cover Letter <span class="text-danger">*</span></label>
                            <textarea name="cover_letter" class="form-control" rows="7" required style="font-size:15px; resize:vertical;"
                                      placeholder="Tell the client about your experience, approach, and why you're the best fit for this project..."><?php echo e(old('cover_letter')); ?></textarea>
                            <?php $__errorArgs = ['cover_letter'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger d-block mt-1"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-paper-plane me-2"></i>Submit Proposal
                            </button>
                            <span class="text-muted small"><i class="fa fa-info-circle me-1"></i>Your contact info will be shared with the client after submission.</span>
                        </div>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-info bg-opacity-10" style="width:60px;height:60px;">
                            <i class="fa fa-shield-alt text-info fs-4"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-2"><i class="fa fa-lock me-2"></i>Account Verification Required</h6>
                            <p class="text-muted mb-3">Complete your account verification to submit proposals. This helps build trust in our community.</p>
                            <a href="<?php echo e(route('profile.edit')); ?>#tab-docs" class="btn btn-info text-white btn-sm">
                                <i class="fa fa-arrow-right me-1"></i>Complete Verification
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>

    </div>

    
    <div class="col-lg-4">

        
        <div class="card border-0 shadow-sm mb-3 sticky-top" style="top: 20px;">
            <div class="card-body">
                <div class="text-muted small fw-semibold text-uppercase mb-2">Project Budget</div>
                <div style="font-size: 32px; font-weight: 800; color: #0d6efd; margin-bottom: 1rem;"><?php echo e($job->budgetRange); ?></div>

                <div class="mb-3">
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark border" style="font-size: 12px; padding: 6px 10px;">
                            <i class="fa fa-briefcase me-1"></i><?php echo e(ucfirst($job->type)); ?>

                        </span>
                        <?php if($job->experience_level): ?>
                        <span class="badge bg-light text-dark border" style="font-size: 12px; padding: 6px 10px;">
                            <i class="fa fa-layer-group me-1"></i><?php echo e(ucfirst($job->experience_level)); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($job->deadline || $job->job_deadline || $job->duration_days): ?>
                <?php
                    $proposalDeadlinePast = $job->deadline ? ($job->deadline->isToday() || $job->deadline->isPast()) : false;
                    $completionDeadline = $job->job_deadline ?: (($job->deadline && $job->duration_days) ? $job->deadline->copy()->addDays((int) $job->duration_days) : null);
                ?>
                <div class="alert <?php echo e($proposalDeadlinePast ? 'alert-danger' : 'alert-warning'); ?> py-3 px-3 d-flex align-items-center gap-3 mb-3">
                    <i class="fa fa-calendar-alt fs-5"></i>
                    <div style="font-size: 14px;">
                        <?php if($job->deadline): ?>
                        <div class="fw-semibold mb-1">Proposal Deadline</div>
                        <div class="fs-6 fw-bold text-danger"><?php echo e($job->deadline->format('d/m/Y')); ?></div>
                        <?php if($proposalDeadlinePast): ?>
                        <small class="text-danger fw-semibold d-block">This deadline has passed</small>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php if($completionDeadline): ?>
                        <small class="text-muted d-block mt-1">
                            <span class="fw-semibold text-body">Job Deadline:</span>
                            <?php echo e($completionDeadline->format('d/m/Y')); ?>

                        </small>
                        <?php elseif($job->duration_days): ?>
                        <small class="text-muted d-block mt-1">
                            <span class="fw-semibold text-body">Job Deadline:</span>
                            within <?php echo e((int) $job->duration_days); ?> days
                        </small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <hr>

                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->hasRole('freelancer') && $job->status === 'open' && $job->poster_id !== auth()->id()): ?>
                        <?php if(!$alreadyApplied): ?>
                            <?php if(auth()->user()->verification_status === 'verified'): ?>
                            <a href="#proposal-form" class="btn btn-primary w-100 btn-lg">
                                <i class="fa fa-paper-plane me-2"></i>Submit Proposal
                            </a>
                            <?php else: ?>
                            <button type="button" class="btn btn-outline-secondary w-100 btn-lg" disabled>
                                <i class="fa fa-paper-plane me-2"></i>Submit Proposal
                            </button>
                            <div class="border border-info rounded-2 bg-info bg-opacity-10 p-3 mt-3 small">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fa fa-lock text-info mt-1" style="font-size: 16px;"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold mb-2">Verify Your Account</div>
                                        <div class="text-muted mb-2" style="font-size: 13px;">Complete verification to submit proposals and build your profile.</div>
                                        <a href="<?php echo e(route('profile.edit')); ?>#tab-docs" class="btn btn-info btn-sm text-white">
                                            <i class="fa fa-arrow-right me-1"></i>Get Verified
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php else: ?>
                        <div class="alert alert-success py-3 px-3 d-flex align-items-center gap-2 mb-0">
                            <i class="fa fa-check-circle fs-5"></i>
                            <div>
                                <div class="fw-semibold small">Already Applied</div>
                                <small class="text-muted">You've already submitted a proposal for this job.</small>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php elseif(auth()->user()->id === $job->poster_id): ?>
                    <a href="<?php echo e(route('jobs.edit', $job)); ?>" class="btn btn-outline-secondary w-100 btn-lg">
                        <i class="fa fa-edit me-2"></i>Edit Job
                    </a>
                    <?php else: ?>
                    <a href="<?php echo e(route('login', ['intended' => route('jobs.show', $job)])); ?>" class="btn btn-primary w-100 btn-lg">
                        <i class="fa fa-sign-in-alt me-2"></i>Login to Apply
                    </a>
                    <?php endif; ?>
                <?php else: ?>
                <a href="<?php echo e(route('login', ['intended' => route('jobs.show', $job)])); ?>" class="btn btn-primary w-100 btn-lg">
                    <i class="fa fa-sign-in-alt me-2"></i>Login to Apply
                </a>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small fw-semibold text-uppercase mb-3 pb-3 border-bottom">About the Client</div>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="<?php echo e($job->poster->avatar_url); ?>" class="rounded-circle object-fit-cover flex-shrink-0 border" width="50" height="50" alt="">
                    <div class="overflow-hidden">
                        <div class="fw-bold"><?php echo e($job->poster->name); ?></div>
                        <?php if($job->poster->profile?->company_name): ?>
                        <div class="text-muted small text-truncate"><?php echo e($job->poster->profile->company_name); ?></div>
                        <?php else: ?>
                        <div class="text-muted small">Job Poster</div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($job->poster->profile?->dzongkhag): ?>
                <div class="text-muted small mb-2 d-flex align-items-center gap-2">
                    <i class="fa fa-map-marker-alt text-danger"></i><?php echo e($job->poster->profile->dzongkhag); ?>, Bhutan
                </div>
                <?php endif; ?>

                <?php if($job->poster->verification_status === 'verified'): ?>
                <div class="mb-3">
                    <span class="badge bg-success"><i class="fa fa-check-circle me-1"></i>Verified Client</span>
                </div>
                <?php endif; ?>

                <a href="<?php echo e(route('profile.show', $job->poster)); ?>" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fa fa-user me-1"></i>View Full Profile
                </a>
            </div>
        </div>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bidInput = document.querySelector('input[name="bid_amount"]');
    const effectiveMax = 500000;

    if (bidInput) {
        bidInput.addEventListener('input', function() {
            const bid = parseFloat(this.value);
            if (isNaN(bid)) { this.classList.remove('is-invalid'); return; }
            if (bid < 300 || bid > effectiveMax) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/jobs/show.blade.php ENDPATH**/ ?>