<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationDocument;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = VerificationDocument::with('user.profile');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending');
        }

        $documents = $query->latest()->paginate(20)->withQueryString();

        return view('admin.verifications.index', compact('documents'));
    }

    public function show(VerificationDocument $document)
    {
        $document->load('user.profile');

        return view('admin.verifications.show', compact('document'));
    }

    public function approve(Request $request, VerificationDocument $document)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
            'valid_until' => 'nullable|date|after:today',
        ]);

        $document->update([
            'status'       => 'approved',
            'reviewed_by'  => Auth::id(),
            'reviewed_at'  => now(),
            'admin_notes'  => $request->notes,
            'valid_until'  => $request->valid_until,
        ]);

        // Get user and check if they should be marked as verified
        $user = $document->user;
        $user->update(['last_verification_attempt' => now()]);

        // Require CID plus role-specific secondary document for full verification
        $cidApproved = $user->verificationDocuments()
            ->where('document_type', 'cid')
            ->where('status', 'approved')
            ->exists();

        $meetsVerification = false;
        $missingDocs = [];

        if ($user->role === 'job_poster') {
            // Job posters require: CID + BRN (Business Registration Number)
            $brnApproved = $user->verificationDocuments()
                ->where('document_type', 'brn')
                ->where('status', 'approved')
                ->exists();
            
            if (!$cidApproved) $missingDocs[] = 'Citizenship ID (CID)';
            if (!$brnApproved) $missingDocs[] = 'Business Registration Number (BRN)';
            
            $meetsVerification = $cidApproved && $brnApproved;
        } elseif ($user->role === 'freelancer') {
            // Freelancers require: CID + Professional License
            $licenseApproved = $user->verificationDocuments()
                ->where('document_type', 'license')
                ->where('status', 'approved')
                ->exists();
            
            if (!$cidApproved) $missingDocs[] = 'Citizenship ID (CID)';
            if (!$licenseApproved) $missingDocs[] = 'Professional License';
            
            $meetsVerification = $cidApproved && $licenseApproved;
        } else {
            // Fallback: require CID only
            if (!$cidApproved) $missingDocs[] = 'Citizenship ID (CID)';
            $meetsVerification = $cidApproved;
        }

        if ($meetsVerification && $user->verification_status !== 'verified') {
            $user->update([
                'verification_status' => 'verified',
                'verification_rejected_reason' => null,
            ]);
            NotificationService::accountVerified($user);
        } elseif (!$meetsVerification) {
            // Notify user of additional requirements if verification is incomplete
            NotificationService::verificationIncomplete($user, $missingDocs);
        }

        // Notify user about document approval
        NotificationService::verificationApproved($user, $document->document_type);

        AuditLogService::log('document.approved', $document, userId: Auth::id(), notes: $request->notes);

        return back()->with('success', 'Document approved and user notified.' . (!$meetsVerification ? ' User still needs: ' . implode(', ', $missingDocs) : ' User is now fully verified!'));
    }

    public function reject(Request $request, VerificationDocument $document)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $document->update([
            'status'           => 'rejected',
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
            'rejection_reason' => $request->reason,
        ]);

        $user = $document->user;
        $user->update([
            'verification_status' => 'rejected',
            'verification_rejected_reason' => $request->reason,
            'last_verification_attempt' => now(),
        ]);

        // Notify user about rejection with clear reason
        NotificationService::verificationRejected(
            $user, 
            $document->document_type, 
            $request->reason
        );

        AuditLogService::log('document.rejected', $document, userId: Auth::id(), notes: $request->reason);

        return back()->with('success', 'Document rejected and user has been notified. They can resubmit after addressing the issues.');
    }
}

