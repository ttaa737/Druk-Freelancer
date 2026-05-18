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
        $query = \App\Models\User::with('verificationDocuments');

        // Filter by status of user's documents
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereHas('verificationDocuments', function ($q) {
                    $q->where('status', 'pending');
                });
            } elseif ($request->status === 'approved') {
                $query->whereHas('verificationDocuments', function ($q) {
                    $q->where('status', 'approved');
                });
            } elseif ($request->status === 'rejected') {
                $query->whereHas('verificationDocuments', function ($q) {
                    $q->where('status', 'rejected');
                });
            }
        } else {
            // Default: show users with pending documents
            $query->whereHas('verificationDocuments', function ($q) {
                $q->where('status', 'pending');
            });
        }

        // Group by unique users
        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.verifications.index', compact('users'));
    }

    public function show(\App\Models\User $user)
    {
        $user->load('verificationDocuments', 'profile');

        return view('admin.verifications.show', compact('user'));
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

        // Require CID plus at least one secondary document for full verification
        // Trust the admin's judgment when approving documents
        $cidApproved = $user->verificationDocuments()
            ->where('document_type', 'cid')
            ->where('status', 'approved')
            ->exists();

        // Count all approved documents (excluding CID)
        $otherApprovedDocs = $user->verificationDocuments()
            ->where('document_type', '!=', 'cid')
            ->where('status', 'approved')
            ->count();

        $meetsVerification = $cidApproved && $otherApprovedDocs > 0;
        $missingDocs = [];

        if (!$cidApproved) {
            $missingDocs[] = 'Citizenship ID (CID)';
        }

        if ($otherApprovedDocs === 0) {
            $missingDocs[] = 'At least one supporting document (BRN, Professional License, etc.)';
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

