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
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">Evidence Files</span>
                <span class="badge bg-light text-dark border"><?php echo e($dispute->evidence->count()); ?> files</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php $__empty_1 = true; $__currentLoopData = $dispute->evidence; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $downloadUrl = route('admin.disputes.evidence.download', ['dispute' => $dispute, 'evidence' => $file]);
                        $inlineUrl = $downloadUrl . '?inline=1';
                        $fileName = $file->original_name ?? basename($file->file_path);
                        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
                        $videoExtensions = ['mp4', 'webm', 'ogg', 'mov'];
                        $isImage = in_array($extension, $imageExtensions, true);
                        $isVideo = in_array($extension, $videoExtensions, true);
                        $isPdf = $extension === 'pdf';
                    ?>
                    <div class="col-12">
                        <div class="border rounded-3 bg-white overflow-hidden">
                            <div class="p-3 border-bottom bg-light-subtle">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-semibold"><?php echo e($fileName); ?></div>
                                        <div class="small text-muted mt-1">
                                            Uploaded by <?php echo e($file->submittedBy?->name ?? 'Unknown'); ?> • <?php echo e($file->created_at?->format('d M Y, h:i A')); ?>

                                        </div>
                                        <?php if($file->description): ?>
                                        <div class="small text-muted mt-1"><?php echo e($file->description); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo e($inlineUrl); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-eye me-1"></i>Open Preview
                                        </a>
                                        <a href="<?php echo e($downloadUrl); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-download me-1"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3">
                                <?php if($isImage): ?>
                                <div class="rounded-3 border overflow-hidden bg-light-subtle">
                                    <img src="<?php echo e($inlineUrl); ?>" alt="<?php echo e($fileName); ?>" class="img-fluid w-100" style="max-height:420px; object-fit:contain;" loading="lazy">
                                </div>
                                <?php elseif($isVideo): ?>
                                <div class="rounded-3 border overflow-hidden bg-dark">
                                    <video controls preload="metadata" class="w-100" style="max-height:420px;">
                                        <source src="<?php echo e($inlineUrl); ?>">
                                        Your browser does not support video playback. Use Download instead.
                                    </video>
                                </div>
                                <?php elseif($isPdf): ?>
                                <div class="rounded-3 border overflow-hidden" style="height:520px;">
                                    <iframe src="<?php echo e($inlineUrl); ?>" title="PDF preview for <?php echo e($fileName); ?>" class="w-100 h-100 border-0"></iframe>
                                </div>
                                <?php else: ?>
                                <div class="rounded-3 border bg-light-subtle p-4 text-center text-muted">
                                    <i class="fa fa-file fa-lg d-block mb-2"></i>
                                    Inline preview is not available for .<?php echo e($extension ?: 'file'); ?>. Use Open Preview or Download.
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div class="text-muted small text-center py-3">No evidence files.</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/admin/disputes/show.blade.php ENDPATH**/ ?>