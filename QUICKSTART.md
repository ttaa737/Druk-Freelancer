# Quick Start Guide: Completion System

## In 5 Minutes

### 1. Run Migration
```bash
php artisan migrate
```
This creates the necessary database tables.

### 2. Add Route Import
Already done in `routes/web.php` ✓

### 3. Test Freelancer Submit
1. Login as freelancer
2. Go to `/completion/{contract_id}/submit`
3. Fill form and upload files
4. Submit

### 4. Test Admin Verify
1. Login as admin
2. Go to `/admin/completions/`
3. Click "Review" on submission
4. Click "Verify & Process Payment"
5. Check wallet balances

---

## Visual Integration Points

### Add to Contract View
In `resources/views/contracts/show.blade.php`, after milestones section:

```blade
@if($contract->status === 'active' && auth()->id() === $contract->freelancer_id)
<div class="mt-6 p-4 bg-blue-50 border-2 border-blue-200 rounded-lg">
    <h3 class="font-bold text-blue-900 mb-3">Ready to Complete?</h3>
    <a href="{{ route('completion.create', $contract) }}" 
       class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Submit Completion Evidence
    </a>
</div>
@endif
```

### Add to Navigation
In your main menu (header/sidebar):

```blade
@auth
    @if(auth()->user()->is_freelancer)
    <a href="{{ route('completion.my-submissions') }}">My Submissions</a>
    @endif
    
    @if(auth()->user()->is_admin)
    <a href="{{ route('admin.completions.index') }}">Pending Completions</a>
    @endif
@endauth
```

### Add to Admin Dashboard
```blade
<!-- New card for pending completions -->
<div class="bg-white p-4 rounded shadow">
    <p class="text-gray-600">Pending Review</p>
    <p class="text-3xl font-bold">{{ \App\Models\CompletionSubmission::where('status', 'pending')->count() }}</p>
    <a href="{{ route('admin.completions.index') }}" class="text-blue-600">View All</a>
</div>
```

---

## Key Files to Know

| File | Purpose |
|------|---------|
| `app/Models/CompletionSubmission.php` | Main submission model |
| `app/Services/PaymentProcessingService.php` | Handles all payments |
| `app/Http/Controllers/CompletionSubmissionController.php` | Freelancer interface |
| `app/Http/Controllers/Admin/AdminCompletionController.php` | Admin interface |
| `routes/web.php` | Routes (already configured) |
| `resources/views/completion/create.blade.php` | Freelancer form |
| `resources/views/admin/completions/show.blade.php` | Admin review |

---

## Database Schema Quick Reference

### CompletionSubmission
```sql
- id
- contract_id (FK)
- freelancer_id (FK)
- submission_notes
- status: pending | verified | rejected | payment_processed
- submitted_at, verified_at, rejected_at, payment_processed_at
- verified_by (FK to users)
- rejection_reason
```

### CompletionSubmissionAttachment
```sql
- id
- completion_submission_id (FK)
- file_name, file_path, file_type, file_size
- description
- document_type: evidence | report | deliverable | screenshot | video | other
```

### Contracts (Updated)
```sql
- completion_status: pending | submitted | verified | rejected | paid
- completion_submitted_at
```

---

## Payment Flow Diagram

```
┌─────────────────────────┐
│  Freelancer Submits     │
│  Completion Evidence    │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│  Admin Reviews Files    │
└────────────┬────────────┘
             │
        ┌────┴────┐
        │          │
        ▼          ▼
     ✓ Approve   ✗ Reject
        │          │
        │          └──────► Freelancer Resubmits
        │
        ▼
┌─────────────────────────┐
│ Process Payment         │
├─────────────────────────┤
│ Debit Job Poster        │ (Total Amount)
│ Credit Freelancer       │ (Freelancer Share)
│ Credit Admin            │ (Platform Fee)
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│ Payment Complete ✓      │
│ Freelancer Receives Pay │
└─────────────────────────┘
```

---

## Common Tasks

### Verify Payment Processed
```bash
php artisan tinker

# Check freelancer received payment
Freelancer::with('wallet')->find(1)->wallet->available_balance

# Check admin received fee
Admin::with('wallet')->find(1)->wallet->available_balance

# Check poster was charged
Poster::with('wallet')->find(1)->wallet->available_balance

# View transaction
Transaction::where('contract_id', 1)->get()
```

### Check Submission Status
```bash
php artisan tinker

$submission = CompletionSubmission::find(1);
$submission->status        # pending, verified, rejected, payment_processed
$submission->contract      # Associated contract
$submission->attachments   # Uploaded files
$submission->verifiedBy    # Admin who verified
```

### Reset for Testing
```bash
# Be careful! This deletes all submissions
php artisan tinker
CompletionSubmission::truncate();
CompletionSubmissionAttachment::truncate();
```

---

## Troubleshooting

### Issue: File upload fails
**Solution**: Check storage permissions
```bash
chmod -R 755 storage/
chown -R www-data:www-data storage/
```

### Issue: Payment not showing in wallet
**Solution**: Check transaction table
```bash
php artisan tinker
Transaction::latest()->limit(5)->get()
```

### Issue: Authorization denied
**Solution**: Verify user roles
```bash
php artisan tinker
auth()->user()->is_admin  # Should be true for admin
auth()->user()->is_freelancer  # Should be true for freelancer
```

### Issue: Routes not found
**Solution**: Clear route cache
```bash
php artisan route:clear
php artisan route:cache
```

---

## URLs Cheat Sheet

### For Freelancers
```
/completion/my-submissions              - See all submissions
/completion/{contract_id}/submit        - Submit completion
/completion/submissions/{submission_id} - View submission details
```

### For Admin
```
/admin/completions/                     - Dashboard
/admin/completions/{submission_id}      - Review submission
/admin/completions/stats                - Statistics
```

---

## API Response Examples

### Submit Completion
```json
POST /completion/{contract_id}/submit
Response:
{
    "success": true,
    "message": "Completion evidence submitted successfully.",
    "submission_id": 42,
    "redirect": "/contracts/5"
}
```

### Verify Completion
```json
POST /admin/completions/{submission_id}/verify
Body:
{
    "verification_notes": "All looks good!"
}

Response:
{
    "success": true,
    "message": "Completion verified successfully. Payment has been processed."
}
```

### Reject Completion
```json
POST /admin/completions/{submission_id}/reject
Body:
{
    "rejection_reason": "Screenshots are unclear, please provide better quality images."
}

Response:
{
    "success": true,
    "message": "Submission rejected. Freelancer will be notified."
}
```

---

## Configuration Options

In `.env`:
```
# Platform fee percentage
PLATFORM_SERVICE_FEE_PERCENT=10

# Platform currency
PLATFORM_CURRENCY=Nu

# File storage
FILESYSTEM_DISK=private

# Max upload size (in bytes)
FILE_MAX_SIZE=10485760  # 10MB
```

In `config/platform.php`:
```php
'service_fee_percent'  => (float) env('PLATFORM_SERVICE_FEE_PERCENT', 10),
'currency'             => 'Nu',
'min_withdrawal'       => (float) env('PLATFORM_MIN_WITHDRAWAL', 500),
```

---

## Testing Checklist

- [ ] Freelancer can submit
- [ ] Admin can view submission
- [ ] Payment processes on approve
- [ ] Freelancer wallet updated
- [ ] Admin fee added to admin wallet
- [ ] Job poster charged
- [ ] Transaction recorded
- [ ] Resubmission works after rejection
- [ ] Files can be downloaded
- [ ] Status updates reflect reality

---

## Next Steps After Installation

1. ✅ Run migration
2. ✅ Add UI elements (see Visual Integration Points)
3. ✅ Test complete flow
4. ✅ Verify payment processing
5. ✅ Review transaction logs
6. ✅ Deploy to production
7. ✅ Monitor and maintain

---

## Performance Tips

```php
// Load relationships efficiently
$submissions = CompletionSubmission::with('contract', 'freelancer', 'attachments')->get();

// Use pagination for large lists
$submissions = CompletionSubmission::paginate(20);

// Cache dashboard stats
Cache::remember('pending_submissions', 3600, function() {
    return CompletionSubmission::where('status', 'pending')->count();
});
```

---

## Support Resources

- **Full Documentation**: See `COMPLETION_SYSTEM.md`
- **Integration Guide**: See `INTEGRATION_GUIDE.md`
- **Implementation Details**: See `IMPLEMENTATION_SUMMARY.md`
- **Code**: Well-commented source files
- **Logs**: Check `storage/logs/laravel.log`

---

## Commands Quick Ref

```bash
# Database
php artisan migrate
php artisan migrate:rollback
php artisan migrate:refresh

# Cache/Config
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Storage
php artisan storage:link
chmod -R 755 storage/

# Debug
php artisan tinker
tail -f storage/logs/laravel.log
```

---

**You're ready to go!** 🚀

For detailed questions, refer to the full documentation files.
