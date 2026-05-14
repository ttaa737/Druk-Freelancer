@extends('layouts.app')

@section('title', 'Submit Completion - ' . $contract->contract_number)

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-gray-900">Submit Project Completion</h1>
            <a href="{{ route('contracts.show', $contract) }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Contract
            </a>
        </div>
        <p class="text-gray-600">Contract: <strong>{{ $contract->contract_number }}</strong></p>
        <p class="text-gray-600">Job: <strong>{{ $contract->job->title }}</strong></p>
    </div>

    <!-- Alert if resubmitting rejected -->
    @if($existingSubmission && $existingSubmission->isRejected())
    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-yellow-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="font-semibold text-yellow-900">Previous Submission Rejected</h3>
                <p class="text-yellow-800 mt-1"><strong>Reason:</strong> {{ $existingSubmission->rejection_reason }}</p>
                <p class="text-yellow-800 mt-1">Please address the feedback and resubmit with improved evidence.</p>
            </div>
        </div>
    </div>
    @endif

    <form id="completionForm" class="bg-white rounded-lg shadow-md p-6 space-y-6">
        @csrf
        
        <!-- Submission Notes -->
        <div>
            <label for="submission_notes" class="block text-sm font-semibold text-gray-900 mb-2">
                Completion Notes <span class="text-red-500">*</span>
            </label>
            <p class="text-sm text-gray-600 mb-3">
                Describe what you have completed, the work performed, and how the deliverables meet the contract requirements.
            </p>
            <textarea
                id="submission_notes"
                name="submission_notes"
                rows="6"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                placeholder="I have completed all the requirements as per the contract. Here's what was delivered:
1. ...
2. ..."
                required
                minlength="20"
                maxlength="2000"
            ></textarea>
            <p class="text-xs text-gray-500 mt-1">Minimum 20 characters, maximum 2000 characters</p>
        </div>

        <!-- Evidence/Attachments Section -->
        <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">
                Evidence & Documents <span class="text-red-500">*</span>
            </label>
            <p class="text-sm text-gray-600 mb-4">
                Upload all necessary evidence supporting your work completion (screenshots, reports, deliverable files, videos, etc.)
            </p>

            <!-- Attachments Container -->
            <div id="attachmentsContainer" class="space-y-4 mb-4">
                <!-- First attachment field is shown by default -->
                <div class="attachment-item bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Document Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Document Type <span class="text-red-500">*</span>
                            </label>
                            <select name="attachments[0][document_type]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select type...</option>
                                <option value="evidence">Evidence</option>
                                <option value="report">Report</option>
                                <option value="deliverable">Deliverable</option>
                                <option value="screenshot">Screenshot</option>
                                <option value="video">Video</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                File <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="file"
                                name="attachments[0][file]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 file:mr-3 file:py-1 file:px-2 file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                required
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.gif,.zip,.mp4,.webm"
                            />
                            <p class="text-xs text-gray-500 mt-1">Max 10MB, Supported: PDF, Office, Images, Video, ZIP</p>
                        </div>

                        <!-- Remove Button -->
                        <div class="flex items-end">
                            <button type="button" class="w-full px-3 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 remove-attachment" style="display: none;">
                                Remove
                            </button>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Description (Optional)
                        </label>
                        <input
                            type="text"
                            name="attachments[0][description]"
                            placeholder="Brief description of this file (e.g., 'Final screenshots of completed design')"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            maxlength="500"
                        />
                    </div>
                </div>
            </div>

            <!-- Add More Attachments Button -->
            <button type="button" id="addAttachment" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                + Add Another File
            </button>
            <p class="text-xs text-gray-500 mt-2">You can upload up to 10 files</p>
        </div>

        <!-- Contract Details Summary -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="font-semibold text-blue-900 mb-3">Contract Summary</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Total Contract Value:</span>
                    <p class="font-semibold text-gray-900">{{ config('platform.currency') }} {{ number_format($contract->total_amount, 2) }}</p>
                </div>
                <div>
                    <span class="text-gray-600">You Will Receive:</span>
                    <p class="font-semibold text-green-600">{{ config('platform.currency') }} {{ number_format($contract->freelancer_amount, 2) }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Platform Fee ({{ config('platform.service_fee_percent') }}%):</span>
                    <p class="font-semibold text-gray-900">{{ config('platform.currency') }} {{ number_format($contract->platform_fee, 2) }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Deadline:</span>
                    <p class="font-semibold text-gray-900">{{ $contract->deadline->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Terms Agreement -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-gray-700 leading-relaxed">
                <strong class="text-yellow-900">Important:</strong> By submitting your completion evidence, you confirm that:
            </p>
            <ul class="text-sm text-gray-700 mt-2 ml-4 list-disc space-y-1">
                <li>All work submitted is original and meets the contract specifications</li>
                <li>All necessary evidence is complete and accurately represents the work done</li>
                <li>You are ready for admin verification and payment processing</li>
                <li>Upon admin approval, payment will be automatically processed to your account</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('contracts.show', $contract) }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Cancel
            </a>
            <button
                type="submit"
                class="flex-1 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
            >
                Submit Completion Evidence
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let attachmentCount = 1;
    const maxAttachments = 10;
    const container = document.getElementById('attachmentsContainer');
    const addBtn = document.getElementById('addAttachment');
    const form = document.getElementById('completionForm');

    // Add attachment field
    addBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (attachmentCount >= maxAttachments) {
            alert(`Maximum ${maxAttachments} attachments allowed`);
            return;
        }

        const html = `
            <div class="attachment-item bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Document Type <span class="text-red-500">*</span></label>
                        <select name="attachments[${attachmentCount}][document_type]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select type...</option>
                            <option value="evidence">Evidence</option>
                            <option value="report">Report</option>
                            <option value="deliverable">Deliverable</option>
                            <option value="screenshot">Screenshot</option>
                            <option value="video">Video</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File <span class="text-red-500">*</span></label>
                        <input type="file" name="attachments[${attachmentCount}][file]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 file:mr-3 file:py-1 file:px-2 file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.gif,.zip,.mp4,.webm" />
                        <p class="text-xs text-gray-500 mt-1">Max 10MB</p>
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="w-full px-3 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 remove-attachment">Remove</button>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                    <input type="text" name="attachments[${attachmentCount}][description]" placeholder="Brief description of this file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" maxlength="500" />
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', html);
        attachmentCount++;
        updateRemoveButtons();
    });

    // Remove attachment field
    container.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-attachment')) {
            e.preventDefault();
            e.target.closest('.attachment-item').remove();
            attachmentCount--;
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const items = container.querySelectorAll('.attachment-item');
        items.forEach(item => {
            const btn = item.querySelector('.remove-attachment');
            btn.style.display = items.length > 1 ? 'block' : 'none';
        });
        addBtn.disabled = attachmentCount >= maxAttachments;
    }

    // Form submission
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        try {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
            
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            const data = await response.json();

            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                showAlert('error', data.message);
            }
        } catch (error) {
            showAlert('error', 'An error occurred. Please try again.');
            console.error(error);
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });

    function showAlert(type, message) {
        const alertHtml = `
            <div class="fixed top-4 right-4 p-4 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} shadow-lg max-w-md">
                ${message}
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', alertHtml);
        setTimeout(() => {
            document.querySelector('.fixed').remove();
        }, 5000);
    }

    updateRemoveButtons();
});
</script>
@endpush
@endsection
