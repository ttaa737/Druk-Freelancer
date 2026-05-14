@extends('layouts.admin')

@section('title', 'Completion Submissions')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Completion Submissions</h1>
            <p class="text-gray-600 mt-1">Review and verify project completions from freelancers</p>
        </div>
        <a href="{{ route('admin.completions.stats') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            View Statistics
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <p class="text-gray-600 text-sm">Pending Review</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $submissions->where('status', 'pending')->count() ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Verified</p>
            <p class="text-3xl font-bold text-green-600">{{ $submissions->where('status', 'verified')->count() ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Payment Processed</p>
            <p class="text-3xl font-bold text-blue-600">{{ $submissions->where('status', 'payment_processed')->count() ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-gray-600 text-sm">Rejected</p>
            <p class="text-3xl font-bold text-red-600">{{ $submissions->where('status', 'rejected')->count() ?? 0 }}</p>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="mb-6 flex gap-4 items-end">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" id="searchInput" placeholder="Search by contract number, freelancer name..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="verified">Verified</option>
                <option value="rejected">Rejected</option>
                <option value="payment_processed">Payment Processed</option>
            </select>
        </div>
    </div>

    <!-- Submissions Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($submissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Contract</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Freelancer</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Submitted</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($submissions as $submission)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $submission->contract->contract_number }}</p>
                                    <p class="text-sm text-gray-600">{{ Str::limit($submission->contract->job->title, 30) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.show', $submission->freelancer) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $submission->freelancer->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ config('platform.currency') }} {{ number_format($submission->contract->freelancer_amount, 2) }}</p>
                                    <p class="text-xs text-gray-600">Fee: {{ config('platform.currency') }} {{ number_format($submission->contract->platform_fee, 2) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $submission->submitted_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                    @if($submission->isPending()) bg-yellow-100 text-yellow-800
                                    @elseif($submission->isVerified()) bg-green-100 text-green-800
                                    @elseif($submission->isRejected()) bg-red-100 text-red-800
                                    @elseif($submission->isPaymentProcessed()) bg-blue-100 text-blue-800
                                    @endif
                                ">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.completions.show', $submission) }}" class="inline-block px-3 py-1 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 text-sm font-medium">
                                    Review
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $submissions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m4-4l2 2m-2-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-600 text-lg">No completion submissions to review</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.getElementById('searchInput').addEventListener('keyup', filterTable);
document.getElementById('statusFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const contractText = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const freelancerText = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const statusText = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

        const matchesSearch = contractText.includes(searchTerm) || freelancerText.includes(searchTerm);
        const matchesStatus = !statusFilter || statusText.includes(statusFilter.toLowerCase());

        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
}
</script>
@endpush
@endsection
