@extends('layouts.app')

@section('title', 'My Completion Submissions')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-6xl">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Completion Submissions</h1>
        <p class="text-gray-600">Track all your project completion submissions and their verification status</p>
    </div>

    <!-- Empty State -->
    @if($submissions->isEmpty())
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">No Submissions Yet</h2>
        <p class="text-gray-600 mb-6">When you complete a contract, submit your work here for admin verification and payment processing.</p>
        <a href="{{ route('contracts.index') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            View My Active Contracts
        </a>
    </div>
    @else
        <!-- Submissions List -->
        <div class="space-y-4">
            @foreach($submissions as $submission)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <!-- Contract Info -->
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Contract</p>
                        <p class="font-semibold text-gray-900">{{ $submission->contract->contract_number }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($submission->contract->job->title, 40) }}</p>
                    </div>

                    <!-- Amount -->
                    <div>
                        <p class="text-sm text-gray-600 mb-1">You Will Receive</p>
                        <p class="text-lg font-bold text-green-600">{{ config('platform.currency') }} {{ number_format($submission->contract->freelancer_amount, 2) }}</p>
                        <p class="text-xs text-gray-600 mt-1">Platform fee: {{ config('platform.currency') }} {{ number_format($submission->contract->platform_fee, 2) }}</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                @if($submission->isPending()) bg-yellow-100 text-yellow-800
                                @elseif($submission->isVerified()) bg-blue-100 text-blue-800
                                @elseif($submission->isRejected()) bg-red-100 text-red-800
                                @elseif($submission->isPaymentProcessed()) bg-green-100 text-green-800
                                @endif
                            ">
                                @if($submission->isPending()) ⏳ Pending Review
                                @elseif($submission->isVerified()) ✓ Verified
                                @elseif($submission->isRejected()) ✗ Rejected
                                @elseif($submission->isPaymentProcessed()) ✓ Payment Processed
                                @endif
                            </span>
                        </p>
                        <p class="text-xs text-gray-600 mt-2">{{ $submission->submitted_at->diffForHumans() }}</p>
                    </div>

                    <!-- Action -->
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('completion.show', $submission) }}" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                            View Details
                        </a>
                    </div>
                </div>

                <!-- Files & Rejection Info -->
                <div class="border-t border-gray-200 pt-4 text-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-600 mb-2">
                                <strong class="text-gray-900">{{ $submission->attachments->count() }}</strong> file(s) attached
                            </p>
                            @if($submission->isRejected())
                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded">
                                <p class="text-red-900 font-medium mb-1">Rejection Reason:</p>
                                <p class="text-red-800">{{ $submission->rejection_reason }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Files Preview -->
                        @if($submission->attachments->count() > 0)
                        <div class="text-right">
                            <p class="text-gray-600 mb-2">Files:</p>
                            <div class="space-y-1">
                                @foreach($submission->attachments->take(3) as $attachment)
                                <a href="{{ route('completion.download-attachment', $attachment) }}" class="block text-xs text-blue-600 hover:text-blue-800 truncate">
                                    {{ $attachment->getDocumentTypeLabel() }}: {{ Str::limit($attachment->file_name, 20) }}
                                </a>
                                @endforeach
                                @if($submission->attachments->count() > 3)
                                <p class="text-xs text-gray-600">+{{ $submission->attachments->count() - 3 }} more</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="border-t border-gray-200 mt-4 pt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="text-center">
                        <p class="text-gray-600">Submitted</p>
                        <p class="font-medium text-gray-900">{{ $submission->submitted_at->format('M d, Y') }}</p>
                    </div>
                    @if($submission->verified_at)
                    <div class="text-center">
                        <p class="text-gray-600">Verified</p>
                        <p class="font-medium text-green-600">{{ $submission->verified_at->format('M d, Y') }}</p>
                    </div>
                    @endif
                    @if($submission->payment_processed_at)
                    <div class="text-center">
                        <p class="text-gray-600">Payment Processed</p>
                        <p class="font-medium text-green-600">{{ $submission->payment_processed_at->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $submissions->links() }}
        </div>
    @endif

    <!-- Help Section -->
    <div class="mt-12 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-bold text-blue-900 mb-4">How the Completion Process Works</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div>
                <div class="flex items-center mb-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">1</div>
                    <h4 class="ml-3 font-semibold text-blue-900">Submit Evidence</h4>
                </div>
                <p class="text-blue-800 ml-11">
                    When your project is complete, submit all necessary evidence (screenshots, documents, deliverables) along with completion notes.
                </p>
            </div>

            <div>
                <div class="flex items-center mb-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">2</div>
                    <h4 class="ml-3 font-semibold text-blue-900">Admin Review</h4>
                </div>
                <p class="text-blue-800 ml-11">
                    Our admin team will review your submission within 24-48 hours. They may approve or request improvements.
                </p>
            </div>

            <div>
                <div class="flex items-center mb-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">3</div>
                    <h4 class="ml-3 font-semibold text-blue-900">Payment</h4>
                </div>
                <p class="text-blue-800 ml-11">
                    Upon approval, payment is automatically transferred to your wallet. Funds are immediately available for withdrawal.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
