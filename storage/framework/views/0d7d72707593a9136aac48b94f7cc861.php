<?php $__env->startSection('title', 'Categories & Skills'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Categories & Skills</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="fa fa-plus me-1"></i>Add Category</button>
</div>

<div class="accordion" id="catAccordion">
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="accordion-item mb-2">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#cat<?php echo e($category->id); ?>">
                <?php if($category->icon): ?><i class="<?php echo e($category->icon); ?> me-2"></i><?php endif; ?><?php echo e($category->name); ?> <span class="badge bg-secondary ms-2"><?php echo e($category->skills->count()); ?> skills</span>
            </button>
        </h2>
        <div id="cat<?php echo e($category->id); ?>" class="accordion-collapse collapse">
            <div class="accordion-body">
                <!-- Skills Table -->
                <div class="table-responsive mb-3">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>Skill</th><th class="text-end">Action</th></tr></thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $category->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="small"><?php echo e($skill->name); ?></td>
                                <td class="text-end">
                                    <form method="POST" action="<?php echo e(route('admin.categories.skills.destroy', [$category, $skill])); ?>">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete skill?')"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="2" class="text-muted small text-center">No skills yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Add Skill -->
                <form method="POST" action="<?php echo e(route('admin.categories.skills.store', $category)); ?>" class="d-flex gap-2">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="New skill name" required>
                    <button class="btn btn-sm btn-success">Add Skill</button>
                </form>
                <!-- Edit/Delete Category -->
                <hr>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editCat<?php echo e($category->id); ?>">Edit Category</button>
                    <form method="POST" action="<?php echo e(route('admin.categories.destroy', $category)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete category and all skills?')">Delete Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCat<?php echo e($category->id); ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary bg-opacity-10">
                    <h5 class="modal-title fw-bold"><i class="fa fa-edit me-2 text-secondary"></i>Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?php echo e(route('admin.categories.update', $category)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?php echo e($category->name); ?>" placeholder="e.g., Web Development" required maxlength="100">
                            <small class="text-muted">Max 100 characters</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" placeholder="Brief description of this category..." maxlength="500" rows="3"><?php echo e($category->description ?? ''); ?></textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Max 500 characters</small>
                                <small class="text-muted"><span id="descCharsEdit<?php echo e($category->id); ?>">0</span>/500</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Icon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i id="iconPreviewEdit<?php echo e($category->id); ?>" class="fa <?php echo e($category->icon ?? 'fa-folder'); ?>" style="font-size: 18px;"></i></span>
                                <input type="text" name="icon" id="iconInputEdit<?php echo e($category->id); ?>" class="form-control" value="<?php echo e($category->icon ?? ''); ?>" placeholder="e.g., fa-code, fa-briefcase" maxlength="100">
                            </div>
                            <small class="text-muted d-block mt-1"><a href="https://fontawesome.com/icons" target="_blank">Browse Font Awesome Icons</a> - Use format: fa-icon-name</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Icon preview for each category edit modal
    document.getElementById('iconInputEdit<?php echo e($category->id); ?>')?.addEventListener('input', function(e) {
        const icon = e.target.value || 'fa-folder';
        document.getElementById('iconPreviewEdit<?php echo e($category->id); ?>').className = 'fa ' + icon;
    });
    // Character counter for description
    document.querySelector('#editCat<?php echo e($category->id); ?> textarea[name="description"]')?.addEventListener('input', function(e) {
        document.getElementById('descCharsEdit<?php echo e($category->id); ?>').textContent = e.target.value.length;
    });
    // Initialize character count on modal open
    document.getElementById('editCat<?php echo e($category->id); ?>')?.addEventListener('show.bs.modal', function(e) {
        document.getElementById('descCharsEdit<?php echo e($category->id); ?>').textContent = document.querySelector('#editCat<?php echo e($category->id); ?> textarea[name="description"]')?.value?.length || 0;
    });
    </script>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary bg-opacity-10">
                <h5 class="modal-title fw-bold"><i class="fa fa-plus me-2 text-primary"></i>Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.categories.store')); ?>" id="addCategoryForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="alert alert-info small mb-3">
                        <i class="fa fa-info-circle me-2"></i>
                        Create new job categories to help organize freelance work and skills.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="categoryNameInput" class="form-control" placeholder="e.g., Web Development" required maxlength="100">
                        <small class="text-muted">Max 100 characters</small>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert alert-danger small mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="descriptionInput" class="form-control" placeholder="Brief description of this category..." maxlength="500" rows="3"></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Max 500 characters</small>
                            <small class="text-muted"><span id="descChars">0</span>/500</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Icon (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i id="iconPreview" class="fa fa-folder" style="font-size: 18px;"></i>
                            </span>
                            <input type="text" name="icon" id="iconInput" class="form-control" placeholder="e.g., fa-code, fa-briefcase" maxlength="100">
                        </div>
                        <small class="text-muted d-block mt-1"><a href="https://fontawesome.com/icons" target="_blank">Browse Font Awesome Icons</a> - Use format: fa-icon-name</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-plus me-1"></i>Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Add Category Modal - Icon Preview
document.getElementById('iconInput')?.addEventListener('input', function(e) {
    const icon = e.target.value || 'fa-folder';
    try {
        document.getElementById('iconPreview').className = 'fa ' + icon;
    } catch(err) {
        document.getElementById('iconPreview').className = 'fa fa-folder';
    }
});

// Add Category Modal - Character Counter
document.getElementById('descriptionInput')?.addEventListener('input', function(e) {
    document.getElementById('descChars').textContent = e.target.value.length;
});

// Reset modal on close
document.getElementById('addCategoryModal')?.addEventListener('hide.bs.modal', function() {
    document.getElementById('addCategoryForm').reset();
    document.getElementById('iconPreview').className = 'fa fa-folder';
    document.getElementById('descChars').textContent = '0';
});

// Initialize modal on show
document.getElementById('addCategoryModal')?.addEventListener('show.bs.modal', function() {
    document.getElementById('categoryNameInput').focus();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\admin\categories\index.blade.php ENDPATH**/ ?>