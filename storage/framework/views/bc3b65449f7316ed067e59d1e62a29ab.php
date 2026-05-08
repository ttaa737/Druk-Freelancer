<?php $__env->startSection('title', 'Settings'); ?>
<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-xl-10">
        
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="mb-1"><i class="fa fa-cog text-primary me-2"></i>Settings</h3>
                <p class="text-muted small mb-0">Manage your account preferences and settings</p>
            </div>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fa fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="fa fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        
        <ul class="nav nav-pills mb-4 bg-white p-2 rounded shadow-sm" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active px-4" id="account-tab" data-bs-toggle="pill" data-bs-target="#account" type="button" role="tab">
                    <i class="fa fa-user-circle me-2"></i>Account
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile" type="button" role="tab">
                    <i class="fa fa-id-card me-2"></i>Profile
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4" id="notifications-tab" data-bs-toggle="pill" data-bs-target="#notifications" type="button" role="tab">
                    <i class="fa fa-bell me-2"></i>Notifications
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4" id="privacy-tab" data-bs-toggle="pill" data-bs-target="#privacy" type="button" role="tab">
                    <i class="fa fa-lock me-2"></i>Privacy
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4" id="payment-tab" data-bs-toggle="pill" data-bs-target="#payment" type="button" role="tab">
                    <i class="fa fa-credit-card me-2"></i>Payment
                </button>
            </li>
        </ul>

        <div class="tab-content" id="settingsTabContent">
            
            
            <div class="tab-pane fade show active" id="account" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fa fa-user-circle text-primary me-2"></i>Account Settings</h5>
                        <p class="text-muted small mb-0 mt-1">Update your basic account information and password</p>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo e(route('settings.account.update')); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
                                    <?php if(!$user->hasVerifiedEmail()): ?>
                                    <small class="text-warning"><i class="fa fa-exclamation-triangle"></i> Email not verified</small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Preferred Language</label>
                                <select name="preferred_language" class="form-select">
                                    <option value="en" <?php echo e(($user->preferred_language ?? 'en') == 'en' ? 'selected' : ''); ?>>English</option>
                                    <option value="dz" <?php echo e(($user->preferred_language ?? 'en') == 'dz' ? 'selected' : ''); ?>>རྫོང་ཁ (Dzongkha)</option>
                                </select>
                            </div>

                            <hr class="my-4">

                            <h6 class="mb-3"><i class="fa fa-key text-warning me-2"></i>Change Password</h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" autocomplete="current-password">
                                    <small class="text-muted">Only required if you want to change your password</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="new_password" class="form-control" autocomplete="new-password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-2"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="profile" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fa fa-id-card text-primary me-2"></i>Profile Settings</h5>
                        <p class="text-muted small mb-0 mt-1">Manage your professional profile, skills, and portfolio</p>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-3">Your professional profile is what clients and freelancers see when they visit your page. Keep it updated to attract more opportunities.</p>
                        
                        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-outline-primary">
                            <i class="fa fa-edit me-2"></i>Edit My Profile
                        </a>
                        
                        <a href="<?php echo e(route('profile.show', $user)); ?>" class="btn btn-outline-secondary">
                            <i class="fa fa-eye me-2"></i>View Public Profile
                        </a>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="notifications" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fa fa-bell text-primary me-2"></i>Notification Preferences</h5>
                        <p class="text-muted small mb-0 mt-1">Choose what notifications you want to receive</p>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo e(route('settings.notifications.update')); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="mb-4">
                                <h6 class="mb-3">In-App Notifications</h6>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="notify_new_messages" id="notifyMessages" 
                                        <?php echo e(($user->notification_preferences['new_messages'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="notifyMessages">
                                        <strong>New Messages</strong>
                                        <div class="text-muted small">Get notified when someone sends you a message</div>
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="notify_proposals" id="notifyProposals" 
                                        <?php echo e(($user->notification_preferences['proposals'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="notifyProposals">
                                        <strong>Proposals & Job Updates</strong>
                                        <div class="text-muted small">Notifications about proposals you've received or sent</div>
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="notify_milestones" id="notifyMilestones" 
                                        <?php echo e(($user->notification_preferences['milestones'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="notifyMilestones">
                                        <strong>Milestone Updates</strong>
                                        <div class="text-muted small">Get notified about milestone submissions and approvals</div>
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="notify_payments" id="notifyPayments" 
                                        <?php echo e(($user->notification_preferences['payments'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="notifyPayments">
                                        <strong>Payments & Transactions</strong>
                                        <div class="text-muted small">Notifications about deposits, withdrawals, and payments</div>
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="notify_reviews" id="notifyReviews" 
                                        <?php echo e(($user->notification_preferences['reviews'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="notifyReviews">
                                        <strong>Reviews & Ratings</strong>
                                        <div class="text-muted small">Get notified when you receive a review</div>
                                    </label>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="mb-4">
                                <h6 class="mb-3">Email Notifications</h6>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotifications" 
                                        <?php echo e(($user->notification_preferences['email_notifications'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="emailNotifications">
                                        <strong>Send email notifications</strong>
                                        <div class="text-muted small">Receive important notifications via email to <?php echo e($user->email); ?></div>
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-2"></i>Save Preferences
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="privacy" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fa fa-lock text-primary me-2"></i>Privacy & Security</h5>
                        <p class="text-muted small mb-0 mt-1">Control who can see your information</p>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo e(route('settings.privacy.update')); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="mb-4">
                                <label class="form-label"><strong>Profile Visibility</strong></label>
                                <select name="profile_visibility" class="form-select">
                                    <option value="public" <?php echo e(($user->privacy_settings['profile_visibility'] ?? 'public') == 'public' ? 'selected' : ''); ?>>
                                        Public - Anyone can view my profile
                                    </option>
                                    <option value="freelancers_only" <?php echo e(($user->privacy_settings['profile_visibility'] ?? 'public') == 'freelancers_only' ? 'selected' : ''); ?>>
                                        Freelancers Only - Only registered users can view my profile
                                    </option>
                                    <option value="private" <?php echo e(($user->privacy_settings['profile_visibility'] ?? 'public') == 'private' ? 'selected' : ''); ?>>
                                        Private - Only people I share my profile link with can view it
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <h6 class="mb-3">Contact Information Visibility</h6>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="show_email" id="showEmail" 
                                        <?php echo e(($user->privacy_settings['show_email'] ?? false) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="showEmail">
                                        <strong>Show email address on profile</strong>
                                        <div class="text-muted small">Your email: <?php echo e($user->email); ?></div>
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="show_phone" id="showPhone" 
                                        <?php echo e(($user->privacy_settings['show_phone'] ?? false) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="showPhone">
                                        <strong>Show phone number on profile</strong>
                                        <div class="text-muted small">
                                            <?php if($user->phone): ?>
                                                Your phone: <?php echo e($user->phone); ?>

                                            <?php else: ?>
                                                <span class="text-warning">No phone number added</span>
                                            <?php endif; ?>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="allow_messages" id="allowMessages" 
                                        <?php echo e(($user->privacy_settings['allow_messages'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="allowMessages">
                                        <strong>Allow others to message me</strong>
                                        <div class="text-muted small">If disabled, only people you've worked with can message you</div>
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-2"></i>Save Privacy Settings
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="alert alert-danger">
                            <h6 class="alert-heading"><i class="fa fa-trash-alt me-2"></i>Delete Account</h6>
                            <p class="mb-2">Once you delete your account, there is no going back. Please be certain.</p>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="fa fa-exclamation-triangle me-2"></i>Delete My Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="payment" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0"><i class="fa fa-credit-card text-primary me-2"></i>Payment Methods</h5>
                        <p class="text-muted small mb-0 mt-1">Manage your payment and withdrawal methods</p>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-4">These payment methods will be used for withdrawals from your wallet. You can manage them from the <a href="<?php echo e(route('wallet.index')); ?>">Wallet page</a>.</p>
                        
                        <?php if($user->paymentMethods->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Account Details</th>
                                        <th>Status</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $user->paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e(ucfirst(str_replace('_', ' ', $method->method_type))); ?></strong>
                                        </td>
                                        <td>
                                            <?php if($method->method_type == 'bank_transfer'): ?>
                                                Bank: <?php echo e($method->details['bank_name'] ?? 'N/A'); ?><br>
                                                Account: <?php echo e($method->details['account_number'] ?? 'N/A'); ?>

                                            <?php elseif($method->method_type == 'mobile_wallet'): ?>
                                                Number: <?php echo e($method->details['phone_number'] ?? 'N/A'); ?>

                                            <?php else: ?>
                                                <?php echo e($method->account_identifier); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($method->is_verified): ?>
                                                <span class="badge bg-success">Verified</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                            <?php if($method->is_primary): ?>
                                                <span class="badge bg-primary">Primary</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <form method="POST" action="<?php echo e(route('settings.payment-method.delete', $method)); ?>" class="d-inline" 
                                                onsubmit="return confirm('Are you sure you want to remove this payment method?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i>You haven't added any payment methods yet. Add one to withdraw funds from your wallet.
                        </div>
                        <?php endif; ?>

                        <div class="mt-4">
                            <a href="<?php echo e(route('wallet.index')); ?>" class="btn btn-primary">
                                <i class="fa fa-plus me-2"></i>Add Payment Method
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa fa-exclamation-triangle me-2"></i>Delete Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo e(route('settings.account.delete')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>Warning!</strong> This action cannot be undone. All your data will be permanently deleted.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Enter your password to confirm</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type <strong>DELETE</strong> to confirm</label>
                        <input type="text" name="delete_confirmation" class="form-control" required placeholder="DELETE">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete My Account Permanently</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .nav-pills .nav-link {
        border-radius: 0.5rem;
        color: #6b7280;
        transition: all 0.2s;
    }
    .nav-pills .nav-link:hover {
        background-color: #f3f4f6;
        color: var(--druk-blue);
    }
    .nav-pills .nav-link.active {
        background-color: var(--druk-orange);
        color: white;
    }
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
    }
    .form-switch .form-check-input:checked {
        background-color: var(--druk-orange);
        border-color: var(--druk-orange);
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views/settings/index.blade.php ENDPATH**/ ?>