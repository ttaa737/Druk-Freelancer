<?php $__env->startSection('title', 'Disputes'); ?>
<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-4">Disputes</h4>

<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2">
            <div class="col-sm-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <?php $__currentLoopData = ['open','under_review','resolved','closed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" <?php if(request('status')===$s): echo 'selected'; endif; ?>><?php echo e(ucfirst(str_replace('_',' ',$s))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-sm-3">
                <select name="assigned" class="form-select form-select-sm">
                    <option value="">All Assignments</option>
                    <option value="mine" <?php if(request('assigned')==='mine'): echo 'selected'; endif; ?>>Assigned to Me</option>
                    <option value="unassigned" <?php if(request('assigned')==='unassigned'): echo 'selected'; endif; ?>>Unassigned</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary">Filter</button>
                <a href="<?php echo e(route('admin.disputes.index')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr>
                <th>Subject</th><th>Contract</th><th>Raised By</th><th>Assigned To</th><th>Status</th><th>Raised</th><th class="text-end">Actions</th>
            </tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $disputes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dispute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><div class="small fw-semibold"><?php echo e(Str::limit($dispute->subject, 50)); ?></div></td>
                    <td><small class="text-muted"><?php echo e($dispute->contract?->title); ?></small></td>
                    <td><small class="text-muted"><?php echo e($dispute->raisedBy?->name); ?></small></td>
                    <td><small class="<?php echo e($dispute->assignedAdmin ? 'text-dark' : 'text-muted'); ?>"><?php echo e($dispute->assignedAdmin?->name ?? 'Unassigned'); ?></small></td>
                    <td><span class="badge bg-<?php echo e(match($dispute->status){ 'open'=>'warning','under_review'=>'info','resolved'=>'success','closed'=>'secondary', default=>'secondary'}); ?>" style="font-size:10px"><?php echo e(ucfirst(str_replace('_',' ',$dispute->status))); ?></span></td>
                    <td><small class="text-muted"><?php echo e($dispute->created_at->diffForHumans()); ?></small></td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="<?php echo e(route('admin.disputes.show', $dispute)); ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                            <?php if(!$dispute->assigned_admin_id): ?>
                            <form method="POST" action="<?php echo e(route('admin.disputes.assign', $dispute)); ?>"><?php echo csrf_field(); ?> <button class="btn btn-sm btn-outline-secondary" title="Assign to Me">Claim</button></form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No disputes found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0"><?php echo e($disputes->withQueryString()->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/admin/disputes/index.blade.php ENDPATH**/ ?>