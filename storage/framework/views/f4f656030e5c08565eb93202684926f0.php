
<?php $__env->startSection('title', 'Completion Submission'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Completion Submission</h4>
        <div class="text-muted small"><?php echo e($submission->contract->contract_number); ?></div>
    </div>
    <a href="<?php echo e(route('contracts.show', $submission->contract)); ?>" class="btn btn-outline-secondary btn-sm">
        <i class="fa fa-arrow-left me-1"></i>Back to Contract
    </a>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Status</h6>
                <?php if($submission->isPending()): ?>
                    <span class="badge bg-warning text-dark">Awaiting Admin Review</span>
                <?php elseif($submission->isVerified()): ?>
                    <span class="badge bg-info">Verified</span>
                <?php elseif($submission->isPaymentProcessed()): ?>
                    <span class="badge bg-success">Payment Processed</span>
                <?php else: ?>
                    <span class="badge bg-danger">Rejected</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="fa fa-upload text-muted me-2"></i>Submitted: <?php echo e($submission->submitted_at?->format('d M Y, h:i A')); ?></li>
                    <?php if($submission->verified_at): ?>
                    <li class="mb-2"><i class="fa fa-check-circle text-info me-2"></i>Verified: <?php echo e($submission->verified_at->format('d M Y, h:i A')); ?></li>
                    <?php endif; ?>
                    <?php if($submission->payment_processed_at): ?>
                    <li class="mb-2"><i class="fa fa-wallet text-success me-2"></i>Payment processed: <?php echo e($submission->payment_processed_at->format('d M Y, h:i A')); ?></li>
                    <?php endif; ?>
                    <?php if($submission->rejected_at): ?>
                    <li><i class="fa fa-times-circle text-danger me-2"></i>Rejected: <?php echo e($submission->rejected_at->format('d M Y, h:i A')); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Submission Notes</h6>
            </div>
            <div class="card-body">
                <p class="mb-0 text-muted" style="white-space: pre-wrap;"><?php echo e($submission->submission_notes); ?></p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Evidence Files</h6>
                <span class="badge bg-light text-dark border"><?php echo e($submission->attachments->count()); ?> files</span>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $submission->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $inlineUrl = route('completion.download-attachment', $attachment) . '?inline=1';
                    $mimeType = $attachment->file_type ?? '';
                    $isImage = str_starts_with($mimeType, 'image/');
                    $isVideo = str_starts_with($mimeType, 'video/');
                    $isPdf = $mimeType === 'application/pdf';
                ?>
                <div class="border rounded-3 p-3 mb-3 bg-white">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <div class="fw-semibold"><?php echo e($attachment->file_name); ?></div>
                            <div class="text-muted small mt-1"><?php echo e($attachment->getDocumentTypeLabel()); ?> • <?php echo e(number_format($attachment->file_size / 1024, 1)); ?> KB</div>
                            <?php if($attachment->description): ?>
                            <div class="text-muted small mt-2"><?php echo e($attachment->description); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e($inlineUrl); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-eye me-1"></i>Open Preview
                            </a>
                            <a href="<?php echo e(route('completion.download-attachment', $attachment)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-download me-1"></i>Download
                            </a>
                        </div>
                    </div>

                    <?php if($isImage): ?>
                    <div class="rounded-3 border overflow-hidden bg-light-subtle">
                        <img src="<?php echo e($inlineUrl); ?>" alt="<?php echo e($attachment->file_name); ?>" class="img-fluid w-100" style="max-height: 420px; object-fit: contain;" loading="lazy">
                    </div>
                    <?php elseif($isVideo): ?>
                    <div class="rounded-3 border overflow-hidden bg-dark">
                        <video controls preload="metadata" class="w-100" style="max-height: 420px;">
                            <source src="<?php echo e($inlineUrl); ?>" type="<?php echo e($mimeType); ?>">
                            Your browser does not support video playback. Use the download button instead.
                        </video>
                    </div>
                    <?php elseif($isPdf): ?>
                    <div class="rounded-3 border overflow-hidden bg-light-subtle" style="height: 520px;">
                        <iframe src="<?php echo e($inlineUrl); ?>" title="PDF preview for <?php echo e($attachment->file_name); ?>" class="w-100 h-100 border-0"></iframe>
                    </div>
                    <?php else: ?>
                    <div class="rounded-3 border bg-light-subtle p-4 text-center text-muted small">
                        <i class="fa fa-file fa-lg d-block mb-2"></i>
                        Preview is not available for this file type (<?php echo e($mimeType ?: 'unknown'); ?>). Use Open Preview or Download.
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-muted">No files uploaded.</div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($submission->isRejected()): ?>
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Admin Feedback</div>
            <div><?php echo e($submission->rejection_reason); ?></div>
            <?php if(auth()->id() === $submission->freelancer_id): ?>
            <a href="<?php echo e(route('completion.create', $submission->contract)); ?>" class="btn btn-sm btn-danger mt-3">
                <i class="fa fa-redo me-1"></i>Resubmit Completion
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-xl-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Contract Info</h6>
            </div>
            <div class="card-body small">
                <div class="mb-2">
                    <div class="text-muted">Job</div>
                    <div class="fw-semibold"><?php echo e($submission->contract->job->title); ?></div>
                </div>
                <div class="mb-2">
                    <div class="text-muted">Freelancer Amount</div>
                    <div class="fw-semibold text-success"><?php echo e(config('platform.currency')); ?> <?php echo e(number_format($submission->contract->freelancer_amount, 2)); ?></div>
                </div>
                <div class="mb-2">
                    <div class="text-muted">Platform Fee</div>
                    <div class="fw-semibold"><?php echo e(config('platform.currency')); ?> <?php echo e(number_format($submission->contract->platform_fee, 2)); ?></div>
                </div>
                <div>
                    <div class="text-muted">Deadline</div>
                    <div class="fw-semibold"><?php echo e($submission->contract->deadline?->format('d M Y') ?? 'N/A'); ?></div>
                </div>
                <hr>
                <a href="<?php echo e(route('contracts.show', $submission->contract)); ?>" class="btn btn-sm btn-outline-primary w-100">
                    <i class="fa fa-file-contract me-1"></i>Open Contract
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/completion/show.blade.php ENDPATH**/ ?>