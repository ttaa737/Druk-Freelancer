
<?php $__env->startSection('title', 'Edit Profile'); ?>
<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-9">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="mb-1"><i class="fa fa-user-edit text-primary me-2"></i>Edit Profile</h3>
                <p class="text-muted small mb-0">Update your professional profile and settings</p>
            </div>
            <a href="<?php echo e(route('profile.show', auth()->user())); ?>" class="btn btn-outline-secondary">
                <i class="fa fa-eye me-2"></i>View Public Profile
            </a>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3"><i class="fa fa-check-circle me-2"></i><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3"><i class="fa fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <strong><i class="fa fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <ul class="nav nav-tabs mb-4" id="profileTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-personal"><i class="fa fa-user me-1"></i>Personal Info</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-skills"><i class="fa fa-tools me-1"></i>Skills</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-docs"><i class="fa fa-id-card me-1"></i>Verification</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-phone"><i class="fa fa-mobile-alt me-1"></i>Phone</a></li>
        </ul>

        <div class="tab-content">

            
            <div class="tab-pane fade show active" id="tab-personal">
                <div class="card">
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <input type="hidden" name="section" value="profile">

                            
                            <div class="d-flex align-items-start gap-4 mb-4">
                                <div class="text-center" style="min-width:110px">
                                    <img id="avatarPreview"
                                         src="<?php echo e(auth()->user()->avatar_url); ?>"
                                         class="rounded-circle border shadow-sm"
                                         style="width:100px;height:100px;object-fit:cover;"
                                         alt="Your photo">
                                    <div class="mt-2">
                                        <label class="btn btn-sm btn-outline-primary w-100" style="cursor:pointer">
                                            <i class="fa fa-camera me-1"></i>Change Photo
                                            <input type="file" name="avatar" class="d-none" accept="image/jpeg,image/png,image/jpg" onchange="previewAvatar(this)">
                                        </label>
                                        <div class="text-muted mt-1" style="font-size:10px">JPG/PNG, max 2 MB</div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-0"><?php echo e(auth()->user()->name); ?></h6>
                                    <div class="text-muted small"><?php echo e(auth()->user()->email); ?></div>
                                    <div class="mt-1">
                                        <?php
                                            $headerVerificationStatus = auth()->user()->verification_status;
                                            $headerVerificationLabel = ($headerVerificationStatus === 'rejected') ? 'Unverified' : ucfirst($headerVerificationStatus ?? 'Unverified');
                                        ?>
                                        <span class="badge bg-<?php echo e(auth()->user()->verification_status === 'verified' ? 'success' : 'secondary'); ?>">
                                            <i class="fa fa-<?php echo e(auth()->user()->verification_status === 'verified' ? 'check-circle' : 'clock'); ?> me-1"></i>
                                            <?php echo e($headerVerificationLabel); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>

                            <hr class="mb-4">

                            
                            <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:1px">Basic Details</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('name', auth()->user()->name)); ?>" required>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Professional Headline</label>
                                    <input type="text" name="headline" class="form-control <?php $__errorArgs = ['headline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('headline', auth()->user()->profile?->headline)); ?>"
                                           placeholder="e.g. Full Stack Developer | Graphic Designer">
                                    <?php $__errorArgs = ['headline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text text-muted small">+975</span>
                                        <input type="tel" name="phone" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               value="<?php echo e(old('phone', auth()->user()->phone)); ?>"
                                               placeholder="17XXXXXX">
                                    </div>
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Hourly Rate (Nu.)</label>
                                    <div class="input-group">
                                        <span class="input-group-text text-muted small">Nu.</span>
                                        <input type="number" name="hourly_rate" class="form-control <?php $__errorArgs = ['hourly_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               value="<?php echo e(old('hourly_rate', auth()->user()->profile?->hourly_rate)); ?>" min="0">
                                    </div>
                                    <?php $__errorArgs = ['hourly_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Years of Experience</label>
                                    <input type="number" name="experience_years" class="form-control"
                                           value="<?php echo e(old('experience_years', auth()->user()->profile?->experience_years)); ?>"
                                           min="0" max="60" placeholder="e.g. 3">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Availability</label>
                                    <select name="availability" class="form-select">
                                        <option value="">Select...</option>
                                        <option value="available" <?php if(old('availability', auth()->user()->profile?->availability) === 'available'): echo 'selected'; endif; ?>>Available for Work</option>
                                        <option value="busy" <?php if(old('availability', auth()->user()->profile?->availability) === 'busy'): echo 'selected'; endif; ?>>Busy / Limited Availability</option>
                                        <option value="not_available" <?php if(old('availability', auth()->user()->profile?->availability) === 'not_available'): echo 'selected'; endif; ?>>Not Available</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Bio / About Me</label>
                                    <textarea name="bio" class="form-control <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="4"
                                              placeholder="Tell clients about your experience, skills, and what makes you unique..."><?php echo e(old('bio', auth()->user()->profile?->bio)); ?></textarea>
                                    <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="text-muted" style="font-size:11px">Max 1000 characters</div>
                                </div>
                            </div>

                            
                            <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:1px">Location</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Dzongkhag</label>
                                    <select name="dzongkhag" class="form-select">
                                        <option value="">Select Dzongkhag</option>
                                        <?php $__currentLoopData = $dzongkhags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($dz); ?>" <?php if(old('dzongkhag', auth()->user()->profile?->dzongkhag) === $dz): echo 'selected'; endif; ?>><?php echo e($dz); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Gewog</label>
                                    <input type="text" name="gewog" class="form-control"
                                           value="<?php echo e(old('gewog', auth()->user()->profile?->gewog)); ?>"
                                           placeholder="Your gewog">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Full Address</label>
                                    <input type="text" name="address" class="form-control"
                                           value="<?php echo e(old('address', auth()->user()->profile?->address)); ?>"
                                           placeholder="Street / village, town">
                                </div>
                            </div>

                            
                            <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:1px">Online Presence</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Website / Portfolio URL</label>
                                    <input type="url" name="website" class="form-control <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('website', auth()->user()->profile?->website)); ?>"
                                           placeholder="https://yoursite.com">
                                    <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Preferred Language</label>
                                    <select name="preferred_language" class="form-select">
                                        <option value="en" <?php if(old('preferred_language', auth()->user()->preferred_language) === 'en'): echo 'selected'; endif; ?>>English</option>
                                        <option value="dz" <?php if(old('preferred_language', auth()->user()->preferred_language) === 'dz'): echo 'selected'; endif; ?>>Dzongkha</option>
                                    </select>
                                </div>
                            </div>

                            <?php if(auth()->user()->isJobPoster()): ?>
                            
                            <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:1px">Company / Organisation</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Company Name</label>
                                    <input type="text" name="company_name" class="form-control"
                                           value="<?php echo e(old('company_name', auth()->user()->profile?->company_name)); ?>"
                                           placeholder="e.g. Druk Holdings & Investments">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Industry</label>
                                    <input type="text" name="industry" class="form-control"
                                           value="<?php echo e(old('industry', auth()->user()->profile?->industry)); ?>"
                                           placeholder="e.g. Technology, Finance">
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-1"></i>Save Changes
                                </button>
                                <a href="<?php echo e(route('profile.show', auth()->user())); ?>" class="btn btn-outline-secondary">
                                    <i class="fa fa-eye me-1"></i>View Profile
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="tab-skills">
                <div class="card">
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <input type="hidden" name="section" value="skills">
                            <p class="text-muted small mb-4">Select the skills that best describe your expertise. Your skills appear on your public profile and help clients find you.</p>
                            <?php if($categories->isEmpty()): ?>
                            <div class="text-center text-muted py-4"><i class="fa fa-info-circle me-1"></i>No skills available yet.</div>
                            <?php else: ?>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-4">
                                <h6 class="fw-bold mb-2 text-muted text-uppercase" style="font-size:11px;letter-spacing:1px"><?php echo e($category->name); ?></h6>
                                <div class="row g-2">
                                    <?php $__currentLoopData = $category->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-sm-4 col-md-3">
                                        <div class="form-check border rounded px-3 py-2 <?php echo e(auth()->user()->skills->contains($skill->id) ? 'border-primary bg-primary bg-opacity-10' : ''); ?>">
                                            <input class="form-check-input" type="checkbox"
                                                   name="skills[]" value="<?php echo e($skill->id); ?>"
                                                   id="skill<?php echo e($skill->id); ?>"
                                                   <?php if(in_array($skill->id, old('skills', auth()->user()->skills->pluck('id')->toArray()))): echo 'checked'; endif; ?>>
                                            <label class="form-check-label small" for="skill<?php echo e($skill->id); ?>"><?php echo e($skill->name); ?></label>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i>Update Skills</button>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="tab-docs">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary bg-gradient text-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-shield-alt me-2 fs-5"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Identity Verification</h6>
                                <small class="opacity-75">Complete verification to unlock job posting, proposals, and trust badges</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php
                            $verificationStatus = auth()->user()->verification_status ?? 'unverified';
                            $verificationLabel = $verificationStatus === 'rejected' ? 'Unverified' : ucfirst($verificationStatus);
                            $statusMeta = [
                                'verified' => ['class' => 'success', 'icon' => 'check-circle', 'title' => 'Verified account', 'text' => 'Your account is verified and can access all platform features.'],
                                'pending' => ['class' => 'warning', 'icon' => 'hourglass-half', 'title' => 'Verification in review', 'text' => 'Your documents are under review. We will notify you once the review is complete.'],
                                'rejected' => ['class' => 'secondary', 'icon' => 'times-circle', 'title' => 'Unverified account', 'text' => 'Your account is currently unverified. Please review feedback and resubmit the required documents.'],
                                'default' => ['class' => 'info', 'icon' => 'info-circle', 'title' => 'Verification not started', 'text' => 'Upload your documents below to complete your identity verification and unlock more features.'],
                            ][$verificationStatus] ?? ['class' => 'info', 'icon' => 'info-circle', 'title' => 'Verification not started', 'text' => 'Upload your documents below to complete your identity verification and unlock more features.'];
                        ?>
                        <div class="border rounded-3 p-4 mb-4 bg-<?php echo e($statusMeta['class']); ?> bg-opacity-10 border-<?php echo e($statusMeta['class']); ?>">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-white border border-<?php echo e($statusMeta['class']); ?>" style="width:52px;height:52px;">
                                    <i class="fa fa-<?php echo e($statusMeta['icon']); ?> text-<?php echo e($statusMeta['class']); ?> fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <h6 class="fw-bold mb-0"><?php echo e($statusMeta['title']); ?></h6>
                                        <span class="badge bg-<?php echo e($statusMeta['class']); ?> text-uppercase"><?php echo e($verificationLabel); ?></span>
                                    </div>
                                    <p class="mb-0 text-muted small"><?php echo e($statusMeta['text']); ?></p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="mb-4 p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold mb-3"><i class="fa fa-star text-warning me-1"></i>Why verify your account</h6>
                            <div class="row g-2 small">
                                <div class="col-md-6">
                                    <i class="fa fa-check text-success me-1"></i> Verified badge on your profile
                                </div>
                                <div class="col-md-6">
                                    <i class="fa fa-check text-success me-1"></i> Higher search ranking
                                </div>
                                <div class="col-md-6">
                                    <i class="fa fa-check text-success me-1"></i> Build client trust & credibility
                                </div>
                                <div class="col-md-6">
                                    <i class="fa fa-check text-success me-1"></i> Access to premium features
                                </div>
                            </div>
                        </div>

                        
                        <div class="mb-4 p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold mb-3"><i class="fa fa-list-check text-info me-1"></i>Document Requirements</h6>
                            <?php if(auth()->user()->isFreelancer()): ?>
                                <div class="small">
                                    <div class="mb-2"><i class="fa fa-check-circle text-danger me-1"></i> <strong>Citizenship ID (CID)</strong> - Required</div>
                                    <div class="mb-2"><i class="fa fa-check-circle text-danger me-1"></i> <strong>Curriculum Vitae (CV)</strong> - Required</div>
                                    <div><i class="fa fa-circle text-muted me-1" style="font-size:7px"></i> <strong>Business License</strong> - Optional (if you run a business)</div>
                                </div>
                            <?php else: ?>
                                <div class="small">
                                    <div class="mb-2"><i class="fa fa-check-circle text-danger me-1"></i> <strong>Citizenship ID (CID)</strong> - Required</div>
                                    <div><i class="fa fa-circle text-muted me-1" style="font-size:7px"></i> <strong>Business License / BRN</strong> - Optional</div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:1px">
                            <i class="fa fa-file-upload me-1"></i> Required Documents
                        </h6>
                        <p class="text-muted small mb-3">Upload the documents below to complete verification. Accepted files: PDF, JPG, PNG, DOC, DOCX up to 5 MB.</p>

                        
                        <?php
                            $documentTypes = [];
                            
                            // All users must upload CID
                            $documentTypes[] = [
                                'type' => 'cid',
                                'label' => 'Citizenship ID (CID)',
                                'icon' => 'fa-id-card',
                                'description' => 'Upload a clear photo or scan of your Bhutanese CID (both front and back)',
                                'placeholder' => 'CID Number (e.g., 11509000001)',
                                'required' => true,
                                'showFor' => ['freelancer', 'job_poster']
                            ];

                            // Freelancers must upload CV
                            if (auth()->user()->isFreelancer()) {
                                $documentTypes[] = [
                                    'type' => 'cv',
                                    'label' => 'Curriculum Vitae (CV)',
                                    'icon' => 'fa-file-pdf',
                                    'description' => 'Upload your CV/Resume. This will be automatically attached to your job proposals.',
                                    'placeholder' => 'N/A',
                                    'required' => true,
                                    'showFor' => ['freelancer']
                                ];
                            }

                            // Optional: Business License for both roles
                            $documentTypes[] = [
                                'type' => 'brn',
                                'label' => 'Business License / BRN',
                                'icon' => 'fa-certificate',
                                'description' => 'Business Registration Number or professional license certificate (optional)',
                                'placeholder' => 'License/BRN Number',
                                'required' => false,
                                'showFor' => auth()->user()->isFreelancer() ? ['freelancer'] : ['job_poster']
                            ];
                        ?>

                        <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $docType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 
                                $doc = auth()->user()->verificationDocuments->where('document_type', $docType['type'])->first();
                            ?>
                            <div class="card mb-3 <?php echo e($doc && $doc->status==='approved' ? 'border-success' : ''); ?>">
                                <div class="card-header bg-light py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="fa <?php echo e($docType['icon']); ?> me-2 text-primary"></i>
                                            <span class="fw-semibold"><?php echo e($docType['label']); ?></span>
                                            <?php if($docType['required']): ?>
                                                <span class="badge bg-danger ms-2" style="font-size:9px">REQUIRED</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary ms-2" style="font-size:9px">OPTIONAL</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($doc): ?>
                                            <span class="badge bg-<?php echo e($doc->status==='approved' ? 'success' : ($doc->status==='rejected' ? 'danger' : 'warning text-dark')); ?>">
                                                <i class="fa fa-<?php echo e($doc->status==='approved' ? 'check-circle' : ($doc->status==='rejected' ? 'times-circle' : 'clock')); ?> me-1"></i><?php echo e(ucfirst($doc->status)); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Not Uploaded</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-muted small mb-3"><?php echo e($docType['description']); ?></p>
                                    
                                    <?php if($doc && $doc->status === 'rejected'): ?>
                                        <div class="alert alert-danger py-2 small mb-3">
                                            <strong><i class="fa fa-exclamation-triangle me-1"></i> Rejection Reason:</strong><br>
                                            <?php echo e($doc->rejection_reason); ?>

                                        </div>
                                    <?php endif; ?>

                                    <?php if($doc): ?>
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <a href="<?php echo e(route('profile.documents.view', $doc)); ?>" class="btn btn-outline-secondary btn-sm" target="_blank">
                                                <i class="fa fa-eye me-1"></i>View File
                                            </a>
                                            <a href="<?php echo e(route('profile.documents.download', $doc)); ?>" class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-download me-1"></i>Download File
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($doc && $doc->status === 'approved'): ?>
                                        <div class="d-flex align-items-center text-success">
                                            <i class="fa fa-check-circle me-2 fs-5"></i>
                                            <div>
                                                <strong>Document Approved</strong><br>
                                                <small class="text-muted">
                                                    Verified on <?php echo e($doc->reviewed_at->format('d M Y, h:i A')); ?>

                                                    <?php if($doc->document_number): ?>
                                                        • <?php echo e($doc->document_number); ?>

                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        </div>
                                    <?php elseif($doc && $doc->status === 'pending'): ?>
                                        <div class="d-flex align-items-center text-warning">
                                            <i class="fa fa-hourglass-half me-2 fs-5"></i>
                                            <div>
                                                <strong>Under Review</strong><br>
                                                <small class="text-muted">Submitted <?php echo e($doc->created_at->diffForHumans()); ?> • Our team typically reviews within 1-2 business days</small>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        
                                        <form method="POST" action="<?php echo e(route('profile.documents')); ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="document_type" value="<?php echo e($docType['type']); ?>">
                                            <div class="row g-3">
                                                <?php if($docType['type'] !== 'cv'): ?>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-semibold">
                                                            Document Number
                                                            <?php if($docType['type'] === 'cid'): ?> <span class="text-danger">*</span> <?php endif; ?>
                                                        </label>
                                                        <input type="text" name="document_number" class="form-control form-control-sm" 
                                                               placeholder="<?php echo e($docType['placeholder']); ?>"
                                                               <?php echo e($docType['type'] === 'cid' ? 'required' : ''); ?>>
                                                        <div class="invalid-feedback">Please enter document number.</div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="col-md-<?php echo e($docType['type'] === 'cv' ? '7' : '5'); ?>">
                                                    <label class="form-label small fw-semibold">Upload File <span class="text-danger">*</span></label>
                                                    <input type="file" name="document_file" class="form-control form-control-sm" 
                                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                                                    <div class="form-text" style="font-size:10px">PDF, JPG, PNG, DOC, DOCX (max 5 MB)</div>
                                                    <div class="invalid-feedback">Please select a file.</div>
                                                </div>
                                                <div class="col-md-3 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                                        <i class="fa fa-upload me-1"></i>Upload
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6 class="fw-bold mb-2"><i class="fa fa-lightbulb text-warning me-1"></i> Document Guidelines</h6>
                            <ul class="small mb-0 ps-3">
                                <li>Ensure documents are clear, readable, and not blurred</li>
                                <li>For CID: All four corners of the document should be visible (both front and back)</li>
                                <li>For CV: Ensure your full name, contact information, and qualifications are clearly visible</li>
                                <li>Documents must be valid and not expired</li>
                                <li>File size should not exceed 5 MB</li>
                                <li>Accepted formats: PDF, JPG, JPEG, PNG, DOC, DOCX</li>
                                <li>Personal information must match your profile name</li>
                            </ul>
                        </div>

                        
                        <div class="alert alert-secondary small mt-3 mb-0">
                            <i class="fa fa-lock me-1"></i> <strong>Privacy Notice:</strong> Your documents are securely stored and only reviewed by authorized administrators. We will never share your personal information with third parties.
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="tab-phone">
                <div class="card">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-1">Phone Verification</h6>
                        <p class="text-muted small mb-4">Verify your Bhutanese mobile number to unlock additional platform features.</p>

                        <?php if(auth()->user()->phone_verified_at): ?>
                        <div class="alert alert-success mb-0">
                            <i class="fa fa-check-circle me-2"></i>Phone <strong><?php echo e(auth()->user()->phone); ?></strong> verified!
                        </div>
                        <?php else: ?>
                        <?php if(!session('phone_otp_sent')): ?>
                        <form method="POST" action="<?php echo e(route('profile.phone.otp')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3" style="max-width:300px">
                                <label class="form-label small fw-semibold">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+975</span>
                                    <input type="tel" name="phone" class="form-control"
                                           value="<?php echo e(old('phone', auth()->user()->phone)); ?>"
                                           placeholder="17XXXXXX" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-sms me-1"></i>Send OTP</button>
                        </form>
                        <?php else: ?>
                        <form method="POST" action="<?php echo e(route('profile.phone.verify')); ?>">
                            <?php echo csrf_field(); ?>
                            <p class="text-muted small">Enter the 6-digit code sent to your phone.</p>
                            <div class="mb-3" style="max-width:200px">
                                <input type="text" name="otp"
                                       class="form-control text-center fw-bold fs-4 <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       maxlength="6" autofocus placeholder="——————" required>
                                <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check me-1"></i>Verify Phone</button>
                        </form>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.size > 2 * 1024 * 1024) {
            alert('Image must be under 2 MB.');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(file);
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/profile/edit.blade.php ENDPATH**/ ?>