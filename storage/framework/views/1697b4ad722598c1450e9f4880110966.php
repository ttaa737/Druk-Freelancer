<?php $__env->startSection('title', 'Manage Jobs'); ?>
<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-4">Jobs</h4>

<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2">
            <div class="col-sm-4"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search jobs..." value="<?php echo e(request('search')); ?>"></div>
            <div class="col-sm-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <?php $__currentLoopData = ['open','in_progress','completed','cancelled','moderated']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" <?php if(request('status')===$s): echo 'selected'; endif; ?>><?php echo e(ucfirst(str_replace('_',' ',$s))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary">Filter</button>
                <a href="<?php echo e(route('admin.jobs.index')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr>
                <th>Title</th><th>Poster</th><th>Budget</th><th>Status</th><th>Featured</th><th>Posted</th><th class="text-end">Actions</th>
            </tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="<?php echo e($job->trashed() ? 'table-danger' : ''); ?>">
                    <td><div class="small fw-semibold"><?php echo e(Str::limit($job->title, 50)); ?></div></td>
                    <td><small class="text-muted"><?php echo e($job->poster?->name); ?></small></td>
                    <td><small>Nu. <?php echo e(number_format($job->budget_min)); ?>–<?php echo e(number_format($job->budget_max)); ?></small></td>
                    <td><span class="badge bg-<?php echo e(match($job->status){ 'open'=>'success','in_progress'=>'info','completed'=>'primary','cancelled','moderated'=>'danger', default=>'secondary'}); ?>" style="font-size:10px"><?php echo e(ucfirst(str_replace('_',' ',$job->status))); ?></span></td>
                    <td>
                        <form method="POST" action="<?php echo e(route('admin.jobs.feature', $job)); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm <?php echo e($job->is_featured ? 'btn-warning' : 'btn-outline-secondary'); ?>" title="<?php echo e($job->is_featured ? 'Unfeature' : 'Feature'); ?>"><i class="fa fa-star" style="font-size:11px"></i></button>
                        </form>
                    </td>
                    <td><small class="text-muted"><?php echo e($job->created_at->format('d M Y')); ?></small></td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="<?php echo e(route('admin.jobs.show', $job)); ?>" class="btn btn-sm btn-outline-primary" title="View"><i class="fa fa-eye"></i></a>
                            <?php if(!$job->trashed()): ?>
                            <button type="button" class="btn btn-sm btn-outline-danger" title="Moderate" data-bs-toggle="modal" data-bs-target="#moderateJobModal" data-job-id="<?php echo e($job->id); ?>" data-job-title="<?php echo e($job->title); ?>" data-job-route="<?php echo e(route('admin.jobs.moderate', $job)); ?>"><i class="fa fa-ban"></i></button>
                            <?php else: ?>
                            <form method="POST" action="<?php echo e(route('admin.jobs.restore', $job)); ?>"><?php echo csrf_field(); ?> <button class="btn btn-sm btn-outline-success" title="Restore"><i class="fa fa-undo"></i></button></form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No jobs found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0"><?php echo e($jobs->withQueryString()->links()); ?></div>
</div>

<!-- Moderation Modal -->
<div class="modal fade" id="moderateJobModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger bg-opacity-10">
                <h5 class="modal-title"><i class="fa fa-ban me-2 text-danger"></i>Moderate Job Posting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="moderateJobForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="alert alert-danger small">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action will close and hide this job from all listings. This action is permanent.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Job Title</label>
                        <div class="alert alert-light mb-0" id="jobTitleDisplay"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Moderation Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" id="moderationReason" class="form-control" rows="3" required placeholder="Provide clear details about why this job is being moderated (e.g., policy violation, inappropriate content)..."></textarea>
                        <small class="text-muted">The reason will be logged in the audit trail for record keeping.</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="moderateJobConfirm" required>
                        <label class="form-check-label small" for="moderateJobConfirm">
                            I understand this will permanently close and hide this job
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you absolutely sure you want to moderate this job?')">
                        <i class="fa fa-ban me-1"></i>Moderate Job
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Set up modal when opener button is clicked
document.getElementById('moderateJobModal').addEventListener('show.bs.modal', function (e) {
    const button = e.relatedTarget;
    const jobId = button.getAttribute('data-job-id');
    const jobTitle = button.getAttribute('data-job-title');
    const jobRoute = button.getAttribute('data-job-route');
    
    document.getElementById('jobTitleDisplay').textContent = jobTitle;
    document.getElementById('moderateJobForm').action = jobRoute;
    document.getElementById('moderationReason').value = '';
    document.getElementById('moderateJobConfirm').checked = false;
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/admin/jobs/index.blade.php ENDPATH**/ ?>