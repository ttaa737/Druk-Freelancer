<?php $__env->startSection('title', 'Verification Document Review'); ?>

<?php
use Illuminate\Support\Facades\Storage;
?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa fa-file-alt text-primary me-2"></i>Verification Document Review</h4>
        <p class="text-muted small mb-0">Review and verify user submitted documents</p>
    </div>
    <a href="<?php echo e(route('admin.verifications.index')); ?>" class="btn btn-sm btn-outline-secondary">
        <i class="fa fa-arrow-left me-1"></i> Back to Queue
    </a>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="fa fa-check-circle me-2"></i><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light d-flex align-items-center justify-content-between">
                <span class="fw-semibold"><i class="fa fa-file-image me-1"></i>Document Preview</span>
                <span class="badge bg-<?php echo e(match($document->status){ 'approved'=>'success','pending'=>'warning','rejected'=>'danger', default=>'secondary'}); ?> px-3 py-2">
                    <i class="fa fa-<?php echo e(match($document->status){ 'approved'=>'check-circle','pending'=>'clock','rejected'=>'times-circle', default=>'question'}); ?> me-1"></i>
                    <?php echo e(ucfirst($document->status)); ?>

                </span>
            </div>
            <div class="card-body p-0">
                <?php if($document->file_path): ?>
                    <?php
                        $ext = strtolower(pathinfo($document->original_name, PATHINFO_EXTENSION));
                        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    ?>
                    <?php if(in_array($ext, $imageExts)): ?>
                    <div class="text-center p-3 bg-dark">
                        <img src="<?php echo e(asset('storage/' . $document->file_path)); ?>" 
                             class="img-fluid rounded" 
                             style="max-height:600px" 
                             alt="Document">
                    </div>
                    <div class="p-3 bg-light text-center">
                        <a href="<?php echo e(asset('storage/' . $document->file_path)); ?>" 
                           class="btn btn-sm btn-primary" 
                           target="_blank">
                            <i class="fa fa-external-link-alt me-1"></i> Open Full Screen
                        </a>
                        <a href="<?php echo e(asset('storage/' . $document->file_path)); ?>" 
                           class="btn btn-sm btn-outline-secondary" 
                           download>
                            <i class="fa fa-download me-1"></i> Download
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fa fa-file-pdf fa-5x text-danger mb-3"></i>
                        <div class="mb-3">
                            <h6 class="fw-bold"><?php echo e($document->original_name); ?></h6>
                            <p class="text-muted">PDF document - Click below to view</p>
                        </div>
                        <a href="<?php echo e(asset('storage/' . $document->file_path)); ?>" 
                           class="btn btn-primary" 
                           target="_blank">
                            <i class="fa fa-file-alt me-1"></i> View Document (<?php echo e(strtoupper($ext)); ?>)
                        </a>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light fw-semibold">
                <i class="fa fa-info-circle me-1"></i>Document Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Document Type</div>
                        <div class="fw-semibold">
                            <?php switch($document->document_type):
                                case ('cid'): ?> 🆔 Citizenship ID (CID) <?php break; ?>
                                <?php case ('license'): ?> 📜 Professional License <?php break; ?>
                                <?php case ('brn'): ?> 🏢 Business Registration Number <?php break; ?>
                                <?php case ('education'): ?> 🎓 Education Certificate <?php break; ?>
                                <?php case ('tax_certificate'): ?> 💼 Tax Clearance Certificate <?php break; ?>
                                <?php default: ?> <?php echo e(ucfirst($document->document_type)); ?>

                            <?php endswitch; ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Document Number</div>
                        <div class="fw-semibold font-monospace"><?php echo e($document->document_number ?? 'Not Provided'); ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Original Filename</div>
                        <div class="small fw-semibold text-truncate"><?php echo e($document->original_name); ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">File Size</div>
                        <div class="fw-semibold"><?php echo e(number_format(Storage::size('public/' . $document->file_path) / 1024, 2)); ?> KB</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Submitted On</div>
                        <div class="fw-semibold"><?php echo e($document->created_at->format('d M Y, h:i A')); ?></div>
                        <div class="text-muted" style="font-size:11px"><?php echo e($document->created_at->diffForHumans()); ?></div>
                    </div>
                    <?php if($document->reviewed_at): ?>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Reviewed On</div>
                        <div class="fw-semibold"><?php echo e($document->reviewed_at->format('d M Y, h:i A')); ?></div>
                        <div class="text-muted" style="font-size:11px">by <?php echo e($document->reviewer?->name ?? 'Admin'); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if($document->admin_notes): ?>
                    <div class="col-12">
                        <div class="text-muted small mb-1">Admin Notes</div>
                        <div class="p-2 bg-info bg-opacity-10 rounded"><?php echo e($document->admin_notes); ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if($document->rejection_reason): ?>
                    <div class="col-12">
                        <div class="text-muted small mb-1">Rejection Reason</div>
                        <div class="p-2 bg-danger bg-opacity-10 rounded text-danger fw-semibold">
                            <i class="fa fa-exclamation-triangle me-1"></i><?php echo e($document->rejection_reason); ?>

                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <?php if($document->isPending()): ?>
        <div class="card shadow-sm border-warning">
            <div class="card-header bg-warning bg-opacity-10 fw-semibold">
                <i class="fa fa-gavel me-1"></i>Review Actions
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Carefully verify the document before approving or rejecting. Your decision will be logged and the user will be notified.</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-success h-100">
                            <div class="card-body">
                                <h6 class="text-success fw-bold mb-2">
                                    <i class="fa fa-check-circle me-1"></i> Approve Document
                                </h6>
                                <form method="POST" action="<?php echo e(route('admin.verifications.approve', $document)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Notes (optional)</label>
                                        <textarea name="notes" class="form-control form-control-sm" rows="2" 
                                                  placeholder="Optional notes for internal record..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this document?')">
                                        <i class="fa fa-check me-1"></i> Approve & Verify
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-danger h-100">
                            <div class="card-body">
                                <h6 class="text-danger fw-bold mb-2">
                                    <i class="fa fa-times-circle me-1"></i> Reject Document
                                </h6>
                                <form method="POST" action="<?php echo e(route('admin.verifications.reject', $document)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Rejection Reason <span class="text-danger">*</span></label>
                                        <textarea name="reason" class="form-control form-control-sm" rows="2" required
                                                  placeholder="Why is this document being rejected? Be specific..."></textarea>
                                        <small class="text-muted">The user will see this message</small>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this document?')">
                                        <i class="fa fa-times me-1"></i> Reject Document
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light fw-semibold">
                <i class="fa fa-user me-1"></i>User Information
            </div>
            <div class="card-body text-center">
                <img src="<?php echo e($document->user?->avatar_url ?? asset('images/default-avatar.png')); ?>" 
                     class="rounded-circle mb-3 border" 
                     style="width:80px;height:80px;object-fit:cover" 
                     alt="User avatar">
                <h6 class="fw-bold mb-1"><?php echo e($document->user?->name); ?></h6>
                <div class="text-muted small mb-2"><?php echo e($document->user?->email); ?></div>
                <?php if($document->user): ?>
                <div class="d-flex gap-2 justify-content-center mb-3">
                    <span class="badge bg-<?php echo e($document->user->role === 'freelancer' ? 'info' : 'warning'); ?>">
                        <?php echo e(ucfirst($document->user->role)); ?>

                    </span>
                    <span class="badge bg-<?php echo e(match($document->user->verification_status ?? 'unverified'){ 'verified'=>'success','pending'=>'warning', default=>'secondary'}); ?>">
                        <?php echo e(ucfirst($document->user->verification_status ?? 'Unverified')); ?>

                    </span>
                </div>
                <a href="<?php echo e(route('admin.users.show', $document->user)); ?>" class="btn btn-sm btn-primary w-100 mb-2">
                    <i class="fa fa-eye me-1"></i>View Full Profile
                </a>
                <a href="<?php echo e(route('profile.show', $document->user)); ?>" class="btn btn-sm btn-outline-secondary w-100" target="_blank">
                    <i class="fa fa-external-link-alt me-1"></i>Public Profile
                </a>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($document->user): ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light fw-semibold small">
                <i class="fa fa-chart-bar me-1"></i>User Activity
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span class="small">Member Since</span>
                    <strong class="small"><?php echo e($document->user->created_at->format('M Y')); ?></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="small">Phone Verified</span>
                    <strong class="small"><?php echo e($document->user->phone_verified_at ? '✅ Yes' : '❌ No'); ?></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="small">Email Verified</span>
                    <strong class="small"><?php echo e($document->user->email_verified ? '✅ Yes' : '❌ No'); ?></strong>
                </li>
            </ul>
        </div>
        <?php endif; ?>

        
        <?php if($document->user): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-light fw-semibold small">
                <i class="fa fa-folder me-1"></i>Other Submitted Documents
            </div>
            <ul class="list-group list-group-flush">
                <?php $__empty_1 = true; $__currentLoopData = $document->user->verificationDocuments()->where('id', '!=', $document->id)->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="list-group-item py-2">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="small fw-semibold"><?php echo e(strtoupper(str_replace('_', ' ', $doc->document_type))); ?></div>
                            <div class="text-muted" style="font-size:11px"><?php echo e($doc->created_at->format('d M Y')); ?></div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-<?php echo e(match($doc->status){ 'approved'=>'success','pending'=>'warning','rejected'=>'danger', default=>'secondary'}); ?>" style="font-size:9px">
                                <?php echo e(ucfirst($doc->status)); ?>

                            </span>
                            <br>
                            <a href="<?php echo e(route('admin.verifications.show', $doc)); ?>" class="small">View</a>
                        </div>
                    </div>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="list-group-item text-muted small py-3 text-center">
                    <i class="fa fa-inbox me-1"></i>No other documents
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\admin\verifications\show.blade.php ENDPATH**/ ?>