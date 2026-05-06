<?php $__env->startSection('title', 'Dispute #' . $dispute->id); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">Dispute: <?php echo e($dispute->subject); ?></h4>
    <span class="badge fs-6 bg-<?php echo e(match($dispute->status){ 'open'=>'warning','under_review'=>'info','resolved'=>'success','closed'=>'secondary', default=>'secondary'}); ?>"><?php echo e(ucfirst(str_replace('_',' ',$dispute->status))); ?></span>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Description -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Description</div>
            <div class="card-body"><p class="mb-0" style="white-space:pre-line"><?php echo e($dispute->description); ?></p></div>
        </div>

        <!-- Evidence -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Evidence Files</div>
            <?php $__empty_1 = true; $__currentLoopData = $dispute->evidence; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="list-group-item d-flex align-items-center justify-content-between p-3 border-bottom gap-3">
                <div><i class="fa fa-file text-secondary me-2"></i><span class="small"><?php echo e($file->original_name ?? basename($file->file_path)); ?></span></div>
                <a href="<?php echo e(Storage::url($file->file_path)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card-body text-muted small text-center">No evidence files.</div>
            <?php endif; ?>
        </div>

        <!-- Comments -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Comments</div>
            <?php $__currentLoopData = $dispute->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card-body border-bottom">
                <div class="d-flex gap-3">
                    <img src="<?php echo e($comment->user?->avatar_url ? Storage::url($comment->user->avatar_url) : asset('img/default-avatar.png')); ?>" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;">
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="card-body pt-2">
                <form method="POST" action="<?php echo e(route('disputes.comment', $dispute)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="input-group">
                        <input type="text" name="comment" class="form-control form-control-sm" placeholder="Add admin comment...">
                        <button class="btn btn-sm btn-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resolve Form -->
        <?php if(in_array($dispute->status, ['open','under_review'])): ?>
        <div class="card border-danger">
            <div class="card-header fw-bold text-danger">Resolve Dispute</div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.disputes.resolve', $dispute)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Resolution Type</label>
                        <select name="resolution" class="form-select" id="resolutionSelect" required>
                            <option value="favour_poster">In Favour of Job Poster (Refund escrow to poster)</option>
                            <option value="favour_freelancer">In Favour of Freelancer (Release escrow to freelancer)</option>
                            <option value="split">Split (Custom percentage)</option>
                        </select>
                    </div>
                    <div id="splitField" class="mb-3 d-none">
                        <label class="form-label small fw-semibold">Freelancer gets (%)</label>
                        <input type="number" name="freelancer_percent" class="form-control" min="0" max="100" value="50" placeholder="50">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Resolution Note</label>
                        <textarea name="resolution_note" class="form-control" rows="3" required placeholder="Explain the decision..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Confirm Resolution</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header fw-bold">Dispute Info</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Raised By</span><span class="small"><?php echo e($dispute->raisedBy?->name); ?></span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Assigned To</span><span class="small"><?php echo e($dispute->assignedAdmin?->name ?? '—'); ?></span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Contract</span><a href="<?php echo e(route('contracts.show', $dispute->contract)); ?>" class="small text-primary">View</a></li>
                <?php if($dispute->resolution_notes): ?><li class="list-group-item"><div class="text-muted small">Resolution</div><div class="small"><?php echo e($dispute->resolution_notes); ?></div></li><?php endif; ?>
            </ul>
        </div>
        <?php if(!$dispute->assigned_admin_id || $dispute->status === 'open'): ?>
        <form method="POST" action="<?php echo e(route('admin.disputes.assign', $dispute)); ?>"><?php echo csrf_field(); ?> <button class="btn btn-outline-secondary w-100 mb-2">Assign to Me</button></form>
        <?php endif; ?>
    </div>
</div>
<script>
document.getElementById('resolutionSelect')?.addEventListener('change', function(){
    document.getElementById('splitField').classList.toggle('d-none', this.value !== 'split');
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\admin\disputes\show.blade.php ENDPATH**/ ?>