# Comprehensive Verification & Admin System Guide

## 🎯 Verification System Overview

The platform now has a professional two-tier verification system ensuring both freelancers and job posters are properly vetted before conducting business.

### Quick Summary
- ✅ **All users** submit core documents
- ✅ **Role-specific requirements** based on user type
- ✅ **Admin review** with detailed tracking
- ✅ **Clear user notifications** at each step

---

## 📋 Document Requirements by Role

### For FREELANCERS (Service Providers)
**Required to get verified:**
1. **Citizenship ID (CID)** - Bhutanese national ID
2. **Professional License** - Trade license, professional cert, or equivalent qualification

Optional (recommended):
- Tax Clearance Certificate
- Education Certificates
- Other relevant credentials

### For JOB POSTERS (Employers)
**Required to get verified:**
1. **Citizenship ID (CID)** - Bhutanese national ID
2. **Business Registration Number (BRN)** - Business license or registration

Optional (recommended):
- Tax Clearance Certificate
- Other business credentials

---

## 📱 User Workflow

### Step 1: Upload Documents
1. Go to **Profile** → **Verification** tab
2. For each required document:
   - Select document type
   - Enter document number (CID, BRN, License #, etc)
   - Upload clear PDF/JPG/PNG file (max 5 MB)
3. Submit

**Status:** Marked as "Under Review" (pending)

### Step 2: Admin Review
Admin receives notification and reviews documents:
- Checks document clarity and validity
- Verifies data matches profile
- Approves or requests changes

### Step 3: Full Verification
- Once ALL required documents approved → Account marked as "Verified" ✅
- User receives notification with benefits explained
- If missing docs → User notified of remaining requirements

### Step 4: Verification Badge
- Verified badge appears on public profile
- Higher search ranking in job/vendor listings
- Increased client trust and visibility

---

## 🔐 Admin Panel - Verification Management

### Accessing Verification Queue
**Admin Dashboard** → **Verifications**

### View Pending Documents
- Shows all documents pending review
- Filter by: Status, Document Type, User Role
- Quick stats: X Pending | Y Approved

### Review Process
1. Click "Review" button next to pending document
2. **Document Preview** - view uploaded file clearly
3. **User Information** - verify details match profile
4. **Historical Records** - see previous docs and submissions
5. **Take Action:**
   - ✅ **Approve** - Document valid, add optional notes
   - ❌ **Reject** - Specify clear reason for rejection
   - 📝 **Optional Validity Date** - Set when cert expires

### Important Notes
- Each document approval is logged/audited
- User automatically receives notifications
- Document validity can be tracked for expiry
- Rejection reason visible to user in profile

---

## 🔧 Admin User Management

### Suspend User
- **What:** Temporarily disable account
- **Effect:** Account frozen, wallet frozen, cannot conduct business
- **Reason:** Required, shared with user
- **Reversible:** Yes - can reactivate anytime

### Ban User
- **What:** Permanently block account  
- **Effect:** Account deleted from listings, severe restriction
- **Warning:** This is serious and cannot be easily undone
- **Reason:** Required, shows user violated terms
- **Checkbox:** Confirm understanding before banning

### Activate User
- **What:** Restore suspended/banned account to active
- **Effect:** Account fully functional again
- **Note:** Usually requires verification of behavior change

---

## 💬 Messaging System

### How It Works
- Freelancers and Job Posters can now message each other freely
- Messaging used for:
  - Asking clarification questions
  - Negotiating terms
  - Sharing details
  - Building trust before contracting

### Access
1. Click "Messages" in sidebar
2. Start conversation with freelancer/poster
3. Both parties can see full chat history
4. Messages are encrypted and archived

**Note:** Messaging flow recently fixed - fresh start for all users

---

## ✅ Verification Status Reference

| Status | Meaning | User Can | Duration |
|--------|---------|----------|----------|
| **Unverified** | No docs submitted | Browse, limited visibility | Until submission |
| **Pending** | Docs submitted, under review | Browse, messaging OK | 1-2 business days |
| **Verified** | All required docs approved | Full features, premium access | Ongoing |
| **Rejected** | Docs not accepted | Resubmit corrected docs | Can reapply immediately |

---

## 🎓 Best Practices

### For Users
- ✅ Provide clear, high-quality document scans
- ✅ Ensure all 4 corners visible, not blurred
- ✅ Match business info exactly to profile
- ✅ Resubmit immediately if rejected
- ✅ Message posters/freelancers once verified

### For Admins
- ✅ Be thorough but fair in reviews
- ✅ Provide specific rejection reasons
- ✅ Add notes for complex cases
- ✅ Verify document number matches CID
- ✅ Track fraudulent users for banning

---

## 🚀 What's New (v2.0)

### Enhanced Features
1. **Role-based requirements** - Freelancer vs Job Poster
2. **Modal-based dialogs** - Professional UI for actions
3. **Better error handling** - Graceful degradation
4. **Audit logging** - All decisions tracked
5. **Incomplete notifications** - Guide users to finish
6. **Document expiry** - Optional validity tracking

### Database Improvements
- Added `is_required` field for document tracking
- Added `valid_until` field for certificate expiry
- Added `verification_rejected_reason` on users
- Added `last_verification_attempt` timestamp

### Fixed Issues
- ✅ Admin ban functionality now modal-based (more reliable)
- ✅ Conversation schema fixed (messaging works fully)
- ✅ Verification rejection uses correct field names
- ✅ Professional inline rejection forms in admin panel

---

## 📞 Support & Troubleshooting

### Users can't message each other
**Fix:** Clear browser cache, schema migration applied
**Status:** ✅ RESOLVED

### Admin ban not working  
**Fix:** Updated to modal-based form with validation
**Status:** ✅ RESOLVED

### Documents not uploading
**Check:**
- File size < 5 MB
- Format is PDF/JPG/PNG
- File is readable (not corrupted)
- Browser allows pop-ups & uploads

### Verification stuck "pending"
**Note:** Normal! Reviews typically take 1-2 business days
- Check email for notifications
- Can message admin if urgent

---

## 🔗 Quick Links

- **User Profile Edit** - /profile/edit
- **Admin Dashboard** - /admin
- **Verification Queue** - /admin/verifications
- **User Management** - /admin/users
- **Settings** - /settings

---

**Last Updated:** March 19, 2026  
**Version:** 2.0 - Professional Verification System
