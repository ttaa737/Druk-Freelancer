<?php $__env->startSection('title', 'Dispute #' . $dispute->id); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Dispute Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-1"><?php echo e($dispute->subject); ?></h5>
                        <small class="text-muted">Contract: <?php echo e($dispute->contract?->title); ?> · Raised <?php echo e($dispute->created_at->diffForHumans()); ?></small>
                    </div>
                    <span class="badge fs-6 bg-<?php echo e(match($dispute->status) { 'open'=>'warning','under_review'=>'info','resolved'=>'success','closed'=>'secondary', default=>'secondary'}); ?>"><?php echo e(ucfirst(str_replace('_',' ',$dispute->status))); ?></span>
                </div>
                <hr>
                <p class="text-dark mb-0" style="white-space:pre-line"><?php echo e($dispute->description); ?></p>
                <?php if($dispute->resolution_note): ?>
                <div class="alert alert-info mt-3 mb-0"><strong>Resolution:</strong> <?php echo e($dispute->resolution_note); ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Evidence Files -->
        <?php if($dispute->evidence && $dispute->evidence->count()): ?>
        <div class="card mb-4">
            <div class="card-header fw-bold">Evidence Files</div>
            <ul class="list-group list-group-flush">
                <?php $__currentLoopData = $dispute->evidence; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item d-flex align-items-center gap-3">
                    <i class="fa fa-file text-secondary"></i>
                    <div class="flex-grow-1">
                        <div class="small fw-semibold"><?php echo e($file->original_name ?? basename($file->file_path)); ?></div>
                        <div class="text-muted" style="font-size:11px">Uploaded by <?php echo e($file->submittedBy?->name); ?> · <?php echo e($file->created_at->format('d M Y')); ?></div>
                    </div>
                    <a href="<?php echo e(Storage::url($file->file_path)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Add Evidence -->
        <?php if(in_array($dispute->status, ['open','under_review'])): ?>
        <div class="card mb-4">
            <div class="card-header fw-bold">Add Evidence</div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('disputes.evidence', $dispute)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2">
                        <input type="file" name="files[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.zip" required>
                    </div>
                    <button class="btn btn-sm btn-primary">Upload</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Comment Thread -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Comments</div>
            <?php $__empty_1 = true; $__currentLoopData = $dispute->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card-body border-bottom">
                <div class="d-flex gap-3">
                    <img src="<?php echo e($comment->user?->avatar_url ? Storage::url($comment->user->avatar_url) : asset('img/default-avatar.png')); ?>" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;">
                    <div>
                        <div class="d-flex gap-2 align-items-center mb-1">
                            <span class="fw-semibold small"><?php echo e($comment->user?->name); ?></span>
                            <?php if($comment->user?->hasRole('admin')): ?><span class="badge bg-danger" style="font-size:9px">Admin</span><?php endif; ?>
                            <small class="text-muted"><?php echo e($comment->created_at->diffForHumans()); ?></small>
                        </div>
                        <p class="mb-0 small"><?php echo e($comment->comment); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card-body text-muted small text-center">No comments yet.</div>
            <?php endif; ?>
            <?php if(in_array($dispute->status, ['open','under_review'])): ?>
            <div class="card-body pt-0">
                <form method="POST" action="<?php echo e(route('disputes.comment', $dispute)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="input-group mt-3">
                        <input type="text" name="comment" class="form-control" placeholder="Add a comment..." required>
                        <button class="btn btn-primary">Post</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header fw-bold">Dispute Info</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Status</span><span class="badge bg-<?php echo e(match($dispute->status) { 'open'=>'warning','under_review'=>'info','resolved'=>'success','closed'=>'secondary', default=>'secondary'}); ?>"><?php echo e(ucfirst(str_replace('_',' ',$dispute->status))); ?></span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Raised By</span><span class="small"><?php echo e($dispute->raisedBy?->name); ?></span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Assigned To</span><span class="small"><?php echo e($dispute->assignedAdmin?->name ?? '—'); ?></span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Contract</span><a href="<?php echo e(route('contracts.show', $dispute->contract)); ?>" class="small text-primary">View</a></li>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/disputes/show.blade.php ENDPATH**/ ?>