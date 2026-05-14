<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompletionSubmissionAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'completion_submission_id', 'file_name', 'file_path', 'file_type',
        'file_size', 'description', 'document_type'
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    // Document type constants
    public const DOCUMENT_TYPE_EVIDENCE = 'evidence';
    public const DOCUMENT_TYPE_REPORT = 'report';
    public const DOCUMENT_TYPE_DELIVERABLE = 'deliverable';
    public const DOCUMENT_TYPE_SCREENSHOT = 'screenshot';
    public const DOCUMENT_TYPE_VIDEO = 'video';
    public const DOCUMENT_TYPE_OTHER = 'other';

    public function completionSubmission(): BelongsTo
    {
        return $this->belongsTo(CompletionSubmission::class);
    }

    public function getFileUrlAttribute(): string
    {
        return route('completion.download-attachment', $this->id);
    }

    public function getDocumentTypeLabel(): string
    {
        return match ($this->document_type) {
            self::DOCUMENT_TYPE_EVIDENCE => 'Evidence',
            self::DOCUMENT_TYPE_REPORT => 'Report',
            self::DOCUMENT_TYPE_DELIVERABLE => 'Deliverable',
            self::DOCUMENT_TYPE_SCREENSHOT => 'Screenshot',
            self::DOCUMENT_TYPE_VIDEO => 'Video',
            default => 'Other',
        };
    }
}
