# Document Verification System - Updated Requirements

## Overview
This document outlines the new, streamlined document verification system implemented on **19 May 2026**.

---

## 📋 New Document Requirements

### For ALL Users
- **Citizenship ID (CID)** - ✅ **REQUIRED**
  - Bhutanese national identification
  - Clear scans/photos of both front and back
  - Used for platform-wide identity verification

### For FREELANCERS (Service Providers)
- **Citizenship ID (CID)** - ✅ **REQUIRED**
- **Curriculum Vitae (CV)** - ✅ **REQUIRED**
  - PDF or DOC format
  - Automatically attached to all job proposals for transparency
  - Updated annually or as needed
- **Business License/BRN** - ⭕ **OPTIONAL**
  - For freelancers operating as businesses
  - Increases trust and credibility

### For JOB POSTERS (Employers)
- **Citizenship ID (CID)** - ✅ **REQUIRED**
- **Business License / BRN** - ⭕ **OPTIONAL**
  - Business Registration Number
  - Professional license or equivalent
  - Increases client trust

---

## ✂️ Documents Removed

The following documents are **NO LONGER REQUIRED** or supported:
- ❌ **Professional License** (previously required for all users)
- ❌ **Education Certificate** (previously optional)
- ❌ **Tax Clearance Certificate** (previously optional)

These were removed to simplify the verification process while maintaining security standards.

---

## 🔄 CV Auto-Attachment Feature

### How It Works

When freelancers submit a job proposal:

1. **If Verified CV Exists**: 
   - Radio option appears: "Use My Verified CV"
   - One click to attach their approved CV automatically
   - No need to upload again

2. **If No Verified CV**:
   - Must upload a CV with the proposal (traditional method)
   - Suggestion appears: "Consider uploading a CV to account verification"

3. **For Job Posters**:
   - All proposals display CV information clearly
   - Badge indicates "From verified account documents" if auto-attached
   - CV download/view always available

### Benefits

| Benefit | Freelancer | Job Poster |
|---------|-----------|-----------|
| **Speed** | Submit proposals faster | See all CVs immediately |
| **Consistency** | Same professional CV every time | Know they're verified |
| **Trust** | Verified documents build credibility | Increased transparency |
| **Transparency** | One approved CV for all | Reduces document fraud |

---

## 🔐 Updated User Flow

### Step 1: Account Creation
User chooses role: **Freelancer** or **Job Poster**

### Step 2: Profile Verification (Required for All)
1. Navigate to **Profile → Verification Tab**
2. Upload **CID** (REQUIRED)
3. For Freelancers: Upload **CV** (REQUIRED)
4. For Job Posters: Optional - Add **Business License**
5. Submit for admin review

### Step 3: Admin Verification
- Admin reviews all documents
- Documents marked: ✅ Approved, ❌ Rejected, ⏳ Pending
- User notified of status

### Step 4: Full Account Verification
**Status:** ✅ Verified when ALL required documents are approved
- Account unlocked for job posting/proposals
- Verified badge appears on profile
- CV automatically attaches to proposals (freelancers only)

---

## 💻 Technical Implementation

### Database Changes

**New Migration:** `2026_05_19_000000_update_verification_system_roles.php`

```php
// New columns added:
- verification_documents.role_required (tracks which roles require each doc)
- verification_documents.is_required (boolean flag)
- users.has_approved_cv (flag for auto-attach feature)

// New migration for proposals: 2026_05_19_000001_add_cv_auto_attach_to_proposals.php
- proposals.cv_from_verification (boolean)
- proposals.cv_document_id (foreign key to verification_documents)
```

### Model Updates

**VerificationDocument.php**
```php
// New constants
const TYPE_CID = 'cid';
const TYPE_CV = 'cv';
const TYPE_BRN = 'brn';
const TYPE_OTHER = 'other';

// New methods for document metadata
getDocumentTypeLabel()
getDocumentDescription()
getDocumentPlaceholder()
getDocumentIcon()
isRequiredForRole($role)
```

**Proposal.php**
```php
// New relationships and methods
cvDocument() // BelongsTo VerificationDocument
isUsingVerifiedCV() // Check if CV is auto-attached
getCVInfo() // Get CV metadata for display
```

### Controller Updates

**ProfileController.php**
- `uploadDocument()` - Enhanced to support CV uploads
- `isDocumentRequired()` - Role-based requirement checking
- `getRoleRequired()` - Track which roles require documents
- `checkVerificationCompletion()` - Auto-mark verified when all docs approved

**ProposalController.php**
- `store()` - Updated to accept `use_verified_cv` parameter
- Handles both verified CV auto-attach and file uploads
- Links proposal to verification document when using verified CV

### View Updates

**profile/edit.blade.php**
- Verification requirements clearly segmented by role
- New CV document option (required for freelancers)
- Professional license moved to optional
- Education and tax certificates removed
- Guidelines updated for new document types

**jobs/show.blade.php**
- Proposal form shows verified CV option (when available)
- Radio buttons: "Use My Verified CV" or "Upload Different CV"
- JavaScript handles dynamic form state
- Tip suggests uploading CV to account for future use

**proposals/show.blade.php**
- CV section displays verification status
- Badge: "From verified account documents" for auto-attached CVs
- Clear explanation of verified document benefits
- Download/view always available

---

## 🚀 Deployment Steps

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Clear Cache
```bash
php artisan cache:clear
php artisan config:cache
```

### 3. Test Verification Flow
- Create freelancer account → Verify with CID + CV
- Create job poster account → Verify with CID only
- Submit proposal → Try using verified CV option

### 4. Validate Admin Panel
- Check admin verification queue for new document types
- Ensure CV uploads work properly
- Test CV auto-attachment in proposals

---

## 📋 Admin Actions Required

### Existing Verified Users
- **No action needed** - Current verifications remain valid
- Professional license docs can be archived
- Existing users won't be asked to re-verify

### For New Verifications
Follow the new checklist:
- ✅ CID - Always required
- ✅ CV - Required for freelancers only
- ⭕ Business License - Optional for either role

### Migration of Pending Submissions
- Submissions for old document types (education, tax clearance) can be:
  - ✅ **Approved** as-is (grandfathered)
  - ❌ **Rejected** with message: "This document type is no longer required"
  - Request: Ask user to submit CID + CV/BRN instead

---

## ❓ FAQ

**Q: What if a freelancer has been verified with the old system?**
A: They remain verified and keep using their old documents. CV auto-attach only works if they uploaded CV as part of verification.

**Q: Can a freelancer use different CVs for different proposals?**
A: Yes! They can either:
- Use verified CV (recommended) - consistent, professional
- Upload a project-specific CV with proposal
- Toggle with radio button on proposal form

**Q: What happens if a freelancer deletes their verified CV?**
A: They can no longer use auto-attach. They must upload CV with each proposal.

**Q: Is the verified CV visible to all job posters?**
A: Only when included with a proposal. The CV itself is not publicly visible in the profile.

**Q: Can job posters require specific documents?**
A: Currently all job posters can see CVs if submitted. Custom requirements can be added per-job as a future enhancement.

---

## 🔄 Rollback (if needed)

To revert to the old system:

```bash
php artisan migrate:rollback --step=2
```

This will remove:
- `2026_05_19_000001_add_cv_auto_attach_to_proposals.php`
- `2026_05_19_000000_update_verification_system_roles.php`

---

## 📞 Support

For questions about the new verification system:
- Admin can contact the dev team
- Users can reach support through platform help
- Documentation: This file + inline code comments

**Last Updated:** 19 May 2026
**System Version:** 1.1
