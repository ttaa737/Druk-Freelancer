<?php $__env->startSection('title', 'Chat'); ?>
<?php $__env->startSection('content'); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('messages.index')); ?>" class="text-decoration-none">Messages</a></li>
        <li class="breadcrumb-item active" aria-current="page">Conversation</li>
    </ol>
</nav>

<div class="card shadow-sm mb-4">
    <div class="row g-0" style="min-height: 500px; max-height: calc(100vh - 280px);">
        <!-- Conversation sidebar (conversation list on left) -->
        <div class="col-lg-4 col-xl-3 border-end d-none d-lg-block" style="overflow-y: auto; background-color: #fafbfc; max-height: calc(100vh - 280px);">
            <div class="p-3 border-bottom bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0">
                        <i class="fa fa-comments me-2 text-primary"></i>Messages
                    </h6>
                    <a href="<?php echo e(route('messages.index')); ?>" class="btn btn-sm btn-outline-secondary" title="View all">
                        <i class="fa fa-list"></i>
                    </a>
                </div>
            </div>
            <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php 
                $other2 = $conv->poster_id === auth()->id() ? $conv->freelancer : $conv->poster;
                $isActive = $conv->id === $conversation->id;
                $unreadCount = $conv->messages->where('sender_id', '!=', auth()->id())->where('read_at', null)->count();
            ?>
            <a href="<?php echo e(route('messages.show', $conv)); ?>" 
               class="d-flex align-items-start gap-3 p-3 border-bottom text-decoration-none position-relative <?php echo e($isActive ? 'bg-primary bg-opacity-10 border-primary border-start border-3' : 'bg-white'); ?>"
               style="transition: all 0.2s;">
                
                <div class="position-relative flex-shrink-0">
                    <img src="<?php echo e($other2?->avatar_url ?? asset('img/default-avatar.png')); ?>" 
                         class="rounded-circle" 
                         style="width: 48px; height: 48px; object-fit: cover; border: 2px solid <?php echo e($isActive ? '#0d6efd' : '#e9ecef'); ?>;">
                    
                    <?php if($unreadCount > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          style="font-size: 9px; padding: 3px 6px;">
                        <?php echo e($unreadCount > 99 ? '99+' : $unreadCount); ?>

                    </span>
                    <?php endif; ?>
                </div>
                
                <div class="flex-grow-1 overflow-hidden">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="fw-semibold text-dark <?php echo e($unreadCount > 0 ? 'fw-bold' : ''); ?>" style="font-size: 14px;">
                            <?php echo e($other2?->name ?? 'Unknown User'); ?>

                        </span>
                        <?php if($conv->last_message_at): ?>
                        <small class="text-muted flex-shrink-0 ms-2" style="font-size: 10px;">
                            <?php echo e($conv->last_message_at->diffForHumans(null, true, true)); ?>

                        </small>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($conv->job): ?>
                    <p class="text-muted mb-1" style="font-size: 11px;">
                        <i class="fa fa-briefcase me-1"></i><?php echo e(Str::limit($conv->job->title, 30)); ?>

                    </p>
                    <?php endif; ?>
                    
                    <p class="text-muted mb-0 text-truncate <?php echo e($unreadCount > 0 ? 'fw-semibold' : ''); ?>"
                       style="font-size: 12px; max-width: 180px;">
                        <?php if($conv->latestMessage): ?>
                            <?php if($conv->latestMessage->sender_id === auth()->id()): ?>
                                <i class="fa fa-reply me-1" style="font-size: 10px;"></i>
                            <?php endif; ?>
                            <?php echo e($conv->latestMessage->body ? Str::limit($conv->latestMessage->body, 35) : '📎 Attachment'); ?>

                        <?php else: ?>
                            <em style="opacity: 0.7;">No messages yet</em>
                        <?php endif; ?>
                    </p>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <?php if($conversations->isEmpty()): ?>
            <div class="p-4 text-center">
                <i class="fa fa-inbox fa-3x text-muted mb-3" style="opacity: 0.2;"></i>
                <p class="text-muted small mb-0">No other conversations</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Chat Area -->
        <div class="col-lg-8 col-xl-9 d-flex flex-column">
            <?php $other = $conversation->poster_id === auth()->id() ? $conversation->freelancer : $conversation->poster; ?>
            
            <!-- Chat Header -->
            <div class="p-3 border-bottom bg-white d-flex align-items-center gap-3" style="box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <a href="<?php echo e(route('messages.index')); ?>" class="btn btn-sm btn-light border d-lg-none rounded-circle p-2" style="width: 36px; height: 36px;">
                    <i class="fa fa-arrow-left"></i>
                </a>
                
                <div class="position-relative">
                    <img src="<?php echo e($other?->avatar_url ?? asset('img/default-avatar.png')); ?>" 
                         class="rounded-circle" 
                         style="width: 50px; height: 50px; object-fit: cover; border: 3px solid #e9ecef;">
                    <!-- Online status indicator (optional) -->
                    <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle" 
                          style="width: 14px; height: 14px;" 
                          title="Active"></span>
                </div>
                
                <div class="flex-grow-1">
                    <div class="fw-bold mb-0" style="font-size: 16px;"><?php echo e($other?->name ?? 'Unknown User'); ?></div>
                    <div class="text-muted small d-flex align-items-center gap-2">
                        <?php if($other): ?>
                            <?php if($other->isFreelancer()): ?>
                            <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 10px;">
                                <i class="fa fa-user-tie me-1"></i>Freelancer
                            </span>
                            <?php else: ?>
                            <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 10px;">
                                <i class="fa fa-briefcase me-1"></i>Client
                            </span>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if($conversation->job): ?>
                        <span style="font-size: 11px;">
                            <i class="fa fa-circle" style="font-size: 4px;"></i>
                            <?php echo e(Str::limit($conversation->job->title, 30)); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="ms-auto d-flex gap-2">
                    <a href="<?php echo e(route('profile.show', $other)); ?>" 
                       class="btn btn-sm btn-light border rounded-circle p-2" 
                       style="width: 36px; height: 36px;"
                       title="View Profile">
                        <i class="fa fa-user"></i>
                    </a>
                    <?php if($conversation->job): ?>
                    <a href="<?php echo e(route('jobs.show', $conversation->job->slug)); ?>" 
                       class="btn btn-sm btn-light border rounded-circle p-2" 
                       style="width: 36px; height: 36px;"
                       title="View Job">
                        <i class="fa fa-briefcase"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Messages Container -->
            <div class="flex-grow-1 p-4 d-flex flex-column gap-2" 
                 id="messageList" 
                 style="overflow-y: auto; background: linear-gradient(to bottom, #e8f0f5 0%, #f5f8fa 100%);">
                <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php 
                    $isOwn = $msg->sender_id === auth()->id();
                    $sender = $msg->sender;
                ?>
                
                <!-- Message Bubble -->
                <div class="d-flex gap-2 align-items-end <?php echo e($isOwn ? 'flex-row-reverse' : ''); ?> mb-2">
                    <!-- Avatar (only show for received messages) -->
                    <?php if(!$isOwn): ?>
                    <img src="<?php echo e($sender->avatar_url ?? asset('img/default-avatar.png')); ?>" 
                         class="rounded-circle flex-shrink-0 mb-1" 
                         style="width:36px; height:36px; object-fit:cover; border: 2px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
                         alt="<?php echo e($sender->name); ?>">
                    <?php endif; ?>
                    
                    <div style="max-width: 65%;" class="<?php echo e($isOwn ? 'text-end' : ''); ?>">
                        <!-- Sender Name (only for received messages) -->
                        <?php if(!$isOwn): ?>
                        <div class="mb-1 ms-1">
                            <span class="badge bg-light text-dark border" style="font-size: 10px; font-weight: 600;">
                                <?php echo e($sender->name); ?>

                                <?php if($sender->isFreelancer()): ?>
                                <i class="fa fa-user-tie ms-1 text-primary"></i>
                                <?php else: ?>
                                <i class="fa fa-briefcase ms-1 text-success"></i>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php else: ?>
                        <div class="mb-1 me-1">
                            <span class="badge bg-primary" style="font-size: 10px; font-weight: 600;">
                                <i class="fa fa-user me-1"></i>You
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Message Content -->
                        <div class="position-relative">
                            <div class="rounded-3 px-3 py-2 <?php echo e($isOwn ? 'bg-primary text-white' : 'bg-white text-dark border'); ?>" 
                                 style="box-shadow: 0 1px 2px rgba(0,0,0,0.1); word-wrap: break-word;">
                                <?php if($msg->body): ?>
                                <div class="mb-0" style="white-space: pre-wrap; font-size: 14px; line-height: 1.5;"><?php echo e($msg->body); ?></div>
                                <?php endif; ?>
                                
                                <?php if($msg->attachment_path): ?>
                                <div class="mt-2 pt-2 <?php echo e($msg->body ? 'border-top' : ''); ?> <?php echo e($isOwn ? 'border-light' : 'border-secondary'); ?>" style="border-width: 1px; opacity: 0.9;">
                                    <a href="<?php echo e(asset('storage/' . $msg->attachment_path)); ?>" 
                                       download
                                       class="d-flex align-items-center gap-2 text-decoration-none <?php echo e($isOwn ? 'text-white' : 'text-primary'); ?>"
                                       style="font-size: 13px;">
                                        <i class="fa fa-file-alt fa-lg"></i>
                                        <div class="flex-grow-1 text-truncate">
                                            <div class="fw-semibold text-truncate" style="max-width: 180px;">
                                                <?php echo e($msg->attachment_name ?? basename($msg->attachment_path)); ?>

                                            </div>
                                            <small class="opacity-75" style="font-size: 11px;">Click to download</small>
                                        </div>
                                        <i class="fa fa-download"></i>
                                    </a>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Time & Status inside bubble -->
                                <div class="mt-1 d-flex align-items-center justify-content-end gap-1" 
                                     style="font-size: 10px; opacity: 0.8;">
                                    <span><?php echo e($msg->created_at->format('g:i A')); ?></span>
                                    <?php if($isOwn): ?>
                                        <?php if($msg->read_at): ?>
                                        <i class="fa fa-check-double" title="Read at <?php echo e($msg->read_at->format('M d, g:i A')); ?>" style="color: #4CAF50;"></i>
                                        <?php else: ?>
                                        <i class="fa fa-check" title="Sent"></i>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Message tail/arrow -->
                            <div class="position-absolute <?php echo e($isOwn ? 'end-0 me-n1' : 'start-0 ms-n1'); ?>" 
                                 style="bottom: 8px;">
                                <svg width="12" height="20" style="transform: <?php echo e($isOwn ? '' : 'scaleX(-1)'); ?>;">
                                    <path d="M 0,0 L 12,10 L 0,20 Z" fill="<?php echo e($isOwn ? '#0d6efd' : '#ffffff'); ?>" 
                                          stroke="<?php echo e($isOwn ? '#0d6efd' : '#dee2e6'); ?>" stroke-width="<?php echo e($isOwn ? '0' : '1'); ?>"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Spacer for sent messages (to align with avatar space) -->
                    <?php if($isOwn): ?>
                    <div style="width: 36px;"></div>
                    <?php endif; ?>
                </div>
                
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="text-center text-muted">
                        <i class="fa fa-comment-dots fa-4x mb-3" style="opacity: 0.2;"></i>
                        <h6 class="fw-semibold">No messages yet</h6>
                        <p class="small mb-0">Start the conversation with <?php echo e($other->name); ?>!</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Send Form -->
            <div class="p-3 border-top bg-white" style="box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
                <form method="POST" action="<?php echo e(route('messages.send', $conversation)); ?>" enctype="multipart/form-data" id="sendForm">
                    <?php echo csrf_field(); ?>
                    
                    <!-- File Preview -->
                    <div id="filePreview" class="mb-2 p-2 bg-light rounded border d-none">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa fa-file-alt text-primary"></i>
                            <span id="fileName" class="flex-grow-1 small fw-semibold text-truncate"></span>
                            <button type="button" class="btn btn-sm btn-link text-danger p-0" id="removeFile" title="Remove file">
                                <i class="fa fa-times-circle"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Input Group -->
                    <div class="d-flex align-items-center gap-2">
                        <!-- Attach Button -->
                        <label class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center" 
                               title="Attach file"
                               style="cursor: pointer; width: 42px; height: 42px; padding: 0;">
                            <i class="fa fa-paperclip text-secondary"></i>
                            <input type="file" name="attachment" class="d-none" id="fileInput" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
                        </label>
                        
                        <!-- Message Input -->
                        <div class="flex-grow-1 position-relative">
                            <input type="text" 
                                   name="body" 
                                   id="msgBody" 
                                   class="form-control form-control-lg border-2 rounded-pill ps-4 pe-5" 
                                   placeholder="Type your message here..."
                                   autocomplete="off"
                                   style="background-color: #f8f9fa; border-color: #dee2e6;">
                            <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                                <i class="fa fa-smile text-muted" style="cursor: pointer; opacity: 0.5;"></i>
                            </div>
                        </div>
                        
                        <!-- Send Button -->
                        <button class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center" 
                                type="submit" 
                                title="Send message"
                                style="width: 50px; height: 50px; padding: 0;">
                            <i class="fa fa-paper-plane fa-lg"></i>
                        </button>
                    </div>
                    
                    <!-- Help Text -->
                    <div class="mt-2 text-center">
                        <small class="text-muted" style="font-size: 11px;">
                            <i class="fa fa-info-circle me-1"></i>Press Enter to send, Shift+Enter for new line
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Scroll to bottom on load (smooth scroll)
const msgList = document.getElementById('messageList');
if (msgList) {
    setTimeout(() => {
        msgList.scrollTo({
            top: msgList.scrollHeight,
            behavior: 'smooth'
        });
    }, 100);
}

// File input handling
const fileInput = document.getElementById('fileInput');
const filePreview = document.getElementById('filePreview');
const fileName = document.getElementById('fileName');
const removeFile = document.getElementById('removeFile');

if (fileInput) {
    fileInput.addEventListener('change', function() {
        if (this.files.length) {
            const file = this.files[0];
            const maxSize = 10 * 1024 * 1024; // 10MB
            
            if (file.size > maxSize) {
                alert('File size exceeds 10MB limit. Please choose a smaller file.');
                this.value = '';
                filePreview.classList.add('d-none');
                return;
            }
            
            fileName.textContent = file.name;
            filePreview.classList.remove('d-none');
        } else {
            filePreview.classList.add('d-none');
        }
    });
}

if (removeFile) {
    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        filePreview.classList.add('d-none');
    });
}

// Auto-focus message input
const msgBody = document.getElementById('msgBody');
if (msgBody) {
    msgBody.focus();
}

// Form submission handling
const sendForm = document.getElementById('sendForm');
if (sendForm && msgBody) {
    // Prevent empty submissions
    sendForm.addEventListener('submit', function(e) {
        const body = msgBody.value.trim();
        const hasFile = fileInput.files.length > 0;
        
        if (!body && !hasFile) {
            e.preventDefault();
            msgBody.focus();
            return false;
        }
    });
    
    // Submit on Enter (but not Shift+Enter)
    msgBody.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendForm.submit();
        }
    });
}

// Auto-reload for new messages (every 5 seconds)
let lastMessageId = <?php echo e($messages->last()->id ?? 0); ?>;
setInterval(() => {
    fetch('<?php echo e(route('messages.poll', [$conversation, 'lastId' => $messages->last()->id ?? 0])); ?>')
        .then(response => response.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                // New messages received, reload page
                location.reload();
            }
        })
        .catch(error => {
            console.log('Polling error:', error);
        });
}, 5000);

// Mark unread messages from other user as read
<?php if($messages->where('sender_id', '!=', auth()->id())->where('read_at', null)->count() > 0): ?>
setTimeout(() => {
    <?php $__currentLoopData = $messages->where('sender_id', '!=', auth()->id())->where('read_at', null); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unreadMsg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    fetch('<?php echo e(route('messages.read', $unreadMsg)); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
        }
    }).catch(() => {});
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
}, 1000);
<?php endif; ?>
</script>

<style>
/* Custom scrollbar for message list */
#messageList::-webkit-scrollbar {
    width: 6px;
}

#messageList::-webkit-scrollbar-track {
    background: transparent;
}

#messageList::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
}

#messageList::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.3);
}

/* Conversation list scrollbar */
.col-lg-4.border-end::-webkit-scrollbar {
    width: 5px;
}

.col-lg-4.border-end::-webkit-scrollbar-track {
    background: transparent;
}

.col-lg-4.border-end::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.15);
    border-radius: 10px;
}

/* Conversation list hover effect */
.col-lg-4 a:not(.btn):hover {
    background-color: #f0f2f5 !important;
}

/* Message input focus */
#msgBody:focus {
    background-color: #fff !important;
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15) !important;
}

/* Send button hover effect */
button[type="submit"]:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4) !important;
}

button[type="submit"]:active {
    transform: scale(0.95);
}

/* Attach button hover */
label[for="fileInput"]:hover,
label:has(#fileInput):hover {
    background-color: #e9ecef !important;
}

/* Message bubble entrance animation */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.d-flex.gap-2.align-items-end.mb-2 {
    animation: slideIn 0.3s ease-out;
}

/* Attachment link hover */
.rounded-3 a:hover {
    opacity: 0.85;
}

/* Badge pulse effect for unread count */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.badge.bg-danger {
    animation: pulse 2s infinite;
}

/* Mobile responsive */
@media (max-width: 991px) {
    .card .row {
        min-height: 400px !important;
        max-height: calc(100vh - 200px) !important;
    }
    
    #messageList {
        max-height: calc(100vh - 350px);
    }
    
    .col-lg-4.border-end {
        max-height: calc(100vh - 200px) !important;
    }
}

@media (min-width: 992px) {
    .card .row {
        min-height: 500px;
        max-height: calc(100vh - 280px);
    }
    
    .col-lg-4.border-end,
    .col-lg-8.col-xl-9 {
        max-height: calc(100vh - 280px);
    }
}

/* Ensure card doesn't overflow page */
.card.shadow-sm {
    margin-bottom: 2rem;
}

/* Smooth transitions */
.btn, a, .form-control {
    transition: all 0.2s ease;
}
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\tandi\OneDrive\Desktop\Druk-Freelancing-System\resources\views\messages\show.blade.php ENDPATH**/ ?>