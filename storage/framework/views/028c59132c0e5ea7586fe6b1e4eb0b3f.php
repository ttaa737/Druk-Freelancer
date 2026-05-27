<?php $__env->startSection('title', $job->title); ?>
<?php $__env->startSection('content'); ?>

<div class="row g-4">

    
    <div class="col-lg-8">

        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
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
                    <div class="row g-3">
                        <?php $__currentLoopData = $job->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $mime = strtolower((string) $attachment->file_type);
                            $isImage = str_starts_with($mime, 'image/');
                            $isPdf = str_contains($mime, 'pdf');
                            $badgeClass = $isImage ? 'success' : ($isPdf ? 'danger' : 'secondary');
                            $icon = $isImage ? 'image' : ($isPdf ? 'file-pdf' : 'file-alt');
                        ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card h-100 border shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <div class="rounded-circle bg-<?php echo e($badgeClass); ?> bg-opacity-10 text-<?php echo e($badgeClass); ?> d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;">
                                            <i class="fa fa-<?php echo e($icon); ?>"></i>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-semibold text-truncate"><?php echo e($attachment->original_name); ?></div>
                                            <div class="text-muted small text-truncate"><?php echo e($attachment->file_type ?? 'File attachment'); ?></div>
                                        </div>
                                    </div>

                                    <?php if($attachment->previewable): ?>
                                    <button type="button" class="btn btn-outline-primary btn-sm mb-2 preview-attachment-btn"
                                        data-preview-url="<?php echo e($attachment->previewUrl); ?>"
                                        data-preview-name="<?php echo e($attachment->original_name); ?>"
                                        data-preview-type="<?php echo e($attachment->file_type); ?>">
                                        <i class="fa fa-eye me-1"></i>Preview
                                    </button>
                                    <?php endif; ?>

                                    <a href="<?php echo e($attachment->downloadUrl); ?>" class="btn btn-outline-secondary btn-sm mt-auto">
                                        <i class="fa fa-download me-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        </div>
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
                    <?php
                        $proposalMinBid = $job->budget_min !== null ? (float) $job->budget_min : null;
                        $proposalMaxBid = $job->budget_max !== null ? (float) $job->budget_max : null;
                    ?>
                    <form method="POST" action="<?php echo e(route('proposals.store', $job)); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Bid Amount (Nu.) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0">Nu.</span>
                                    <input type="number" name="bid_amount" class="form-control border-start-0" required <?php if($proposalMinBid !== null): ?> min="<?php echo e($proposalMinBid); ?>" <?php endif; ?> <?php if($proposalMaxBid !== null): ?> max="<?php echo e($proposalMaxBid); ?>" <?php endif; ?> placeholder="15,000" value="<?php echo e(old('bid_amount')); ?>" style="font-size:16px;">
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <?php if($proposalMinBid !== null && $proposalMaxBid !== null): ?>
                                        Project budget: Nu. <?php echo e(number_format($proposalMinBid)); ?> - Nu. <?php echo e(number_format($proposalMaxBid)); ?>

                                    <?php elseif($proposalMinBid !== null): ?>
                                        Minimum project budget: Nu. <?php echo e(number_format($proposalMinBid)); ?>

                                    <?php elseif($proposalMaxBid !== null): ?>
                                        Maximum project budget: Nu. <?php echo e(number_format($proposalMaxBid)); ?>

                                    <?php else: ?>
                                        Budget negotiable
                                    <?php endif; ?>
                                </small>
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
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Upload CV / Resume <span class="text-danger">*</span></label>
                            
                            <?php
                                $verifiedCV = auth()->user()->verificationDocuments()
                                    ->where('document_type', 'cv')
                                    ->where('status', 'approved')
                                    ->first();
                            ?>

                            <?php if($verifiedCV): ?>
                                <div class="mb-3 p-3 border rounded bg-light">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="use_verified_cv" id="useVerifiedCV" value="1">
                                            <label class="form-check-label fw-semibold" for="useVerifiedCV">
                                                Use My Verified CV
                                            </label>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block mb-1">
                                                <i class="fa fa-check-circle text-success me-1"></i>
                                                <?php echo e($verifiedCV->original_name); ?>

                                            </small>
                                            <small class="text-muted d-block">
                                                Approved <?php echo e($verifiedCV->reviewed_at?->format('d M Y') ?? 'recently'); ?>

                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="text-center text-muted small">OR</div>
                                </div>

                                <div class="p-3 border rounded bg-light mb-3">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="use_verified_cv" id="uploadNewCV" value="0" checked>
                                            <label class="form-check-label fw-semibold" for="uploadNewCV">
                                                Upload a Different CV
                                            </label>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block">
                                                Upload a specific CV for this project
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <input type="file" name="cv_file" class="form-control" accept=".pdf,.doc,.docx" id="cvFileInput">
                                <small class="text-muted d-block mt-2">Accepted formats: PDF, DOC, DOCX. Max size: 10 MB.</small>
                                <?php $__errorArgs = ['cv_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger d-block mt-1"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <?php $__errorArgs = ['use_verified_cv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger d-block mt-1"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const useVerified = document.getElementById('useVerifiedCV');
                                    const uploadNew = document.getElementById('uploadNewCV');
                                    const cvInput = document.getElementById('cvFileInput');

                                    function updateCVRequirement() {
                                        if (useVerified.checked) {
                                            cvInput.removeAttribute('required');
                                            cvInput.classList.add('d-none');
                                        } else {
                                            cvInput.setAttribute('required', 'required');
                                            cvInput.classList.remove('d-none');
                                        }
                                    }

                                    useVerified.addEventListener('change', updateCVRequirement);
                                    uploadNew.addEventListener('change', updateCVRequirement);
                                    updateCVRequirement(); // Initial state
                                });
                                </script>
                            <?php else: ?>
                                <input type="file" name="cv_file" class="form-control" accept=".pdf,.doc,.docx" required>
                                <small class="text-muted d-block mt-2">Accepted formats: PDF, DOC, DOCX. Max size: 10 MB.</small>
                                <small class="text-info d-block mt-2">
                                    <i class="fa fa-lightbulb me-1"></i>
                                    <strong>Tip:</strong> Upload a CV to your account verification to use it automatically for future proposals.
                                </small>
                                <?php $__errorArgs = ['cv_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger d-block mt-1"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php endif; ?>
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

        
        <div class="card border-0 shadow-sm mb-3">
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

<!-- Attachment Preview Modal -->
<div class="modal fade" id="attachmentPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attachmentPreviewTitle">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="attachmentPreviewBody" style="min-height:70vh;background:#f8f9fa;"></div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary" id="attachmentPreviewDownload" target="_blank"><i class="fa fa-download me-1"></i>Download</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bidInput = document.querySelector('input[name="bid_amount"]');
    const effectiveMax = 500000;
    const modalEl = document.getElementById('attachmentPreviewModal');
    const modal = modalEl ? new bootstrap.Modal(modalEl) : null;
    const title = document.getElementById('attachmentPreviewTitle');
    const body = document.getElementById('attachmentPreviewBody');
    const download = document.getElementById('attachmentPreviewDownload');

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

    document.querySelectorAll('.preview-attachment-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const url = button.dataset.previewUrl;
            const name = button.dataset.previewName || 'Preview';
            const type = (button.dataset.previewType || '').toLowerCase();

            if (!modal || !title || !body || !download) {
                window.open(url, '_blank', 'noopener');
                return;
            }

            title.textContent = name;
            download.href = url.replace('/preview', '/download');

            if (type.includes('image/')) {
                body.innerHTML = '<div class="d-flex justify-content-center align-items-center p-3" style="min-height:70vh;"><img src="' + url + '" class="img-fluid rounded shadow-sm" style="max-height:68vh;object-fit:contain;" alt="Preview"></div>';
            } else if (type.includes('pdf')) {
                body.innerHTML = '<iframe src="' + url + '" style="width:100%;height:70vh;border:0;background:#fff;"></iframe>';
            } else {
                body.innerHTML = '<div class="p-5 text-center text-muted"><i class="fa fa-file-alt fa-3x mb-3 opacity-25"></i><div>This file type cannot be previewed inline.</div></div>';
            }

            modal.show();
        });
    });

    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', () => {
            if (body) {
                body.innerHTML = '';
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/jobs/show.blade.php ENDPATH**/ ?>