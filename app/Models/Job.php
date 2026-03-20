<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'poster_id', 'category_id', 'title', 'slug', 'description', 'requirements',
        'type', 'budget_min', 'budget_max', 'duration_days', 'experience_level',
        'dzongkhag', 'remote_ok', 'status', 'is_featured', 'expires_at',
    ];

    protected $casts = [
        'budget_min'  => 'decimal:2',
        'budget_max'  => 'decimal:2',
        'remote_ok'   => 'boolean',
        'is_featured' => 'boolean',
        'expires_at'  => 'datetime',
        'awarded_at'  => 'datetime',
    ];

    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'poster_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_skills');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(JobAttachment::class);
    }

    public function getBudgetRangeAttribute(): string
    {
        if ($this->budget_min && $this->budget_max) {
            return 'Nu. ' . number_format($this->budget_min) . ' - ' . number_format($this->budget_max);
        }
        return $this->budget_max ? 'Up to Nu. ' . number_format($this->budget_max) : 'Negotiable';
    }

    public function scopeOpen($query) { return $query->where('status', 'open'); }
    public function scopeFeatured($query) { return $query->where('is_featured', true); }
}
