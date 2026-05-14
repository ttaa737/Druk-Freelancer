@extends('layouts.admin')

@section('title', 'Verify Completion - ' . $submission->contract->contract_number)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Review Completion Submission</h1>
                <p class="text-gray-600 mt-1">Contract: <strong>{{ $submission->contract->contract_number }}</strong></p>
            </div>
            <a href="{{ route('admin.completions.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Submissions
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="col-span-2 space-y-6">
            <!-- Submission Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Submission Details</h2>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="font-semibold">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                @if($submission->isPending()) bg-yellow-100 text-yellow-800
                                @elseif($submission->isVerified()) bg-green-100 text-green-800
                                @elseif($submission->isRejected()) bg-red-100 text-red-800
                                @elseif($submission->isPaymentProcessed()) bg-blue-100 text-blue-800
                                @endif
                            ">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Submitted At</p>
                        <p class="font-semibold">{{ $submission->submitted_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Freelancer</p>
                        <p class="font-semibold">
                            <a href="{{ route('admin.users.show', $submission->freelancer) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $submission->freelancer->name }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Job Poster</p>
                        <p class="font-semibold">
                            <a href="{{ route('admin.users.show', $submission->contract->poster) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $submission->contract->poster->name }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submission Notes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Freelancer's Notes</h2>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $submission->submission_notes }}</p>
                </div>
            </div>

            <!-- Attached Evidence -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Submitted Evidence ({{ $submission->attachments->count() }} files)</h2>

                @if($submission->attachments->count() > 0)
                    <div class="space-y-3">
                        @foreach($submission->attachments as $attachment)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8 16.5a1 1 0 11-2 0 1 1 0 012 0zM15 16.5a1 1 0 11-2 0 1 1 0 012 0z"/><path d="M3 4a2 2 0 00-2 2v4a2 2 0 002 2h9.586l-1.293-1.293a1 1 0 111.414-1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 00-1.414 1.414L12.586 7H3a1 1 0 01-.82-.384l-.84 1.566A1 1 0 001 8v4a1 1 0 11-2 0V8a3 3 0 013-3h9.586L9.293 2.293a1 1 0 011.414-1.414l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L12.586 4H3z"/>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $attachment->file_name }}</p>
                                            <p class="text-sm text-gray-600">
                                                {{ $attachment->getDocumentTypeLabel() }} • {{ number_format($attachment->file_size / 1024, 1) }} KB
                                            </p>
                                        </div>
                                    </div>
                                    @if($attachment->description)
                                    <p class="text-sm text-gray-600 ml-8">{{ $attachment->description }}</p>
                                    @endif
                                </div>
                                <a href="{{ route('completion.download-attachment', $attachment) }}" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                    Download
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">No attachments submitted</p>
                @endif
            </div>

            @if($submission->isRejected())
            <!-- Rejection Reason -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <h2 class="text-lg font-bold text-red-900 mb-3">Rejection Reason</h2>
                <p class="text-red-800">{{ $submission->rejection_reason }}</p>
                <p class="text-sm text-red-700 mt-3">Rejected at: {{ $submission->rejected_at->format('M d, Y H:i') }}</p>
            </div>
            @elseif($submission->isPaymentProcessed())
            <!-- Payment Processed -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h2 class="text-lg font-bold text-green-900 mb-3">✓ Payment Processed</h2>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-green-700">Verified By</p>
                        <p class="font-semibold text-gray-900">{{ $submission->verifiedBy->name }}</p>
                    </div>
                    <div>
                        <p class="text-green-700">Verified At</p>
                        <p class="font-semibold text-gray-900">{{ $submission->verified_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-green-700">Payment Processed</p>
                        <p class="font-semibold text-gray-900">{{ $submission->payment_processed_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar: Contract & Payment Info -->
        <div class="col-span-1">
            <!-- Contract Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Contract Information</h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Job Title</p>
                        <p class="font-semibold">{{ $submission->contract->job->title }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Contract Number</p>
                        <p class="font-semibold">{{ $submission->contract->contract_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Start Date</p>
                        <p class="font-semibold">{{ $submission->contract->start_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Deadline</p>
                        <p class="font-semibold">{{ $submission->contract->deadline->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 mt-4 pt-4">
                    <a href="{{ route('contracts.show', $submission->contract) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Full Contract →
                    </a>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-blue-900 mb-4">Payment Summary</h3>

                <div class="space-y-3 text-sm bg-white rounded p-3 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Contract:</span>
                        <span class="font-semibold">{{ config('platform.currency') }} {{ number_format($submission->contract->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                        <span class="text-gray-600">Platform Fee ({{ config('platform.service_fee_percent') }}%):</span>
                        <span class="font-semibold text-amber-600">{{ config('platform.currency') }} {{ number_format($submission->contract->platform_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                        <span class="font-semibold text-gray-900">Freelancer Receives:</span>
                        <span class="font-bold text-green-600 text-lg">{{ config('platform.currency') }} {{ number_format($submission->contract->freelancer_amount, 2) }}</span>
                    </div>
                </div>

                <div class="bg-gray-100 p-3 rounded text-xs text-gray-700">
                    <p><strong>When approved:</strong> Payment will be deducted from poster's account, distributed to freelancer, and platform fee added to admin account.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($submission->isPending())
            <div class="space-y-3">
                <!-- Approve Button -->
                <button
                    onclick="showApproveModal()"
                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
                >
                    ✓ Verify & Process Payment
                </button>

                <!-- Reject Button -->
                <button
                    onclick="showRejectModal()"
                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                >
                    ✗ Reject Submission
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Verify Completion & Process Payment</h2>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-green-800">
                    This action will:
                </p>
                <ul class="text-sm text-green-800 mt-2 ml-4 list-disc space-y-1">
                    <li>Mark completion as verified</li>
                    <li>Deduct {{ config('platform.currency') }} {{ number_format($submission->contract->total_amount, 2) }} from poster's account</li>
                    <li>Transfer {{ config('platform.currency') }} {{ number_format($submission->contract->freelancer_amount, 2) }} to freelancer</li>
                    <li>Add {{ config('platform.currency') }} {{ number_format($submission->contract->platform_fee, 2) }} to admin account</li>
                </ul>
            </div>

            <form id="approveForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Verification Notes (Optional)
                    </label>
                    <textarea
                        name="verification_notes"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                        placeholder="Notes about the verification..."
                    ></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="hideApproveModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Confirm Verification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Reject Submission</h2>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-red-800">
                    The freelancer will be notified to resubmit with corrected work.
                </p>
            </div>

            <form id="rejectForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Rejection Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="rejection_reason"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                        placeholder="Explain why the submission is being rejected..."
                        required
                        minlength="20"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">Be specific about what needs to be corrected</p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="hideRejectModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showApproveModal() {
    document.getElementById('approveModal').classList.remove('hidden');
}

function hideApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

document.getElementById('approveForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    await submitAction('{{ route("admin.completions.verify", $submission) }}', new FormData(e.target));
});

document.getElementById('rejectForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    await submitAction('{{ route("admin.completions.reject", $submission) }}', new FormData(e.target));
});

async function submitAction(url, formData) {
    try {
        const response = await fetch(url, {
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
                location.reload();
            }, 2000);
        } else {
            showAlert('error', data.message);
        }
    } catch (error) {
        showAlert('error', 'An error occurred');
        console.error(error);
    }
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="fixed top-4 right-4 p-4 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} shadow-lg max-w-md z-50">
            ${message}
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    setTimeout(() => {
        document.querySelector('.fixed').remove();
    }, 5000);
}

// Close modals on background click
document.getElementById('approveModal').addEventListener('click', (e) => {
    if (e.target.id === 'approveModal') hideApproveModal();
});

document.getElementById('rejectModal').addEventListener('click', (e) => {
    if (e.target.id === 'rejectModal') hideRejectModal();
});
</script>
@endpush
@endsection
