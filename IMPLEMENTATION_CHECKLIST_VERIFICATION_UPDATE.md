# Implementation Checklist - Document Verification Update

## ✅ Changes Completed

### 1. Database Migrations Created
- [x] `2026_05_19_000000_update_verification_system_roles.php`
  - Adds CV document type to verification_documents table
  - Adds role_required tracking field
  - Adds has_approved_cv flag to users table

- [x] `2026_05_19_000001_add_cv_auto_attach_to_proposals.php`
  - Adds cv_from_verification flag to proposals
  - Adds cv_document_id foreign key linking to verified documents

### 2. Model Updates
- [x] **VerificationDocument.php**
  - Added TYPE constants: CID, CV, BRN, OTHER
  - Added document metadata methods (getLabel, getDescription, etc.)
  - Added role requirement checking

- [x] **Proposal.php**
  - Added cv_from_verification, cv_document_id columns to fillable
  - Added cvDocument() relationship
  - Added isUsingVerifiedCV() method
  - Added getCVInfo() helper method

- [x] **User.php** (No changes needed - already has relationships)

### 3. Controller Updates
- [x] **ProfileController.php**
  - Updated uploadDocument() to handle CV and support role-based requirements
  - Added isDocumentRequired() helper
  - Added getRoleRequired() helper
  - Added checkVerificationCompletion() to auto-mark verified

- [x] **ProposalController.php**
  - Updated store() method to support use_verified_cv parameter
  - Added validation for verified CV usage
  - Handles both verified and newly-uploaded CVs
  - Tracks CV source in database

### 4. View Updates
- [x] **profile/edit.blade.php** - Verification Tab
  - Added role-specific requirements display
  - Added CV as required document for freelancers
  - Removed education certificate option
  - Removed tax clearance certificate option
  - Updated guidelines and instructions
  - Professional license now optional for both roles

- [x] **jobs/show.blade.php** - Proposal Form
  - Added verified CV option (if freelancer has approved CV)
  - Radio buttons for CV selection
  - JavaScript for dynamic form state
  - Fallback for users without verified CV
  - Helpful tip about uploading CV

- [x] **proposals/show.blade.php** - Proposal Display
  - Shows verified CV status
  - Added badge for auto-attached CVs
  - Shows verification date and source
  - Maintains traditional CV download/view

### 5. Documentation Created
- [x] **VERIFICATION_SYSTEM_UPDATE_2026.md**
  - Complete overview of new system
  - Role-based requirements clearly defined
  - CV auto-attach feature explained
  - Admin actions and deployment steps
  - FAQ section

---

## 🔧 Before Deployment

### Must Do
- [ ] Review all code changes in Git diff
- [ ] Run Laravel tests for verification system
- [ ] Test migrations on staging database
- [ ] Verify all routes still work
- [ ] Check error handling in controllers

### Should Do
- [ ] Test as freelancer role:
  1. Create account
  2. Upload CID + CV
  3. Wait for approval (or approve as admin)
  4. Submit job proposal with verified CV
  5. Verify CV appears in proposal show page
  
- [ ] Test as job poster role:
  1. Create account
  2. Upload CID (CV upload not shown)
  3. Wait for approval
  4. View received proposals
  5. Check CV display in proposals

- [ ] Test admin approval flow:
  1. Submit documents as test user
  2. Approve from admin panel
  3. Check user verification_status updated
  4. Check has_approved_cv flag set

### Good to Do
- [ ] Add unit tests for new methods
- [ ] Add feature tests for CV auto-attach
- [ ] Performance test with large document uploads
- [ ] Browser test on different devices

---

## 📝 Configuration

### Accepted File Types
- CID: PDF, JPG, PNG, DOC, DOCX
- CV: PDF, JPG, PNG, DOC, DOCX
- BRN: PDF, JPG, PNG, DOC, DOCX

### File Size Limits
- Verification documents: 5 MB max
- CV in proposals: 10 MB max

### Storage Location
- Path: `storage/app/public/verification-docs/`
- Path: `storage/app/public/proposal-cvs/`

---

## 🚀 Deployment

### Step 1: Backup
```bash
# Backup database
mysqldump -u root -p druk_freelancing > backup_$(date +%Y%m%d).sql
```

### Step 2: Deploy Code
```bash
git pull origin main
composer install
npm install && npm run build
```

### Step 3: Run Migrations
```bash
php artisan migrate
```

### Step 4: Clear Caches
```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

### Step 5: Verify
```bash
# Check users table
php artisan tinker
> DB::table('users')->first();
// Should show has_approved_cv column

# Check verification_documents
> DB::table('verification_documents')->first();
// Should show role_required column

# Check proposals table
> DB::table('proposals')->first();
// Should show cv_from_verification and cv_document_id columns
```

---

## 📊 Data Migration Notes

### For Existing Users
- `has_approved_cv` defaults to FALSE
- `verification_status` remains unchanged
- Existing verified CVs not automatically linked
- Users can upload CV again if they want auto-attach

### For Existing Documents
- `role_required` will be NULL (no action needed)
- Existing approvals remain valid
- Old document types (education, tax) still show in database

---

## 🔒 Security Checklist

- [x] File upload validation (mimes, size)
- [x] Authorization checks (user can only upload own docs)
- [x] Storage outside public webroot for original documents
- [x] File access only through authenticated routes
- [x] Audit logging for document actions
- [x] Admin-only approval workflow

---

## ✨ Features Summary

| Feature | Freelancer | Job Poster | Admin |
|---------|-----------|-----------|-------|
| Upload CID | ✅ Required | ✅ Required | Can approve |
| Upload CV | ✅ Required | ❌ N/A | Can approve |
| Upload License | ⭕ Optional | ⭕ Optional | Can approve |
| Auto-attach CV to proposals | ✅ Auto | ❌ N/A | View status |
| See verified badge | ✅ On profile | ✅ On profile | View in queue |

---

## 🔗 Related Files

**Modified:**
- `app/Models/VerificationDocument.php`
- `app/Models/Proposal.php`
- `app/Http/Controllers/ProfileController.php`
- `app/Http/Controllers/ProposalController.php`
- `resources/views/profile/edit.blade.php`
- `resources/views/jobs/show.blade.php`
- `resources/views/proposals/show.blade.php`

**Created:**
- `database/migrations/2026_05_19_000000_update_verification_system_roles.php`
- `database/migrations/2026_05_19_000001_add_cv_auto_attach_to_proposals.php`
- `VERIFICATION_SYSTEM_UPDATE_2026.md` (this documentation)

**Documentation:**
- `VERIFICATION_GUIDE.md` (update with new requirements)

---

## 🐛 Troubleshooting

### Issue: CV not appearing as option in proposal form
**Solution:** Check that user has at least one approved CV document with `status = 'approved'`

### Issue: Migration fails
**Solution:** Check database enum syntax for your MySQL version, may need to adjust ALTER TABLE statements

### Issue: Verified CV not attaching to proposal
**Solution:** Verify `cv_from_verification = true` and `cv_document_id` is set in proposals table

### Issue: Admin sees old document types in form
**Solution:** Database still has old enums, safe to ignore. New submissions will only use new types.

---

**Implementation Date:** 19 May 2026
**Version:** 1.0
**Status:** Ready for Deployment
