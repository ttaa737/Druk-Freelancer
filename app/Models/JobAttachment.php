<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['job_id', 'file_path', 'original_name', 'file_type', 'file_size'];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getPreviewableAttribute(): bool
    {
        $mime = strtolower((string) $this->file_type);

        return str_starts_with($mime, 'image/') || str_contains($mime, 'pdf');
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('jobs.attachments.download', $this);
    }

    public function getPreviewUrlAttribute(): string
    {
        return route('jobs.attachments.preview', $this);
    }
}
