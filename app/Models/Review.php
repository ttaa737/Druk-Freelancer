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
        'comment', 'is_public', 'is_flagged', 'flag_reason',
    ];

    protected $casts = [
        'is_public'  => 'boolean',
        'is_flagged' => 'boolean',
    ];

    public function contract(): BelongsTo { return $this->belongsTo(Contract::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewer_id'); }
    public function reviewee(): BelongsTo { return $this->belongsTo(User::class, 'reviewee_id'); }

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
}
