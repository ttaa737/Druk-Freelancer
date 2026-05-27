<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Review;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List reviews received by a user.
     */
    public function index(User $user)
    {
        $reviews = Review::where('reviewee_id', $user->id)
            ->where('is_public', true)
            ->with('reviewer.profile')
            ->latest()
            ->paginate(10);

        return view('reviews.index', compact('user', 'reviews'));
    }

    /**
     * Show the review form for a completed contract.
     */
    public function create(Contract $contract)
    {
        $user = Auth::user();

        // Only parties of the contract may review
        abort_if(
            $contract->poster_id !== $user->id && $contract->freelancer_id !== $user->id,
            403
        );
        abort_unless($contract->status === 'completed', 403, 'You can only review completed contracts.');

        // Check if this user has already left a review
        $alreadyReviewed = Review::where('contract_id', $contract->id)
            ->where('reviewer_id', $user->id)
            ->exists();

        abort_if($alreadyReviewed, 403, 'You have already submitted a review for this contract.');

        $reviewee = $user->id === $contract->poster_id
            ? $contract->freelancer
            : $contract->poster;

        $reviewerRole = $user->id === $contract->poster_id ? 'poster' : 'freelancer';
        $revieweeRole = $reviewerRole === 'poster' ? 'freelancer' : 'poster';

        return view('reviews.create', compact('contract', 'reviewee', 'reviewerRole', 'revieweeRole'));
    }

    /**
     * Store a new review after contract completion.
     */
    public function store(Request $request, Contract $contract)
    {
        $user = Auth::user();

        abort_if(
            $contract->poster_id !== $user->id && $contract->freelancer_id !== $user->id,
            403
        );
        abort_unless($contract->status === 'completed', 403, 'You can only review completed contracts.');

        $alreadyReviewed = Review::where('contract_id', $contract->id)
            ->where('reviewer_id', $user->id)
            ->exists();
        abort_if($alreadyReviewed, 403, 'You have already submitted a review for this contract.');

        $isPosterReviewer = $user->id === $contract->poster_id;

        $validated = $request->validate([
            'rating_overall'        => 'required|integer|min:1|max:5',
            'rating_communication'  => 'nullable|integer|min:1|max:5',
            'rating_quality'        => 'nullable|integer|min:1|max:5',
            'rating_timeliness'     => 'nullable|integer|min:1|max:5',
            'rating_professionalism' => 'nullable|integer|min:1|max:5',
            'rating_payment_behavior' => 'nullable|integer|min:1|max:5',
            'rating_project_clarity' => 'nullable|integer|min:1|max:5',
            'comment'               => 'nullable|string|max:2000',
            'is_anonymous'          => 'sometimes|boolean',
        ]);

        if ($isPosterReviewer) {
            $request->validate([
                'rating_communication' => 'required|integer|min:1|max:5',
                'rating_quality' => 'required|integer|min:1|max:5',
                'rating_professionalism' => 'required|integer|min:1|max:5',
                'rating_timeliness' => 'required|integer|min:1|max:5',
            ]);
        } else {
            $request->validate([
                'rating_payment_behavior' => 'required|integer|min:1|max:5',
                'rating_project_clarity' => 'required|integer|min:1|max:5',
                'rating_communication' => 'required|integer|min:1|max:5',
            ]);
        }

        $revieweeId = $user->id === $contract->poster_id
            ? $contract->freelancer_id
            : $contract->poster_id;

        $review = Review::create([
            'contract_id'            => $contract->id,
            'reviewer_id'            => $user->id,
            'reviewee_id'            => $revieweeId,
            'reviewer_role'          => $isPosterReviewer ? 'poster' : 'freelancer',
            'rating_overall'         => $validated['rating_overall'],
            'rating_communication'   => $validated['rating_communication'] ?? null,
            'rating_quality'         => $validated['rating_quality'] ?? null,
            'rating_timeliness'      => $validated['rating_timeliness'] ?? null,
            'rating_professionalism' => $validated['rating_professionalism'] ?? null,
            'rating_payment_behavior' => $validated['rating_payment_behavior'] ?? null,
            'rating_project_clarity' => $validated['rating_project_clarity'] ?? null,
            'comment'                => $validated['comment'] ?? null,
            'is_anonymous'           => $request->boolean('is_anonymous'),
        ]);

        // Update the reviewee's average rating on their profile
        $this->updateProfileRating($revieweeId);

        AuditLogService::log('review.created', $review);

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Review submitted successfully. Thank you for your feedback!');
    }

    public function report(Request $request, Review $review)
    {
        $user = Auth::user();

        abort_if($review->reviewer_id === $user->id, 422, 'You cannot report your own review.');

        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);

        $review->update([
            'is_flagged' => true,
            'flag_reason' => $validated['reason'],
            'reported_by' => $user->id,
            'reported_at' => now(),
        ]);

        AuditLogService::log('review.reported', $review, notes: $validated['reason']);

        return back()->with('success', 'Review has been reported to the admin team for moderation.');
    }

    // ─── Private ─────────────────────────────────────────────────────────────

    private function updateProfileRating(int $userId): void
    {
        $avg = Review::where('reviewee_id', $userId)
            ->where('is_public', true)
            ->avg('rating_overall');
        $count = Review::where('reviewee_id', $userId)
            ->where('is_public', true)
            ->count();

        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $userId],
            [
                'average_rating' => round($avg ?: 0, 2),
                'total_reviews' => $count,
            ]
        );
    }
}

