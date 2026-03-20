<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'file_path', 'file_type', 'external_url', 'category_id', 'is_featured', 'sort_order'];
    protected $casts = ['is_featured' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(Category::class); }
}
