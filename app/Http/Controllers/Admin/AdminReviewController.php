<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Review::with(['contract.job', 'reviewer.profile', 'reviewee.profile', 'reporter', 'moderator']);

        if ($request->filled('status')) {
            if ($request->status === 'flagged') {
                $query->where('is_flagged', true);
            } elseif ($request->status === 'hidden') {
                $query->where('is_public', false);
            } elseif ($request->status === 'visible') {
                $query->where('is_public', true);
            }
        }

        if ($request->filled('role')) {
            $query->where('reviewer_role', $request->role);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('comment', 'like', "%{$q}%")
                    ->orWhereHas('reviewer', fn($uq) => $uq->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('reviewee', fn($uq) => $uq->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('contract', fn($cq) => $cq->where('contract_number', 'like', "%{$q}%"));
            });
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();

        $summary = [
            'total' => Review::count(),
            'flagged' => Review::where('is_flagged', true)->count(),
            'hidden' => Review::where('is_public', false)->count(),
            'avg_rating' => (float) Review::avg('rating_overall'),
        ];

        return view('admin.reviews.index', compact('reviews', 'summary'));
    }

    public function show(Review $review)
    {
        $review->load(['contract.job', 'reviewer.profile', 'reviewee.profile', 'reporter', 'moderator']);

        return view('admin.reviews.show', compact('review'));
    }

    public function hide(Request $request, Review $review)
    {
        $validated = $request->validate([
            'note' => 'required|string|min:5|max:1000',
        ]);

        $review->update([
            'is_public' => false,
            'is_flagged' => false,
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
            'moderation_notes' => $validated['note'],
        ]);

        AuditLogService::log('review.hidden', $review, notes: $validated['note']);

        return back()->with('success', 'Review has been hidden from public view.');
    }

    public function unhide(Request $request, Review $review)
    {
        $validated = $request->validate([
            'note' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'is_public' => true,
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
            'moderation_notes' => $validated['note'] ?? null,
        ]);

        AuditLogService::log('review.unhidden', $review, notes: $validated['note'] ?? null);

        return back()->with('success', 'Review has been restored to public view.');
    }

    public function resolveReport(Request $request, Review $review)
    {
        $validated = $request->validate([
            'note' => 'required|string|min:5|max:1000',
            'keep_public' => 'sometimes|boolean',
        ]);

        $review->update([
            'is_flagged' => false,
            'is_public' => $request->boolean('keep_public', true),
            'reported_by' => null,
            'reported_at' => null,
            'flag_reason' => null,
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
            'moderation_notes' => $validated['note'],
        ]);

        AuditLogService::log('review.report_resolved', $review, notes: $validated['note']);

        return back()->with('success', 'Reported feedback has been reviewed and resolved.');
    }
}
