@extends('layouts.app')

@section('title', 'Submission Details')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Submission Details</h1>
            <p class="text-gray-600 mt-1">{{ $submission->contract->contract_number }}</p>
        </div>
        <a href="{{ route('completion.my-submissions') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Submissions
        </a>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900">Status Overview</h2>
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-bold
                        @if($submission->isPending()) bg-yellow-100 text-yellow-800
                        @elseif($submission->isVerified()) bg-blue-100 text-blue-800
                        @elseif($submission->isRejected()) bg-red-100 text-red-800
                        @elseif($submission->isPaymentProcessed()) bg-green-100 text-green-800
                        @endif
                    ">
                        @if($submission->isPending())
                            ⏳ Awaiting Review
                        @elseif($submission->isVerified())
                            ✓ Verified
                        @elseif($submission->isRejected())
                            ✗ Rejected
                        @elseif($submission->isPaymentProcessed())
                            ✓ Payment Processed
                        @endif
                    </span>
                </div>

                <!-- Timeline -->
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                            <div class="w-0.5 h-12 bg-gray-300"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Submitted</p>
                            <p class="text-sm text-gray-600">{{ $submission->submitted_at->format('M d, Y at H:i') }}</p>
                        </div>
                    </div>

                    @if($submission->verified_at)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                            <div class="w-0.5 h-12 bg-gray-300"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Verified by Admin</p>
                            <p class="text-sm text-gray-600">{{ $submission->verified_at->format('M d, Y at H:i') }}</p>
                            <p class="text-sm text-blue-600 mt-1">By: {{ $submission->verifiedBy->name }}</p>
                        </div>
                    </div>
                    @endif

                    @if($submission->rejected_at)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Rejected by Admin</p>
                            <p class="text-sm text-gray-600">{{ $submission->rejected_at->format('M d, Y at H:i') }}</p>
                            <p class="text-sm text-red-600 mt-1">By: {{ $submission->verifiedBy->name }}</p>
                        </div>
                    </div>
                    @elseif($submission->payment_processed_at)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Payment Processed</p>
                            <p class="text-sm text-gray-600">{{ $submission->payment_processed_at->format('M d, Y at H:i') }}</p>
                            <p class="text-sm text-green-600 mt-1">✓ Funds transferred to your wallet</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Completion Notes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Your Submission Notes</h2>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $submission->submission_notes }}</p>
                </div>
            </div>

            <!-- Attached Evidence -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Submitted Evidence</h2>

                @if($submission->attachments->count() > 0)
                    <div class="space-y-3">
                        @foreach($submission->attachments as $attachment)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
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
                    <p class="text-gray-600">No attachments in this submission</p>
                @endif
            </div>

            <!-- Rejection Feedback (if rejected) -->
            @if($submission->isRejected())
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <h2 class="text-lg font-bold text-red-900 mb-3">Feedback from Admin</h2>
                <p class="text-red-800 mb-4">{{ $submission->rejection_reason }}</p>
                <p class="text-sm text-red-700 mb-4">
                    <strong>What to do:</strong> Please address the feedback above and resubmit your completion with the necessary improvements.
                </p>
                <a href="{{ route('completion.create', $submission->contract) }}" class="inline-block px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Resubmit Completion
                </a>
            </div>
            @endif
        </div>

        <!-- Sidebar: Contract Info -->
        <div class="col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 sticky top-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Contract Information</h3>

                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-600">Job Title</p>
                        <p class="font-semibold text-gray-900">{{ $submission->contract->job->title }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">Contract Number</p>
                        <p class="font-semibold font-mono text-gray-900">{{ $submission->contract->contract_number }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">Status</p>
                        <p class="font-semibold text-gray-900">
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                {{ ucfirst($submission->contract->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-gray-600">Start Date</p>
                        <p class="font-semibold text-gray-900">{{ $submission->contract->start_date->format('M d, Y') }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">Deadline</p>
                        <p class="font-semibold text-gray-900">{{ $submission->contract->deadline->format('M d, Y') }}</p>
                    </div>

                    <div class="bg-blue-50 p-3 rounded border border-blue-200">
                        <p class="text-gray-600 text-xs mb-1">Payment Amount</p>
                        <p class="font-bold text-lg text-blue-600">{{ config('platform.currency') }} {{ number_format($submission->contract->freelancer_amount, 2) }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-200 mt-4 pt-4">
                    <a href="{{ route('contracts.show', $submission->contract) }}" class="inline-block text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Full Contract →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
