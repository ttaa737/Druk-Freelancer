
<?php $__env->startSection('title', 'Freelancer Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-1">Freelancer Reports</h5>
        <p class="text-muted mb-0">Track your income, activity, feedback, and growth opportunities.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 stat-card">
            <div class="card-body">
                <div class="text-muted small">Total Earnings</div>
                <div class="fs-4 fw-bold text-success">Nu. <?php echo e(number_format($financials['total_earnings'], 2)); ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100" style="border-left:4px solid #3b82f6;">
            <div class="card-body">
                <div class="text-muted small">Monthly Income</div>
                <div class="fs-4 fw-bold">Nu. <?php echo e(number_format($financials['monthly_income'], 2)); ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100" style="border-left:4px solid #f59e0b;">
            <div class="card-body">
                <div class="text-muted small">Pending Payments</div>
                <div class="fs-4 fw-bold">Nu. <?php echo e(number_format($financials['pending_payments'], 2)); ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100" style="border-left:4px solid #ef4444;">
            <div class="card-body">
                <div class="text-muted small">Withdrawn Amount</div>
                <div class="fs-4 fw-bold">Nu. <?php echo e(number_format($financials['withdrawn_amount'], 2)); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-bold">Work Activity Reports</div>
            <div class="card-body">
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>Jobs Applied</span>
                    <span class="fw-semibold"><?php echo e(number_format($activity['jobs_applied'])); ?></span>
                </div>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>Accepted Proposals</span>
                    <span class="fw-semibold"><?php echo e(number_format($activity['accepted_proposals'])); ?></span>
                </div>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>Completed Projects</span>
                    <span class="fw-semibold"><?php echo e(number_format($activity['completed_projects'])); ?></span>
                </div>
                <div class="d-flex justify-content-between pt-2">
                    <span>Active Contracts</span>
                    <span class="fw-semibold"><?php echo e(number_format($activity['active_contracts'])); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-bold">Client Feedback & Ratings</div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="border rounded p-2 text-center">
                            <div class="text-muted small">Average Rating</div>
                            <div class="fw-bold fs-4"><?php echo e(number_format($feedback['average_rating'], 1)); ?></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2 text-center">
                            <div class="text-muted small">Total Reviews</div>
                            <div class="fw-bold fs-4"><?php echo e(number_format($feedback['total_reviews'])); ?></div>
                        </div>
                    </div>
                </div>

                <div class="small text-muted mb-2">Rating Breakdown</div>
                <div class="d-flex justify-content-between small border-bottom py-1">
                    <span>Communication</span>
                    <span class="fw-semibold"><?php echo e(number_format($feedback['rating_breakdown']['communication'], 1)); ?></span>
                </div>
                <div class="d-flex justify-content-between small border-bottom py-1">
                    <span>Quality</span>
                    <span class="fw-semibold"><?php echo e(number_format($feedback['rating_breakdown']['quality'], 1)); ?></span>
                </div>
                <div class="d-flex justify-content-between small border-bottom py-1">
                    <span>Timeliness</span>
                    <span class="fw-semibold"><?php echo e(number_format($feedback['rating_breakdown']['timeliness'], 1)); ?></span>
                </div>
                <div class="d-flex justify-content-between small pt-1">
                    <span>Professionalism</span>
                    <span class="fw-semibold"><?php echo e(number_format($feedback['rating_breakdown']['professionalism'], 1)); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header fw-bold">Recent Client Feedback</div>
    <div class="card-body p-0">
        <?php $__empty_1 = true; $__currentLoopData = $feedback['recent']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="px-3 py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div class="fw-semibold small"><?php echo e($review->reviewer?->name ?? 'Client'); ?></div>
                    <span class="badge bg-warning text-dark"><?php echo e(number_format($review->rating_overall, 1)); ?> / 5</span>
                </div>
                <div class="text-muted small"><?php echo e($review->comment ?: 'No written comment.'); ?></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-4 text-center text-muted">No client feedback yet.</div>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-bold">Time Tracking Reports</div>
            <div class="card-body">
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>Hourly Projects</span>
                    <span class="fw-semibold"><?php echo e(number_format($hourly['hourly_projects'])); ?></span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>Estimated Billable Hours</span>
                    <span class="fw-semibold"><?php echo e(number_format($hourly['estimated_billable_hours'], 1)); ?> hrs</span>
                </div>
                <div class="alert alert-light small mb-0 mt-2">
                    Estimated from hourly job duration (8 hrs/day) because detailed timesheets are not stored yet.
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-bold">Performance Strategy Reports</div>
            <div class="card-body">
                <div class="small text-muted mb-2">Highest-Paying Categories</div>
                <?php $__empty_1 = true; $__currentLoopData = $performanceCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between border-bottom py-2 small">
                        <span><?php echo e($category->category_name); ?></span>
                        <span class="fw-semibold">Nu. <?php echo e(number_format($category->average_project_value, 2)); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-muted small mb-3">Not enough completed projects to calculate category insights.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header fw-bold">Most Successful Skills</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Skill</th>
                    <th class="text-end">Completed Projects</th>
                    <th class="text-end">Total Earnings (Nu.)</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $performanceSkills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($skill->skill_name); ?></td>
                        <td class="text-end"><?php echo e(number_format($skill->completed_projects)); ?></td>
                        <td class="text-end fw-semibold"><?php echo e(number_format($skill->total_earnings, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Not enough completed projects to generate skill performance insights.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/reports/freelancer.blade.php ENDPATH**/ ?>