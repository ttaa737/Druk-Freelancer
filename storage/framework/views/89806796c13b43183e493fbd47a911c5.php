
<?php $__env->startSection('title', 'Completion Statistics'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Completion Statistics</h4>
        <div class="text-muted small">Overview of completion verification and settlement outcomes.</div>
    </div>
    <a href="<?php echo e(route('admin.completions.index')); ?>" class="btn btn-outline-secondary btn-sm">
        <i class="fa fa-arrow-left me-1"></i>Back to Submissions
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Pending Review</div>
                <div class="fs-3 fw-bold text-warning"><?php echo e($pending); ?></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Verified</div>
                <div class="fs-3 fw-bold text-info"><?php echo e($verified); ?></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Payment Processed</div>
                <div class="fs-3 fw-bold text-success"><?php echo e($payment_processed); ?></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Rejected</div>
                <div class="fs-3 fw-bold text-danger"><?php echo e($rejected); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">Recent Submissions</h6>
        <span class="badge bg-light text-dark border"><?php echo e($recent->count()); ?> records</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Contract</th>
                    <th>Freelancer</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="fw-semibold"><?php echo e($submission->contract->contract_number); ?></div>
                        <div class="text-muted small"><?php echo e(\Illuminate\Support\Str::limit($submission->contract->job->title, 50)); ?></div>
                    </td>
                    <td>
                        <a href="<?php echo e(route('admin.users.show', $submission->freelancer)); ?>" class="text-decoration-none fw-semibold">
                            <?php echo e($submission->freelancer->name); ?>

                        </a>
                    </td>
                    <td class="text-muted small"><?php echo e($submission->submitted_at?->format('d M Y, h:i A')); ?></td>
                    <td>
                        <?php if($submission->status === \App\Models\CompletionSubmission::STATUS_PENDING): ?>
                            <span class="badge bg-warning text-dark">Pending</span>
                        <?php elseif($submission->status === \App\Models\CompletionSubmission::STATUS_VERIFIED): ?>
                            <span class="badge bg-info">Verified</span>
                        <?php elseif($submission->status === \App\Models\CompletionSubmission::STATUS_PAYMENT_PROCESSED): ?>
                            <span class="badge bg-success">Payment Processed</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Rejected</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="<?php echo e(route('admin.completions.show', $submission)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye me-1"></i>Review
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No submissions yet.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/admin/completions/stats.blade.php ENDPATH**/ ?>