<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id', 'reviewer_id', 'reviewee_id', 'reviewer_role',
        'rating_overall', 'rating_communication', 'rating_quality',
        'rating_timeliness', 'rating_professionalism',
        'rating_payment_behavior', 'rating_project_clarity',
        'comment', 'is_anonymous', 'is_public',
        'is_flagged', 'flag_reason', 'reported_by', 'reported_at',
        'moderated_by', 'moderated_at', 'moderation_notes',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_public'  => 'boolean',
        'is_flagged' => 'boolean',
        'reported_at' => 'datetime',
        'moderated_at' => 'datetime',
    ];

    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewer_id'); }
    public function reviewee(): BelongsTo { return $this->belongsTo(User::class, 'reviewee_id'); }
    public function reporter(): BelongsTo { return $this->belongsTo(User::class, 'reported_by'); }
    public function moderator(): BelongsTo { return $this->belongsTo(User::class, 'moderated_by'); }

    public function getAverageRatingsAttribute(): float
    {
        $ratings = array_filter([
            $this->rating_overall,
            $this->rating_communication,
            $this->rating_quality,
            $this->rating_timeliness,
            $this->rating_professionalism,
        ]);
        return count($ratings) ? round(array_sum($ratings) / count($ratings), 2) : 0;
    }

    // Backward-compatible aliases for old view/controller field names.
    public function getOverallRatingAttribute(): ?int { return $this->rating_overall; }
    public function getCommunicationRatingAttribute(): ?int { return $this->rating_communication; }
    public function getQualityRatingAttribute(): ?int { return $this->rating_quality; }
    public function getTimelinessRatingAttribute(): ?int { return $this->rating_timeliness; }
    public function getProfessionalismRatingAttribute(): ?int { return $this->rating_professionalism; }
}
