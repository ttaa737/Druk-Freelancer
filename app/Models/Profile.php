<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bio', 'dzongkhag', 'gewog', 'address', 'website',
        'headline', 'hourly_rate', 'availability', 'experience_years',
        'company_name', 'industry', 'company_size',
        'average_rating', 'total_reviews', 'total_jobs_completed',
        'total_earned', 'total_spent', 'profile_views', 'is_featured',
    ];

    protected $casts = [
        'hourly_rate'   => 'decimal:2',
        'average_rating' => 'decimal:2',
        'total_earned'  => 'decimal:2',
        'total_spent'   => 'decimal:2',
        'is_featured'   => 'boolean',
    ];

    const DZONGKHAGS = [
        'Bumthang', 'Chhukha', 'Dagana', 'Gasa', 'Haa', 'Lhuntse', 'Mongar',
        'Paro', 'Pemagatshel', 'Punakha', 'Samdrup Jongkhar', 'Samtse',
        'Sarpang', 'Thimphu', 'Trashigang', 'Trashiyangtse', 'Trongsa',
        'Tsirang', 'Wangdue Phodrang', 'Zhemgang',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
