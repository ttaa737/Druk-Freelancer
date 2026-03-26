<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, TwoFactorAuthenticatable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'cid_number', 'brn_number',
        'verification_status', 'avatar', 'preferred_language', 'status',
        'two_factor_enabled', 'last_login_at', 'last_login_ip',
        'notification_preferences', 'privacy_settings',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at'       => 'datetime',
        'phone_verified'          => 'boolean',
        'identity_verified'       => 'boolean',
        'two_factor_confirmed_at' => 'datetime',
        'last_login_at'           => 'datetime',
        'two_factor_enabled'      => 'boolean',
        'password'                => 'hashed',
        'notification_preferences' => 'array',
        'privacy_settings'        => 'array',
    ];

    public function profile(): HasOne { return $this->hasOne(Profile::class); }
    public function wallet(): HasOne { return $this->hasOne(Wallet::class); }
    public function notifications(): HasMany { return $this->hasMany(Notification::class, 'user_id'); }
    public function unreadNotifications(): HasMany { return $this->notifications()->where(function ($q) { $q->whereNull('read_at')->orWhere('is_read', 0); }); }
    public function readNotifications(): HasMany { return $this->notifications()->where(function ($q) { $q->whereNotNull('read_at')->orWhere('is_read', 1); }); }
    public function skills()
    {
        $relation = $this->belongsToMany(Skill::class, 'user_skills');
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('user_skills', 'level')) {
                $relation = $relation->withPivot('level');
            }
        } catch (\Exception $e) {
            // If schema can't be read (e.g., during certain CLI contexts), silently continue without pivot
        }

        return $relation->withTimestamps();
    }
    public function portfolioItems(): HasMany { return $this->hasMany(PortfolioItem::class); }
    public function certifications(): HasMany { return $this->hasMany(Certification::class); }
    public function paymentMethods(): HasMany { return $this->hasMany(PaymentMethod::class); }
    public function jobsPosted(): HasMany { return $this->hasMany(Job::class, 'poster_id'); }
    public function proposals(): HasMany { return $this->hasMany(Proposal::class, 'freelancer_id'); }
    public function contractsAsFreelancer(): HasMany { return $this->hasMany(Contract::class, 'freelancer_id'); }
    public function contractsAsPoster(): HasMany { return $this->hasMany(Contract::class, 'poster_id'); }
    public function transactions(): HasMany { return $this->hasMany(Transaction::class); }
    public function reviewsGiven(): HasMany { return $this->hasMany(Review::class, 'reviewer_id'); }
    public function reviewsReceived(): HasMany { return $this->hasMany(Review::class, 'reviewee_id'); }
    public function reviews(): HasMany { return $this->reviewsReceived(); } // Alias for reviewsReceived
    public function disputesRaised(): HasMany { return $this->hasMany(DisputeCase::class, 'raised_by'); }
    public function verificationDocuments(): HasMany { return $this->hasMany(VerificationDocument::class); }
    public function auditLogs(): HasMany { return $this->hasMany(AuditLog::class); }

    public function isAdmin(): bool       { return $this->role === 'admin'; }
    public function isFreelancer(): bool  { return $this->role === 'freelancer'; }
    public function isJobPoster(): bool   { return $this->role === 'job_poster'; }
    public function isVerified(): bool    { return $this->verification_status === 'verified'; }
    public function isActive(): bool      { return $this->status === 'active'; }

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        // Use UI Avatars as fallback - generates avatar from name
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=1A3A5C&color=FF6B35&bold=true";
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->profile?->company_name ?? $this->name;
    }
}
