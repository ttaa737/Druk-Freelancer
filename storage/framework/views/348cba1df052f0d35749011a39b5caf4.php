
<?php $__env->startSection('title', 'My Wallet'); ?>
<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Wallet</li>
    </ol>
</nav>

<div class="row g-3">
    <div class="col-lg-8">
        
        <div class="card shadow-sm mb-3">
            <div class="card-body p-4" style="border-bottom: 3px solid var(--bs-primary);">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Your Wallet ID</small>
                            <div class="d-flex align-items-center gap-2">
                                <code class="fs-6 fw-semibold" style="color: #1a3a5c; letter-spacing: 1px;"><?php echo e(auth()->user()->wallet_id); ?></code>
                                <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard('<?php echo e(auth()->user()->wallet_id); ?>')">
                                    <i class="fa fa-copy me-1"></i>Copy
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fa fa-info-circle me-1"></i>Share this ID with other users to receive payments
                            </small>
                        </div>
                    </div>
                    <div class="col-auto text-center">
                        <div class="mb-2">
                            <small class="text-muted d-block">Total Balance</small>
                            <h3 class="text-primary mb-0">Nu. <?php echo e(number_format(auth()->user()->wallet->available_balance + auth()->user()->wallet->escrow_balance, 2)); ?></h3>
                        </div>
                        <span class="badge bg-success">
                            <i class="fa fa-check-circle me-1"></i>Active
                        </span>
                    </div>
                </div>
            </div>

            
            <div class="card-body border-top">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fa fa-wallet fa-2x text-primary mb-2"></i>
                            <small class="text-muted d-block">Available</small>
                            <strong class="text-success">Nu. <?php echo e(number_format(auth()->user()->wallet->available_balance, 2)); ?></strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fa fa-lock fa-2x text-warning mb-2"></i>
                            <small class="text-muted d-block">In Escrow</small>
                            <strong class="text-warning">Nu. <?php echo e(number_format(auth()->user()->wallet->escrow_balance, 2)); ?></strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fa fa-history fa-2x text-info mb-2"></i>
                            <small class="text-muted d-block">Total Earned</small>
                            <strong class="text-info">Nu. <?php echo e(number_format(auth()->user()->wallet->total_earned, 2)); ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card-footer bg-light">
                <div class="d-grid gap-2 gap-md-0 row">
                    <div class="col-md-6">
                        <a href="<?php echo e(route('wallet.send')); ?>" class="btn btn-primary btn-sm w-100">
                            <i class="fa fa-arrow-right me-1"></i>Send to Wallet
                        </a>
                    </div>
                    <?php if(auth()->user()->wallet->available_balance >= 500): ?>
                    <div class="col-md-6">
                        <a href="<?php echo e(route('wallet.withdraw')); ?>" class="btn btn-success btn-sm w-100">
                            <i class="fa fa-arrow-up me-1"></i>Withdraw
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fa fa-history me-2"></i>Transaction History</h6>
                    <form method="GET" action="<?php echo e(route('wallet.transactions')); ?>" class="d-flex gap-2">
                        <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <option value="payment" <?php echo e(request('type') === 'payment' ? 'selected' : ''); ?>>Payments</option>
                            <option value="escrow_release" <?php echo e(request('type') === 'escrow_release' ? 'selected' : ''); ?>>Escrow Release</option>
                            <option value="transfer" <?php echo e(request('type') === 'transfer' ? 'selected' : ''); ?>>Transfers</option>
                            <option value="withdrawal" <?php echo e(request('type') === 'withdrawal' ? 'selected' : ''); ?>>Withdrawals</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <?php if(auth()->user()->transactions->count()): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th width="120">Amount</th>
                                <th width="120">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = auth()->user()->transactions()->latest()->paginate(15); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#transactionDetail<?php echo e($transaction->id); ?>">
                                <td class="small"><?php echo e($transaction->created_at->format('d M Y, h:i A')); ?></td>
                                <td>
                                    <?php
                                        $typeConfig = [
                                            'payment' => ['icon' => 'credit-card', 'label' => 'Payment', 'color' => 'primary'],
                                            'escrow_release' => ['icon' => 'unlock', 'label' => 'Escrow Release', 'color' => 'success'],
                                            'platform_fee' => ['icon' => 'percent', 'label' => 'Platform Fee', 'color' => 'warning'],
                                            'transfer' => ['icon' => 'exchange-alt', 'label' => 'Transfer', 'color' => 'info'],
                                            'withdrawal' => ['icon' => 'arrow-up', 'label' => 'Withdrawal', 'color' => 'secondary'],
                                        ];
                                        $config = $typeConfig[$transaction->type] ?? ['icon' => 'question', 'label' => ucfirst($transaction->type), 'color' => 'secondary'];
                                    ?>
                                    <span class="badge bg-<?php echo e($config['color']); ?>">
                                        <i class="fa fa-<?php echo e($config['icon']); ?> me-1"></i><?php echo e($config['label']); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($transaction->milestone): ?>
                                        Milestone: <?php echo e($transaction->milestone->title); ?>

                                    <?php elseif($transaction->contract): ?>
                                        Contract: <?php echo e($transaction->contract->contract_number); ?>

                                    <?php else: ?>
                                        <?php echo e($transaction->notes); ?>

                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong class="<?php echo e($transaction->amount > 0 ? 'text-success' : 'text-danger'); ?>">
                                        <?php echo e($transaction->amount > 0 ? '+' : ''); ?>Nu. <?php echo e(number_format($transaction->amount, 2)); ?>

                                    </strong>
                                </td>
                                <td>
                                    <?php
                                        $statusConfig = [
                                            'completed' => ['class' => 'success', 'icon' => 'check-circle'],
                                            'pending' => ['class' => 'warning text-dark', 'icon' => 'clock'],
                                            'failed' => ['class' => 'danger', 'icon' => 'times-circle'],
                                        ];
                                        $config = $statusConfig[$transaction->status] ?? ['class' => 'secondary', 'icon' => 'circle'];
                                    ?>
                                    <span class="badge bg-<?php echo e($config['class']); ?>">
                                        <i class="fa fa-<?php echo e($config['icon']); ?> me-1"></i><?php echo e(ucfirst($transaction->status)); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="card-footer bg-light">
                    <?php echo e(auth()->user()->transactions()->paginate(15)->links()); ?>

                </div>
                <?php else: ?>
                <div class="alert alert-info m-3 mb-0">
                    <i class="fa fa-info-circle me-2"></i>
                    No transactions yet. Start accepting freelance projects to build your transaction history!
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-info-circle me-2"></i>Wallet Information</h6>
            </div>
            <div class="card-body small">
                <div class="mb-3">
                    <strong>Account Status</strong><br>
                    <span class="badge bg-success">Active</span>
                </div>
                <div class="mb-3">
                    <strong>Currency</strong><br>
                    <span>Bhutanese Ngultrum (Nu.)</span>
                </div>
                <div class="mb-0">
                    <strong>Minimum Withdrawal</strong><br>
                    <span>Nu. 500</span>
                </div>
            </div>
        </div>

        
        <div class="alert alert-light border">
            <h6 class="fw-bold mb-2"><i class="fa fa-shield-alt text-primary me-2"></i>Security Tips</h6>
            <ul class="small mb-0 ps-3">
                <li>Never share your wallet ID in personal conversations</li>
                <li>Verify recipient wallet ID before sending funds</li>
                <li>Review transaction details carefully</li>
                <li>All transactions are irreversible once processed</li>
            </ul>
        </div>

        
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-question-circle me-2"></i>Need Help?</h6>
            </div>
            <div class="card-body small">
                <p class="mb-2">Questions about wallet transactions?</p>
                <a href="<?php echo e(route('help.wallet')); ?>" class="btn btn-outline-primary btn-sm w-100 mb-2">
                    <i class="fa fa-book me-1"></i>View Documentation
                </a>
                <a href="<?php echo e(route('contact.support')); ?>" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fa fa-envelope me-1"></i>Contact Support
                </a>
            </div>
        </div>
    </div>
</div>


<?php $__currentLoopData = auth()->user()->transactions()->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="transactionDetail<?php echo e($transaction->id); ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold"><i class="fa fa-receipt me-2"></i>Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small">
                <div class="mb-3">
                    <strong class="d-block text-muted mb-1">Reference Number</strong>
                    <code><?php echo e($transaction->transaction_ref); ?></code>
                </div>

                <div class="mb-3">
                    <strong class="d-block text-muted mb-1">Date & Time</strong>
                    <?php echo e($transaction->created_at->format('d M Y, h:i A')); ?>

                </div>

                <div class="mb-3">
                    <strong class="d-block text-muted mb-1">Type</strong>
                    <span class="badge bg-primary"><?php echo e(ucfirst(str_replace('_', ' ', $transaction->type))); ?></span>
                </div>

                <div class="alert alert-light border">
                    <div class="row g-2">
                        <div class="col-6">
                            <strong class="d-block text-muted mb-1">Gross Amount</strong>
                            <strong>Nu. <?php echo e(number_format($transaction->amount, 2)); ?></strong>
                        </div>
                        <?php if($transaction->fee > 0): ?>
                        <div class="col-6">
                            <strong class="d-block text-muted mb-1">Fee</strong>
                            <strong class="text-danger">Nu. <?php echo e(number_format($transaction->fee, 2)); ?></strong>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if($transaction->net_amount): ?>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between">
                        <strong>Net Amount</strong>
                        <strong class="text-success">Nu. <?php echo e(number_format($transaction->net_amount, 2)); ?></strong>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <strong class="d-block text-muted mb-1">Status</strong>
                    <?php
                        $statusConfig = [
                            'completed' => ['class' => 'success', 'icon' => 'check-circle'],
                            'pending' => ['class' => 'warning text-dark', 'icon' => 'clock'],
                            'failed' => ['class' => 'danger', 'icon' => 'times-circle'],
                        ];
                        $config = $statusConfig[$transaction->status] ?? ['class' => 'secondary', 'icon' => 'circle'];
                    ?>
                    <span class="badge bg-<?php echo e($config['class']); ?>">
                        <i class="fa fa-<?php echo e($config['icon']); ?> me-1"></i><?php echo e(ucfirst($transaction->status)); ?>

                    </span>
                </div>

                <?php if($transaction->notes): ?>
                <div class="mb-0">
                    <strong class="d-block text-muted mb-1">Notes</strong>
                    <p class="mb-0 text-muted"><?php echo e($transaction->notes); ?></p>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Wallet ID copied to clipboard!');
    }).catch(() => {
        const input = document.createElement('input');
        input.value = text;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        alert('Wallet ID copied!');
    });
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/wallet/transactions.blade.php ENDPATH**/ ?>