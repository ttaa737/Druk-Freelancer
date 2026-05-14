# Integration Guide: Completion Submission System

## Overview
This guide explains how to integrate the Completion Submission System into your existing contract views and navigation.

## Steps to Integrate

### 1. Add "Submit Completion" Button to Contract View

**File: `resources/views/contracts/show.blade.php`**

Add this section where you show contract actions (typically after fund escrow button):

```blade
<!-- Completion Submission Section -->
@if($contract->status === 'active' && auth()->check() && auth()->id() === $contract->freelancer_id)
    @php
        $existingSubmission = $contract->completionSubmission;
        $canSubmit = !$existingSubmission || $existingSubmission->status === \App\Models\CompletionSubmission::STATUS_REJECTED;
    @endphp

    <div class="mt-6 bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
        <h3 class="text-lg font-bold text-blue-900 mb-2">Project Completion</h3>

        @if($existingSubmission && $existingSubmission->isPending())
            <div class="mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800">
                <strong>Submission Pending:</strong> Your completion submission is awaiting admin review. Check back soon!
            </div>
        @elseif($existingSubmission && $existingSubmission->isVerified())
            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-800">
                <strong>✓ Verified:</strong> Your submission has been verified. Payment is being processed.
            </div>
        @elseif($existingSubmission && $existingSubmission->isPaymentProcessed())
            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-800">
                <strong>✓ Payment Processed:</strong> {{ config('platform.currency') }} {{ number_format($contract->freelancer_amount, 2) }} has been transferred to your wallet.
            </div>
        @endif

        @if($canSubmit)
            <a href="{{ route('completion.create', $contract) }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                {{ $existingSubmission && $existingSubmission->isRejected() ? 'Resubmit' : 'Submit' }} Completion Evidence
            </a>
        @endif

        <a href="{{ route('completion.my-submissions') }}" class="inline-block ml-3 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
            View All Submissions
        </a>
    </div>
@endif
```

### 2. Update Navigation Menu

**File: `resources/views/layouts/app.blade.php` or your navigation component**

Add link in freelancer menu:

```blade
<!-- In freelancer dropdown menu -->
@if(auth()->check() && auth()->user()->is_freelancer)
    <a href="{{ route('completion.my-submissions') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
        My Submissions
    </a>
@endif
```

### 3. Update Admin Dashboard

**File: `resources/views/admin/dashboard.blade.php`**

Add completion statistics card:

```blade
<!-- Add to admin dashboard -->
<div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
    <div class="flex items-center">
        <div>
            <p class="text-gray-600 text-sm">Pending Completions</p>
            <p class="text-3xl font-bold text-blue-600">
                {{ \App\Models\CompletionSubmission::where('status', 'pending')->count() }}
            </p>
        </div>
    </div>
    <a href="{{ route('admin.completions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-3 inline-block">
        Review Submissions →
    </a>
</div>
```

### 4. Update Admin Sidebar

**File: `resources/views/layouts/admin.blade.php`**

Add completion management link:

```blade
<!-- In admin sidebar navigation -->
<li>
    <a href="{{ route('admin.completions.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 100 2H6v10a2 2 0 01-2 2H4a1 1 0 100 2h2a4 4 0 004-4V7h2a1 1 0 100-2h-2V5a2 2 0 00-2-2H6a1 1 0 000 2zm0 5a1 1 0 100 2v4a2 2 0 01-2 2H4a1 1 0 100 2h2a4 4 0 004-4v-4a1 1 0 100-2h-2z" clip-rule="evenodd"/>
        </svg>
        Completions
        @php
            $pendingCount = \App\Models\CompletionSubmission::where('status', 'pending')->count();
        @endphp
        @if($pendingCount > 0)
            <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $pendingCount }}</span>
        @endif
    </a>
</li>
```

### 5. Update Contract Model Relationship

**File: `app/Models/Contract.php`**

Verify the relationship exists (should already be added):

```php
public function completionSubmission(): HasOne 
{ 
    return $this->hasOne(CompletionSubmission::class); 
}
```

### 6. Register Policies (if not auto-discovered)

**File: `app/Providers/AuthServiceProvider.php`**

Add to the `policies` array:

```php
protected $policies = [
    'App\Models\CompletionSubmission' => 'App\Policies\CompletionSubmissionPolicy',
    'App\Models\CompletionSubmissionAttachment' => 'App\Policies\CompletionSubmissionAttachment',
];
```

### 7. Publish Configuration

Ensure `config/platform.php` has these settings:

```php
'service_fee_percent'  => (float) env('PLATFORM_SERVICE_FEE_PERCENT', 10),
'currency'             => 'Nu',
'min_withdrawal'       => (float) env('PLATFORM_MIN_WITHDRAWAL', 500),
```

## File Upload Configuration

**File: `config/filesystems.php`**

Ensure you have a private disk configured:

```php
'disks' => [
    'private' => [
        'driver' => 'local',
        'root'   => storage_path('app/private'),
        'url'    => env('APP_URL').'/storage',
        'visibility' => 'private',
    ],
],
```

Create the directory if needed:
```bash
mkdir -p storage/app/private
chmod 750 storage/app/private
```

## Database Migration

**Run migrations:**

```bash
php artisan migrate
```

This will create:
- `completion_submissions` table
- `completion_submission_attachments` table
- Add columns to `contracts` table

## Authorization Setup

### Contract Policies

**File: `app/Policies/ContractPolicy.php`** (if exists, update):

```php
public function submitCompletion(User $user, Contract $contract): bool
{
    return $user->id === $contract->freelancer_id && $contract->status === 'active';
}
```

### Controller Policies

The `CompletionSubmissionController` already uses these policies:

```php
public function create(Contract $contract)
{
    $this->authorize('submitCompletion', $contract);
    // ...
}
```

## Notifications (Optional Enhancement)

To add email notifications, create these notification classes:

```bash
php artisan make:notification CompletionSubmittedNotification
php artisan make:notification CompletionVerifiedNotification
php artisan make:notification CompletionRejectedNotification
```

Then add to controllers:

```php
// In CompletionSubmissionController@store
Notification::send($contract->poster, new CompletionSubmittedNotification($submission));

// In AdminCompletionController@verify
Notification::send($submission->freelancer, new CompletionVerifiedNotification($submission));

// In AdminCompletionController@reject
Notification::send($submission->freelancer, new CompletionRejectedNotification($submission));
```

## Testing Integration

### Test Freelancer Flow:
1. Navigate to active contract
2. Click "Submit Completion Evidence" button
3. Fill form with notes and files
4. Submit
5. Check `/completion/my-submissions` for status

### Test Admin Flow:
1. Go to `/admin/completions/`
2. See pending submissions
3. Click "Review" on a submission
4. Download and inspect files
5. Approve or reject
6. Verify payment processed in wallets

### Verify Payment Processing:
1. Check freelancer wallet: balance should increase
2. Check job poster wallet: balance should decrease
3. Check admin wallet: fee should be added
4. Verify transaction records created

## Troubleshooting

### "File not found" when downloading
- Ensure file exists in `storage/app/private/`
- Check permissions: `chmod 755 storage`
- Verify storage link: `php artisan storage:link`

### Payment not processing
- Check `\App\Services\PaymentProcessingService`
- Enable debugging: `LOG_LEVEL=debug`
- Review logs: `tail storage/logs/laravel.log`

### Authorization errors
- Verify policies in `app/Policies/`
- Check user roles: `auth()->user()->is_admin`
- Test with `can()` in Blade: `@can('verify', $submission)`

### Migration issues
- Check column exists: `php artisan tinker`
- Review migration: `database/migrations/2026_05_14_*.php`
- Rollback if needed: `php artisan migrate:rollback`

## Performance Optimization

### Add Indexes (if needed)

```php
// In migration
$table->index('status');
$table->index(['contract_id', 'status']);
$table->index(['freelancer_id', 'status']);
```

### Cache Pending Count

```php
$pendingCount = Cache::remember('completions.pending', 3600, function() {
    return CompletionSubmission::where('status', 'pending')->count();
});
```

## Customization

### Change Fee Percentage

In `.env`:
```
PLATFORM_SERVICE_FEE_PERCENT=15
```

Or in code:
```php
$platformFee = ($contract->total_amount * config('platform.service_fee_percent')) / 100;
```

### Customize File Types

Edit `CompletionSubmissionAttachment` document types:

```php
public const DOCUMENT_TYPE_INVOICE = 'invoice';
public const DOCUMENT_TYPE_RECEIPT = 'receipt';
```

### Add Document Requirements

Extend validation in `CompletionSubmissionController@store`:

```php
'attachments' => 'required|array|min:2', // Require at least 2 files
```

## Next Steps

1. Run migrations: `php artisan migrate`
2. Add navigation links (see step 2 & 4)
3. Test freelancer submission
4. Test admin verification
5. Verify payment processing
6. Monitor logs and transactions

## Support

For issues or questions, refer to:
- `COMPLETION_SYSTEM.md` - System documentation
- `VERIFICATION_GUIDE.md` - Payment verification
- View application logs: `storage/logs/laravel.log`
