<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-0">Welcome back, <?php echo e(auth()->user()->name); ?>! 👋</h4>
        <p class="text-muted">Here's what's happening on your account.</p>
    </div>
</div>


<?php if(auth()->user()->hasRole('freelancer')): ?>
<div class="row g-3 mb-4">
    <?php $__currentLoopData = [
        ['label'=>'Active Contracts','value'=>$stats['active_contracts'],'icon'=>'fa-file-contract','color'=>'primary'],
        ['label'=>'Pending Proposals','value'=>$stats['pending_proposals'],'icon'=>'fa-paper-plane','color'=>'warning'],
        ['label'=>'Completed','value'=>$stats['completed_contracts'],'icon'=>'fa-check-circle','color'=>'success'],
        ['label'=>'Total Earned','value'=>'Nu. '.number_format($stats['total_earned']),'icon'=>'fa-coins','color'=>'info'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-<?php echo e($s['color']); ?> bg-opacity-10 p-3">
                    <i class="fa <?php echo e($s['icon']); ?> text-<?php echo e($s['color']); ?> fa-lg"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5"><?php echo e($s['value']); ?></div>
                    <div class="text-muted small"><?php echo e($s['label']); ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-wallet me-2 text-warning"></i>Wallet</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Available</span>
                    <span class="fw-semibold">Nu. <?php echo e(number_format($stats['available_balance'])); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted small">In Escrow</span>
                    <span class="fw-semibold text-warning">Nu. <?php echo e(number_format($stats['escrow_balance'])); ?></span>
                </div>
                <a href="<?php echo e(route('wallet.index')); ?>" class="btn btn-outline-primary btn-sm w-100">Manage Wallet</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-star me-2 text-warning"></i>Rating</h6>
                <div class="display-6 fw-bold"><?php echo e(number_format($stats['average_rating'] ?? 0, 1)); ?></div>
                <div class="text-muted small">out of 5.0</div>
                <div class="mt-2">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <i class="fa fa-star <?php echo e($i <= round($stats['average_rating'] ?? 0) ? 'text-warning' : 'text-muted'); ?>"></i>
                    <?php endfor; ?>
                </div>
                <a href="<?php echo e(route('profile.show', auth()->user())); ?>" class="btn btn-outline-secondary btn-sm w-100 mt-3">View Profile</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-search me-2 text-primary"></i>Find Work</h6>
                <p class="text-muted small">Browse the latest job postings and submit your proposals.</p>
                <a href="<?php echo e(route('jobs.index')); ?>" class="btn btn-primary btn-sm w-100">Browse Jobs</a>
                <a href="<?php echo e(route('proposals.my')); ?>" class="btn btn-outline-secondary btn-sm w-100 mt-2">My Proposals</a>
            </div>
        </div>
    </div>
</div>


<?php elseif(auth()->user()->hasRole('job_poster')): ?>
<div class="row g-3 mb-4">
    <?php $__currentLoopData = [
        ['label'=>'Active Jobs','value'=>$stats['active_jobs'],'icon'=>'fa-briefcase','color'=>'primary'],
        ['label'=>'Pending Proposals','value'=>$stats['pending_proposals'],'icon'=>'fa-inbox','color'=>'warning'],
        ['label'=>'Active Contracts','value'=>$stats['active_contracts'],'icon'=>'fa-file-contract','color'=>'info'],
        ['label'=>'Total Spent','value'=>'Nu. '.number_format($stats['total_spent']),'icon'=>'fa-coins','color'=>'danger'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-<?php echo e($s['color']); ?> bg-opacity-10 p-3">
                    <i class="fa <?php echo e($s['icon']); ?> text-<?php echo e($s['color']); ?> fa-lg"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5"><?php echo e($s['value']); ?></div>
                    <div class="text-muted small"><?php echo e($s['label']); ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-plus me-2 text-primary"></i>Post a New Job</h6>
                <p class="text-muted small">Find the right talent for your project across Bhutan.</p>
                <a href="<?php echo e(route('jobs.create')); ?>" class="btn btn-primary">Post a Job</a>
                <a href="<?php echo e(route('jobs.my')); ?>" class="btn btn-outline-secondary ms-2">My Jobs</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-wallet me-2 text-warning"></i>Wallet</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Available Balance</span>
                    <span class="fw-semibold">Nu. <?php echo e(number_format(auth()->user()->wallet?->available_balance ?? 0)); ?></span>
                </div>
                <a href="<?php echo e(route('wallet.deposit.form')); ?>" class="btn btn-success btn-sm me-2">Deposit Funds</a>
                <a href="<?php echo e(route('wallet.index')); ?>" class="btn btn-outline-secondary btn-sm">View Wallet</a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\dashboard\index.blade.php ENDPATH**/ ?>