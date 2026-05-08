<?php $__env->startSection('title', 'Verifications'); ?>
<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-4">Verifications</h4>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4">
        <i class="fa fa-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2">
            <div class="col-sm-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="pending" <?php if(request('status')==='pending' || !request('status')): echo 'selected'; endif; ?>>Pending Review</option>
                    <option value="approved" <?php if(request('status')==='approved'): echo 'selected'; endif; ?>>Approved</option>
                    <option value="rejected" <?php if(request('status')==='rejected'): echo 'selected'; endif; ?>>Rejected</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary">Filter</button>
                <a href="<?php echo e(route('admin.verifications.index')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Pending</th>
                    <th>Approved</th>
                    <th>Rejected</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $userDocs = $user->verificationDocuments;
                    $pendingDocs = $userDocs->where('status', 'pending')->count();
                    $approvedDocs = $userDocs->where('status', 'approved')->count();
                    $rejectedDocs = $userDocs->where('status', 'rejected')->count();
                ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="<?php echo e($user->avatar_url ?? asset('images/default-avatar.png')); ?>" 
                                 class="rounded-circle" 
                                 style="width:32px;height:32px;object-fit:cover;"
                                 alt="User avatar">
                            <span class="small fw-semibold"><?php echo e($user->name); ?></span>
                        </div>
                    </td>
                    <td><small class="text-muted"><?php echo e($user->email); ?></small></td>
                    <td><small><?php echo e(ucfirst($user->role)); ?></small></td>
                    <td>
                        <?php if($pendingDocs > 0): ?>
                            <span class="badge bg-warning text-dark" style="font-size:10px"><?php echo e($pendingDocs); ?></span>
                        <?php else: ?>
                            <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($approvedDocs > 0): ?>
                            <span class="badge bg-success" style="font-size:10px"><?php echo e($approvedDocs); ?></span>
                        <?php else: ?>
                            <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($rejectedDocs > 0): ?>
                            <span class="badge bg-danger" style="font-size:10px"><?php echo e($rejectedDocs); ?></span>
                        <?php else: ?>
                            <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="<?php echo e(route('admin.verifications.show', $user)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No verification submissions found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0"><?php echo e($users->withQueryString()->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/admin/verifications/index.blade.php ENDPATH**/ ?>