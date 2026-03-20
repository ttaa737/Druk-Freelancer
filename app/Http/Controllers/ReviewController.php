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
            $contract->job_poster_id !== $user->id && $contract->freelancer_id !== $user->id,
            403
        );
        abort_unless($contract->status === 'completed', 403, 'You can only review completed contracts.');

        // Check if this user has already left a review
        $alreadyReviewed = Review::where('contract_id', $contract->id)
            ->where('reviewer_id', $user->id)
            ->exists();

        abort_if($alreadyReviewed, 403, 'You have already submitted a review for this contract.');

        $reviewee = $user->id === $contract->job_poster_id
            ? $contract->freelancer
            : $contract->jobPoster;

        return view('reviews.create', compact('contract', 'reviewee'));
    }

    /**
     * Store a new review after contract completion.
     */
    public function store(Request $request, Contract $contract)
    {
        $user = Auth::user();

        abort_if(
            $contract->job_poster_id !== $user->id && $contract->freelancer_id !== $user->id,
            403
        );
        abort_unless($contract->status === 'completed', 403, 'You can only review completed contracts.');

        $alreadyReviewed = Review::where('contract_id', $contract->id)
            ->where('reviewer_id', $user->id)
            ->exists();
        abort_if($alreadyReviewed, 403, 'You have already submitted a review for this contract.');

        $validated = $request->validate([
            'overall_rating'        => 'required|integer|min:1|max:5',
            'communication_rating'  => 'nullable|integer|min:1|max:5',
            'quality_rating'        => 'nullable|integer|min:1|max:5',
            'timeliness_rating'     => 'nullable|integer|min:1|max:5',
            'professionalism_rating' => 'nullable|integer|min:1|max:5',
            'comment'               => 'nullable|string|max:2000',
            'is_anonymous'          => 'sometimes|boolean',
        ]);

        $revieweeId = $user->id === $contract->job_poster_id
            ? $contract->freelancer_id
            : $contract->job_poster_id;

        $review = Review::create([
            'contract_id'            => $contract->id,
            'reviewer_id'            => $user->id,
            'reviewee_id'            => $revieweeId,
            'reviewer_role'          => $user->id === $contract->job_poster_id ? 'job_poster' : 'freelancer',
            'overall_rating'         => $validated['overall_rating'],
            'communication_rating'   => $validated['communication_rating'] ?? null,
            'quality_rating'         => $validated['quality_rating'] ?? null,
            'timeliness_rating'      => $validated['timeliness_rating'] ?? null,
            'professionalism_rating' => $validated['professionalism_rating'] ?? null,
            'comment'                => $validated['comment'] ?? null,
            'is_anonymous'           => $request->boolean('is_anonymous'),
        ]);

        // Update the reviewee's average rating on their profile
        $this->updateProfileRating($revieweeId);

        AuditLogService::log('review.created', $review);

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Review submitted successfully. Thank you for your feedback!');
    }

    // ─── Private ─────────────────────────────────────────────────────────────

    private function updateProfileRating(int $userId): void
    {
        $avg = Review::where('reviewee_id', $userId)->avg('overall_rating');
        $count = Review::where('reviewee_id', $userId)->count();

        User::find($userId)?->profile()?->update([
            'rating'        => round($avg, 2),
            'total_reviews' => $count,
        ]);
    }
}

