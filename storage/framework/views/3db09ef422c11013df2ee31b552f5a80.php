<?php $__env->startSection('title', 'Verifications'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa fa-shield-alt text-primary me-2"></i>Verification Queue</h4>
        <p class="text-muted small mb-0">Review and approve user verification documents</p>
    </div>
    <div class="d-flex gap-2">
        <?php
            $pendingCount = \App\Models\VerificationDocument::where('status', 'pending')->count();
        ?>
        <?php if($pendingCount > 0): ?>
            <span class="badge bg-warning text-dark px-3 py-2">
                <i class="fa fa-clock me-1"></i><?php echo e($pendingCount); ?> Pending
            </span>
        <?php endif; ?>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fa fa-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card mb-4 shadow-sm">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-3">
                <label class="form-label small fw-semibold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="pending" <?php if(request('status')==='pending' || !request('status')): echo 'selected'; endif; ?>>⏱️ Pending</option>
                    <option value="approved" <?php if(request('status')==='approved'): echo 'selected'; endif; ?>>✅ Approved</option>
                    <option value="rejected" <?php if(request('status')==='rejected'): echo 'selected'; endif; ?>>❌ Rejected</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label small fw-semibold mb-1">Document Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="cid" <?php if(request('type')==='cid'): echo 'selected'; endif; ?>>🆔 CID / Passport</option>
                    <option value="license" <?php if(request('type')==='license'): echo 'selected'; endif; ?>>📜 Professional License</option>
                    <option value="brn" <?php if(request('type')==='brn'): echo 'selected'; endif; ?>>🏢 BRN</option>
                    <option value="education" <?php if(request('type')==='education'): echo 'selected'; endif; ?>>🎓 Education</option>
                    <option value="tax_certificate" <?php if(request('type')==='tax_certificate'): echo 'selected'; endif; ?>>💼 Tax Certificate</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary"><i class="fa fa-filter me-1"></i>Filter</button>
                <a href="<?php echo e(route('admin.verifications.index')); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-redo me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:5%">#</th>
                    <th style="width:25%">User</th>
                    <th style="width:15%">Document Type</th>
                    <th style="width:12%">Document #</th>
                    <th style="width:15%">Submitted</th>
                    <th style="width:10%">Status</th>
                    <th class="text-end" style="width:18%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="<?php echo e($doc->status === 'pending' ? 'table-warning table-warning-subtle' : ''); ?>">
                    <td class="text-muted small"><?php echo e($loop->iteration); ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="<?php echo e($doc->user?->avatar_url ?? asset('images/default-avatar.png')); ?>" 
                                 class="rounded-circle" 
                                 style="width:36px;height:36px;object-fit:cover;"
                                 alt="User avatar">
                            <div>
                                <div class="fw-semibold"><?php echo e($doc->user?->name); ?></div>
                                <div class="text-muted" style="font-size:11px"><?php echo e($doc->user?->email); ?></div>
                                <div class="mt-1">
                                    <span class="badge bg-<?php echo e($doc->user?->role === 'freelancer' ? 'info' : 'warning'); ?>" style="font-size:9px">
                                        <?php echo e(ucfirst($doc->user?->role ?? 'N/A')); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border px-2 py-1">
                            <?php switch($doc->document_type):
                                case ('cid'): ?> 🆔 CID <?php break; ?>
                                <?php case ('license'): ?> 📜 License <?php break; ?>
                                <?php case ('brn'): ?> 🏢 BRN <?php break; ?>
                                <?php case ('education'): ?> 🎓 Education <?php break; ?>
                                <?php case ('tax_certificate'): ?> 💼 Tax Cert <?php break; ?>
                                <?php default: ?> <?php echo e(ucfirst($doc->document_type)); ?>

                            <?php endswitch; ?>
                        </span>
                    </td>
                    <td>
                        <span class="font-monospace small text-muted"><?php echo e($doc->document_number ?? '—'); ?></span>
                    </td>
                    <td>
                        <div class="small"><?php echo e($doc->created_at->format('d M Y')); ?></div>
                        <div class="text-muted" style="font-size:10px"><?php echo e($doc->created_at->format('h:i A')); ?></div>
                        <div class="text-muted" style="font-size:10px"><?php echo e($doc->created_at->diffForHumans()); ?></div>
                    </td>
                    <td>
                        <span class="badge bg-<?php echo e($doc->status === 'approved' ? 'success' : ($doc->status === 'rejected' ? 'danger' : 'warning text-dark')); ?>">
                            <i class="fa fa-<?php echo e($doc->status === 'approved' ? 'check-circle' : ($doc->status === 'rejected' ? 'times-circle' : 'clock')); ?> me-1"></i>
                            <?php echo e(ucfirst($doc->status)); ?>

                        </span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="<?php echo e(route('admin.verifications.show', $doc)); ?>" 
                               class="btn btn-outline-primary"
                               title="View Details">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="<?php echo e(asset('storage/' . $doc->file_path)); ?>" 
                               target="_blank" 
                               class="btn btn-outline-secondary"
                               title="View Document">
                                <i class="fa fa-file-alt"></i>
                            </a>
                        </div>
                        <?php if($doc->status === 'pending'): ?>
                        <div class="btn-group btn-group-sm ms-1">
                            <form method="POST" action="<?php echo e(route('admin.verifications.approve', $doc)); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-success" title="Approve" onclick="return confirm('Approve this document?')">
                                    <i class="fa fa-check"></i>
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#rejectModal<?php echo e($doc->id); ?>"
                                    title="Reject">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>

                        
                        <div class="modal fade" id="rejectModal<?php echo e($doc->id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reject Document</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="<?php echo e(route('admin.verifications.reject', $doc)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-body">
                                            <p class="text-muted small">Provide a clear reason for rejection. The user will see this message.</p>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Rejection Reason <span class="text-danger">*</span></label>
                                                <textarea name="reason" class="form-control" rows="3" required 
                                                          placeholder="e.g., Document is blurred, expired, or doesn't match profile information"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-times me-1"></i>Reject Document
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                            <?php if($doc->rejection_reason): ?>
                                <button class="btn btn-sm btn-outline-info ms-1" 
                                        data-bs-toggle="tooltip" 
                                        title="<?php echo e($doc->rejection_reason); ?>">
                                    <i class="fa fa-info-circle"></i>
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fa fa-inbox fa-3x mb-3 opacity-25"></i>
                        <div>No verification documents found.</div>
                        <small>Documents will appear here when users submit them for verification.</small>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($documents->hasPages()): ?>
    <div class="card-footer bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing <?php echo e($documents->firstItem()); ?> to <?php echo e($documents->lastItem()); ?> of <?php echo e($documents->total()); ?> documents
            </div>
            <div><?php echo e($documents->withQueryString()->links()); ?></div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/admin/verifications/index.blade.php ENDPATH**/ ?>