<?php $__env->startSection('title', 'Transactions'); ?>
<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-4">Transactions</h4>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <?php $__currentLoopData = [
        ['label'=>'Total Revenue','value'=>'Nu. '.number_format($summary['total_revenue']),'color'=>'success'],
        ['label'=>'Pending Withdrawals','value'=>$summary['pending_withdrawals'],'color'=>'warning'],
        ['label'=>'Total Deposits','value'=>'Nu. '.number_format($summary['total_deposits']),'color'=>'info'],
        ['label'=>'Total Withdrawn','value'=>'Nu. '.number_format($summary['total_withdrawn']),'color'=>'danger'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-sm-6 col-xl-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-bold fs-5 text-<?php echo e($card['color']); ?>"><?php echo e($card['value']); ?></div>
                <div class="text-muted small"><?php echo e($card['label']); ?></div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2">
            <div class="col-sm-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="User or Ref..." value="<?php echo e(request('search')); ?>"></div>
            <div class="col-sm-2">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <?php $__currentLoopData = ['deposit','withdrawal','escrow_hold','escrow_release','platform_fee','refund']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t); ?>" <?php if(request('type')===$t): echo 'selected'; endif; ?>><?php echo e(ucfirst(str_replace('_',' ',$t))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-sm-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <?php $__currentLoopData = ['pending','completed','failed','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" <?php if(request('status')===$s): echo 'selected'; endif; ?>><?php echo e(ucfirst($s)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-sm-2"><input type="date" name="from" class="form-control form-control-sm" value="<?php echo e(request('from')); ?>"></div>
            <div class="col-sm-2"><input type="date" name="to" class="form-control form-control-sm" value="<?php echo e(request('to')); ?>"></div>
            <div class="col-auto d-flex gap-1">
                <button class="btn btn-sm btn-primary">Filter</button>
                <a href="<?php echo e(route('admin.transactions.index')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr>
                <th>User</th><th>Type</th><th>Amount</th><th>Provider</th><th>Ref</th><th>Status</th><th>Date</th><th class="text-end">Actions</th>
            </tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><small class="fw-semibold"><?php echo e($tx->user?->name); ?></small></td>
                    <td><span class="badge bg-light text-dark border" style="font-size:10px"><?php echo e(ucfirst(str_replace('_',' ',$tx->type))); ?></span></td>
                    <td><span class="fw-semibold <?php echo e($tx->amount < 0 ? 'text-danger' : 'text-success'); ?>">Nu. <?php echo e(number_format(abs($tx->amount))); ?></span></td>
                    <td><small class="text-muted"><?php echo e($tx->payment_provider ?? '—'); ?></small></td>
                    <td><small class="text-muted font-monospace"><?php echo e(Str::limit($tx->transaction_ref, 14)); ?></small></td>
                    <td><span class="badge bg-<?php echo e(match($tx->status){ 'completed'=>'success','pending'=>'warning','failed'=>'danger','cancelled'=>'secondary', default=>'secondary'}); ?>" style="font-size:10px"><?php echo e(ucfirst($tx->status)); ?></span></td>
                    <td><small class="text-muted"><?php echo e($tx->created_at->format('d M Y')); ?></small></td>
                    <td class="text-end">
                        <?php if($tx->type === 'withdrawal' && $tx->status === 'pending'): ?>
                        <div class="d-flex gap-1 justify-content-end">
                            <form method="POST" action="<?php echo e(route('admin.transactions.approve', $tx)); ?>"><?php echo csrf_field(); ?> <button class="btn btn-sm btn-success" title="Approve">✓</button></form>
                            <form method="POST" action="<?php echo e(route('admin.transactions.reject', $tx)); ?>" class="d-flex gap-1">
                                <?php echo csrf_field(); ?>
                                <input type="text" name="reason" class="form-control form-control-sm" placeholder="Reason" required style="width:100px">
                                <button class="btn btn-sm btn-danger" title="Reject">✗</button>
                            </form>
                        </div>
                        <?php else: ?>
                        <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No transactions found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0"><?php echo e($transactions->withQueryString()->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\admin\transactions\index.blade.php ENDPATH**/ ?>