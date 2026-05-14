<?php $__env->startSection('title', 'Raise a Dispute'); ?>
<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1"><i class="fa fa-gavel me-2 text-danger"></i>Raise a Dispute</h5>
                <p class="text-muted small mb-4">Disputes are reviewed by our admin team. Please provide clear evidence to support your claim.</p>

                <div class="alert alert-warning small mb-4">
                    <i class="fa fa-info-circle me-1"></i>This will freeze the escrow funds until the dispute is resolved. Use this only if you cannot resolve the issue directly with the other party.
                </div>

                <form method="POST" action="<?php echo e(route('disputes.store', $contract)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('subject')); ?>" required>
                        <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" placeholder="Describe the issue in detail..." required><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <?php if($contract->milestones->count()): ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Related Milestone</label>
                        <select name="milestone_id" class="form-select">
                            <option value="">None / Entire Contract</option>
                            <?php $__currentLoopData = $contract->milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ms->id); ?>" <?php if(old('milestone_id') == $ms->id): echo 'selected'; endif; ?>><?php echo e($ms->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Evidence Files <span class="text-muted fw-normal">(Optional)</span></label>
                        <input type="file" name="evidence_files[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.zip">
                        <div class="form-text">Max 5 files, 10MB each. Accepted: PDF, JPG, PNG, ZIP</div>
                    </div>
                    <button type="submit" class="btn btn-danger"><i class="fa fa-gavel me-1"></i>Submit Dispute</button>
                    <a href="<?php echo e(route('contracts.show', $contract)); ?>" class="btn btn-link text-muted">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/disputes/create.blade.php ENDPATH**/ ?>