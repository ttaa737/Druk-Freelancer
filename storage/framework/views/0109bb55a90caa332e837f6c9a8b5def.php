<?php $__env->startSection('title', 'Notifications'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="fa fa-bell me-2"></i>Notifications</h5>
    <?php if($notifications->where('read_at', null)->count()): ?>
    <form method="POST" action="<?php echo e(route('notifications.read-all')); ?>">
        <?php echo csrf_field(); ?>
        <button class="btn btn-sm btn-outline-secondary">Mark all as read</button>
    </form>
    <?php endif; ?>
</div>
<?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="card mb-2 <?php echo e(is_null($notif->read_at) ? 'border-primary border-start border-3' : ''); ?>">
    <div class="card-body py-3 px-4">
        <div class="d-flex justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-<?php echo e(is_null($notif->read_at) ? 'primary' : 'light'); ?> text-<?php echo e(is_null($notif->read_at) ? 'white' : 'secondary'); ?> d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;">
                    <i class="fa fa-<?php echo e($notif->data['icon'] ?? 'bell'); ?> small"></i>
                </div>
                <div>
                    <p class="mb-0 small <?php echo e(is_null($notif->read_at) ? 'fw-semibold' : ''); ?>"><?php echo e($notif->data['message'] ?? 'Notification'); ?></p>
                    <small class="text-muted"><?php echo e($notif->created_at->diffForHumans()); ?></small>
                </div>
            </div>
            <div class="d-flex gap-1 flex-shrink-0">
                <?php if(is_null($notif->read_at)): ?>
                <form method="POST" action="<?php echo e(route('notifications.read', $notif->id)); ?>"><?php echo csrf_field(); ?> <button class="btn btn-sm btn-outline-primary" title="Mark read"><i class="fa fa-check"></i></button></form>
                <?php endif; ?>
                <?php if(isset($notif->data['url'])): ?>
                <a href="<?php echo e($notif->data['url']); ?>" class="btn btn-sm btn-outline-secondary">View</a>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('notifications.destroy', $notif->id)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fa fa-times"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="text-center py-5 text-muted">
    <i class="fa fa-bell-slash fa-3x mb-3 opacity-25"></i>
    <p>You're all caught up!</p>
</div>
<?php endif; ?>
<div class="mt-3"><?php echo e($notifications->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/notifications/index.blade.php ENDPATH**/ ?>