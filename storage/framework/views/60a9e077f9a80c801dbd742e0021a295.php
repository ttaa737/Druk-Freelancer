<?php $__env->startSection('title', 'Messages'); ?>
<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Messages</li>
    </ol>
</nav>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="fa fa-comments me-2"></i>Messages
            </h5>
            <span class="badge bg-primary">
                <?php echo e($conversations->count()); ?> Conversation<?php echo e($conversations->count() !== 1 ? 's' : ''); ?>

            </span>
        </div>
    </div>

    <div class="row g-0" style="min-height: 500px; max-height: calc(100vh - 280px);">
        <!-- Conversation List -->
        <div class="col-lg-5 col-xl-4 border-end" style="max-height: calc(100vh - 280px); overflow-y: auto;">
            <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php 
                $other = $conv->poster_id === auth()->id() ? $conv->freelancer : $conv->poster;
                $unreadCount = $conv->messages->where('sender_id', '!=', auth()->id())->where('is_read', false)->count();
            ?>
            <a href="<?php echo e(route('messages.show', $conv)); ?>" 
               class="d-flex align-items-start gap-3 p-3 border-bottom text-decoration-none position-relative <?php echo e(isset($active) && $active->id === $conv->id ? 'bg-light' : ''); ?>"
               style="transition: background-color 0.2s;">
                
                <div class="position-relative flex-shrink-0">
                    <img src="<?php echo e($other?->avatar_url ?? asset('img/default-avatar.png')); ?>" 
                         class="rounded-circle" 
                         width="48" 
                         height="48"
                         style="object-fit:cover;">
                    
                    <?php if($unreadCount > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          style="font-size: 10px;">
                        <?php echo e($unreadCount > 9 ? '9+' : $unreadCount); ?>

                    </span>
                    <?php endif; ?>
                </div>
                
                <div class="flex-grow-1 overflow-hidden">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="fw-semibold text-dark <?php echo e($unreadCount > 0 ? 'fw-bold' : ''); ?>">
                            <?php echo e($other?->name ?? 'Unknown User'); ?>

                        </span>
                        <?php if($conv->last_message_at): ?>
                        <small class="text-muted flex-shrink-0 ms-2">
                            <?php echo e($conv->last_message_at->diffForHumans(null, true)); ?>

                        </small>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($conv->job): ?>
                    <p class="text-muted small mb-1">
                        <i class="fa fa-briefcase me-1"></i><?php echo e(Str::limit($conv->job->title, 35)); ?>

                    </p>
                    <?php endif; ?>
                    
                    <p class="text-muted mb-0 text-truncate small <?php echo e($unreadCount > 0 ? 'fw-semibold' : ''); ?>"
                       style="max-width: 250px;">
                        <?php if($conv->latestMessage): ?>
                            <?php if($conv->latestMessage->sender_id === auth()->id()): ?>
                                <span class="text-muted">You:</span>
                            <?php endif; ?>
                            <?php echo e($conv->latestMessage->body ? Str::limit($conv->latestMessage->body, 40) : '📎 Attachment'); ?>

                        <?php else: ?>
                            <em>No messages yet</em>
                        <?php endif; ?>
                    </p>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-5 text-center">
                <i class="fa fa-inbox fa-3x text-muted mb-3" style="opacity: 0.3"></i>
                <h6 class="text-muted mb-2">No conversations yet</h6>
                <p class="text-muted small mb-3">
                    Start messaging freelancers or clients through job proposals and contracts.
                </p>
                <a href="<?php echo e(route('jobs.index')); ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-search me-1"></i>Browse Jobs
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Empty state for desktop (when no conversation is selected) -->
        <div class="col-lg-7 col-xl-8 d-none d-lg-flex align-items-center justify-content-center flex-column text-muted" style="min-height: 500px;">
            <i class="fa fa-comments fa-4x mb-3" style="opacity: 0.2"></i>
            <h6 class="text-muted">Select a conversation to start messaging</h6>
            <p class="text-muted small">Your conversations will appear on the left</p>
        </div>
    </div>
</div>

<style>
/* Conversation item hover effect */
.border-bottom:hover {
    background-color: #f8f9fa !important;
}

/* Responsive heights */
@media (max-width: 991px) {
    .card .row {
        min-height: 400px !important;
        max-height: calc(100vh - 200px) !important;
    }
    
    .col-lg-5.border-end {
        max-height: calc(100vh - 200px) !important;
    }
}

@media (min-width: 992px) {
    .card .row {
        min-height: 500px;
        max-height: calc(100vh - 280px);
    }
}

/* Ensure proper spacing */
.card.shadow-sm {
    margin-bottom: 2rem;
}

/* Custom scrollbar for conversation list */
.col-lg-5.border-end::-webkit-scrollbar {
    width: 6px;
}

.col-lg-5.border-end::-webkit-scrollbar-track {
    background: transparent;
}

.col-lg-5.border-end::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.15);
    border-radius: 10px;
}

.col-lg-5.border-end::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.25);
}
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/messages/index.blade.php ENDPATH**/ ?>