<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'issuing_organization', 'issue_date', 'expiry_date', 'credential_id', 'credential_url', 'certificate_file', 'is_verified'];
    protected $casts = ['issue_date' => 'date', 'expiry_date' => 'date', 'is_verified' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
}
