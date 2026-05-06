<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-1"><i class="fa fa-briefcase me-2"></i><?php echo e(isset($job) ? 'Edit Job Posting' : 'Post a New Job'); ?></h5>
                <p class="text-muted small mb-4"><?php echo e(isset($job) ? 'Update' : 'Fill in'); ?> the details below to <?php echo e(isset($job) ? 'update your' : 'create a new'); ?> job posting</p>

                <?php if(!isset($job) && auth()->check() && auth()->user()->verification_status !== 'verified'): ?>
                <div class="border border-warning rounded-3 bg-warning bg-opacity-10 p-3 mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:42px;height:42px;background:#fff3cd;color:#856404;">
                            <i class="fa fa-shield-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold mb-1">Verification needed before posting</div>
                            <div class="small text-muted mb-2">You can complete the form now, but your job will only be posted after your account is verified.</div>
                            <a href="<?php echo e(route('profile.edit')); ?>#tab-docs" class="btn btn-warning btn-sm">
                                <i class="fa fa-id-card me-1"></i> Go to Verification
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(isset($job) ? route('jobs.update', $job) : route('jobs.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php if(isset($job)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Job Title <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                               class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('title', $job->title ?? '')); ?>"
                               placeholder="e.g. Senior Laravel Developer" required>
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Select Category</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id', $job->category_id ?? '') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Job Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="fixed"     <?php echo e(old('type', $job->type ?? '') == 'fixed'     ? 'selected' : ''); ?>>Fixed Price</option>
                                <option value="hourly"    <?php echo e(old('type', $job->type ?? '') == 'hourly'    ? 'selected' : ''); ?>>Hourly Rate</option>
                                <option value="milestone" <?php echo e(old('type', $job->type ?? '') == 'milestone' ? 'selected' : ''); ?>>Milestone Based</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="8" required
                                  class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="Describe the project, requirements, and what you expect from freelancers..."><?php echo e(old('description', $job->description ?? '')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                          <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Budget Min (Nu.)</label>
                            <input type="number" name="budget_min" min="300" max="500000" class="form-control"
                                   value="<?php echo e(old('budget_min', $job->budget_min ?? '')); ?>"
                            placeholder="e.g. 300">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Budget Max (Nu.)</label>
                            <input type="number" name="budget_max" min="300" max="500000" class="form-control"
                                value="<?php echo e(old('budget_max', $job->budget_max ?? '')); ?>"
                                placeholder="e.g. 500000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Deadline</label>
                            
                            <input type="text" id="deadline_picker" class="form-control" placeholder="dd/mm/yyyy">
                            
                            <input type="hidden" name="deadline" id="deadline" value="<?php echo e(old('deadline', optional($job->deadline ?? null)->format('d/m/Y'))); ?>">
                            <div id="deadlineError" class="invalid-feedback d-none"></div>
                            <small class="form-text text-muted">Select a deadline (dd/mm/yyyy)</small>
                            <?php $__errorArgs = ['deadline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Location (Dzongkhag)</label>
                            <select name="dzongkhag" class="form-select">
                                <option value="">Remote / Any</option>
                                <?php $__currentLoopData = \App\Models\Profile::DZONGKHAGS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dz); ?>" <?php echo e(old('dzongkhag', $job->dzongkhag ?? '') == $dz ? 'selected' : ''); ?>><?php echo e($dz); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Experience Level</label>
                            <select name="experience_level" class="form-select">
                                <option value="">Any Level</option>
                                <option value="entry"        <?php echo e(old('experience_level', $job->experience_level ?? '') == 'entry'        ? 'selected' : ''); ?>>Entry Level</option>
                                <option value="intermediate" <?php echo e(old('experience_level', $job->experience_level ?? '') == 'intermediate' ? 'selected' : ''); ?>>Intermediate</option>
                                <option value="expert"       <?php echo e(old('experience_level', $job->experience_level ?? '') == 'expert'       ? 'selected' : ''); ?>>Expert</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Required Skills <span class="text-muted">(Select all that apply)</span></label>
                        <div class="border rounded p-3" style="background: #f8f9fa;">
                            <input type="text" id="skillSearch" placeholder="Search skills..." 
                                   class="form-control form-control-sm mb-2">
                            <div class="row g-2" id="skillsGrid" style="max-height: 300px; overflow-y: auto;">
                                <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 col-md-4 col-lg-3 skill-item" data-skill-name="<?php echo e(strtolower($skill->name)); ?>">
                                    <div class="form-check">
                                        <input type="checkbox" name="skills[]" value="<?php echo e($skill->id); ?>"
                                               id="skill_<?php echo e($skill->id); ?>"
                                               class="form-check-input"
                                               <?php echo e(in_array($skill->id, old('skills', isset($job) ? $job->skills->pluck('id')->toArray() : [])) ? 'checked' : ''); ?>>
                                        <label class="form-check-label small" for="skill_<?php echo e($skill->id); ?>">
                                            <?php echo e($skill->name); ?>

                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div id="noSkillsFound" class="text-center py-3 text-muted small d-none">
                                No skills found matching your search
                            </div>
                        </div>
                        <small class="form-text text-muted">Select skills that are required or preferred for this job</small>
                    </div>

                    
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Attachments <span class="text-muted">(Optional)</span></label>
                           <input type="file" name="attachments[]" id="jobAttachments" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               class="form-control" data-temp-upload-url="<?php echo e(route('jobs.attachments.temp')); ?>">
                           <small class="form-text text-muted d-block mt-1">Files are uploaded as drafts, so refreshing the page will not remove them.</small>
                           <div id="attachmentPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                           <div id="tempAttachmentsInputs"></div>
                    </div>

                    
                    <div class="d-flex gap-2 pt-3">
                        <?php if(isset($job) || auth()->user()->verification_status === 'verified'): ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>
                            <?php echo e(isset($job) ? 'Update Job' : 'Post Job'); ?>

                        </button>
                        <?php else: ?>
                        <button type="button" class="btn btn-primary" disabled>
                            <i class="fa fa-save me-1"></i>
                            Post Job
                        </button>
                        <?php endif; ?>
                        <a href="<?php echo e(route('jobs.my')); ?>" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                    <?php if(!isset($job) && auth()->check() && auth()->user()->verification_status !== 'verified'): ?>
                    <div class="alert alert-info mt-3 mb-0 py-2 px-3 small d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <i class="fa fa-info-circle me-1"></i>
                            Verify your account to enable job posting.
                        </div>
                        <a href="<?php echo e(route('profile.edit')); ?>#tab-docs" class="fw-semibold text-decoration-none">Open Verification</a>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('skillSearch');
    const skillsGrid = document.getElementById('skillsGrid');
    const skillItems = document.querySelectorAll('.skill-item');
    const noSkillsFound = document.getElementById('noSkillsFound');
    const attachmentInput = document.getElementById('jobAttachments');
    const attachmentPreview = document.getElementById('attachmentPreview');
    const tempAttachmentsInputs = document.getElementById('tempAttachmentsInputs');
    const uploadUrl = attachmentInput ? attachmentInput.dataset.tempUploadUrl : null;
    const pageStateKey = 'druk_job_temp_attachments_state';
    const pageInstanceId = (() => {
        try {
            const existing = sessionStorage.getItem(pageStateKey);
            if (existing) {
                return existing;
            }

            const generated = `${Date.now()}_${Math.random().toString(36).slice(2, 10)}`;
            sessionStorage.setItem(pageStateKey, generated);
            return generated;
        } catch (error) {
            return `${Date.now()}_${Math.random().toString(36).slice(2, 10)}`;
        }
    })();
    const storageKey = 'druk_job_temp_attachments_' + pageInstanceId;

    let tempAttachments = [];

    function saveTempAttachments() {
        sessionStorage.setItem(storageKey, JSON.stringify(tempAttachments));
    }

    function renderTempAttachments() {
        if (!attachmentPreview || !tempAttachmentsInputs) {
            return;
        }

        attachmentPreview.innerHTML = '';
        tempAttachmentsInputs.innerHTML = '';

        tempAttachments.forEach((file) => {
            const chip = document.createElement('span');
            chip.className = 'badge bg-light text-dark border px-3 py-2';
            chip.innerHTML = '<i class="fa fa-paperclip me-1 text-primary"></i>' + file.name;
            attachmentPreview.appendChild(chip);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'temp_attachments[]';
            input.value = file.path;
            tempAttachmentsInputs.appendChild(input);

            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'temp_attachment_names[]';
            nameInput.value = file.name;
            tempAttachmentsInputs.appendChild(nameInput);
        });
    }

    function restoreTempAttachments() {
        try {
            tempAttachments = JSON.parse(sessionStorage.getItem(storageKey) || '[]');
        } catch (error) {
            tempAttachments = [];
        }

        renderTempAttachments();
    }

    function clearTempAttachments() {
        try {
            sessionStorage.removeItem(storageKey);
            sessionStorage.removeItem(pageStateKey);
        } catch (error) {
            // ignore storage cleanup failures
        }
    }

    if (attachmentInput && uploadUrl) {
        restoreTempAttachments();

        attachmentInput.addEventListener('change', async function() {
            const files = Array.from(this.files || []);
            if (!files.length) {
                return;
            }

            for (const file of files) {
                try {
                    const formData = new FormData();
                    formData.append('_token', '<?php echo e(csrf_token()); ?>');
                    formData.append('attachment', file);

                    const response = await fetch(uploadUrl, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (!response.ok) {
                        continue;
                    }

                    const data = await response.json();
                    tempAttachments.push({ path: data.path, name: data.name, url: data.url });
                } catch (error) {
                    continue;
                }
            }

            this.value = '';
            saveTempAttachments();
            renderTempAttachments();
        });
    }

    window.addEventListener('pagehide', function() {
        const performanceEntries = performance.getEntriesByType('navigation');
        const navigationType = performanceEntries.length ? performanceEntries[0].type : '';

        // Preserve drafts on refresh, but clear them when the page is left normally.
        if (navigationType !== 'reload') {
            clearTempAttachments();
        }
    });

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            skillItems.forEach(function(item) {
                const skillName = item.getAttribute('data-skill-name');
                if (skillName.includes(searchTerm)) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                skillsGrid.classList.add('d-none');
                noSkillsFound.classList.remove('d-none');
            } else {
                skillsGrid.classList.remove('d-none');
                noSkillsFound.classList.add('d-none');
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const datePicker = document.getElementById('deadline_picker');
    const hiddenDeadline = document.getElementById('deadline');
    const deadlineError = document.getElementById('deadlineError');

    function ddmmyyyyToIso(value) {
        // value: dd/mm/yyyy -> return yyyy-mm-dd
        if (!value) return '';
        const parts = value.split('/');
        if (parts.length !== 3) return '';
        const [d, m, y] = parts;
        if (d.length !== 2 || m.length !== 2 || y.length !== 4) return '';
        return `${y}-${m}-${d}`;
    }

    // Initialize Flatpickr calendar with dd/mm/yyyy format
    if (datePicker && hiddenDeadline) {
        const minDate = new Date();
        minDate.setDate(minDate.getDate() + 1); // Tomorrow at minimum

        flatpickr(datePicker, {
            format: 'd/m/Y',
            dateFormat: 'd/m/Y',
            minDate: minDate,
            onChange: function(selectedDates, dateStr) {
                if (dateStr) {
                    hiddenDeadline.value = dateStr; // Already in dd/mm/yyyy format from Flatpickr
                } else {
                    hiddenDeadline.value = '';
                }
            },
            onReady: function() {
                // Initialize with existing value if present
                if (hiddenDeadline && hiddenDeadline.value) {
                    datePicker.value = hiddenDeadline.value;
                    this.setDate(hiddenDeadline.value, false);
                }
            }
        });
    }

    // Form submission validation
    if (datePicker && hiddenDeadline) {
        const form = datePicker.closest('form');
        form.addEventListener('submit', function(e) {
            deadlineError.classList.add('d-none');
            deadlineError.classList.remove('d-block');

            const dateStr = hiddenDeadline.value; // dd/mm/yyyy
            if (!dateStr) {
                return; // Allow submit if empty (optional field)
            }

            const iso = ddmmyyyyToIso(dateStr);
            const selected = new Date(iso + 'T00:00:00');
            const today = new Date();
            today.setHours(0,0,0,0);

            if (!(selected instanceof Date) || isNaN(selected.getTime())) {
                e.preventDefault();
                deadlineError.textContent = 'Invalid date selected.';
                deadlineError.classList.remove('d-none');
                deadlineError.classList.add('d-block');
                return;
            }

            // Validate date is after today
            if (selected <= today) {
                e.preventDefault();
                deadlineError.textContent = 'Please select a date after today.';
                deadlineError.classList.remove('d-none');
                deadlineError.classList.add('d-block');
                return;
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/jobs/_form.blade.php ENDPATH**/ ?>