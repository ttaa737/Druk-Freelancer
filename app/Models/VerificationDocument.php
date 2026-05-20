<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'document_type', 'document_number',
        'file_path', 'original_name', 'status',
        'reviewed_by', 'rejection_reason', 'admin_notes', 'reviewed_at',
        'is_required', 'role_required', 'valid_until',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'valid_until' => 'date',
    ];

    /**
     * Document type constants
     */
    public const TYPE_CID = 'cid';
    public const TYPE_CV = 'cv';
    public const TYPE_BRN = 'brn';
    public const TYPE_OTHER = 'other';

    /**
     * Get human-readable label for document type
     */
    public function getDocumentTypeLabel(): string
    {
        return match ($this->document_type) {
            self::TYPE_CID => 'Citizenship ID (CID)',
            self::TYPE_CV => 'Curriculum Vitae (CV)',
            self::TYPE_BRN => 'Business License / BRN',
            self::TYPE_OTHER => 'Other Document',
            default => ucfirst(str_replace('_', ' ', $this->document_type)),
        };
    }

    /**
     * Get description for document type
     */
    public static function getDocumentDescription(string $type): string
    {
        return match ($type) {
            self::TYPE_CID => 'Upload a clear photo or scan of your Bhutanese CID (both front and back)',
            self::TYPE_CV => 'Upload your Curriculum Vitae (CV) - PDF or DOC format recommended',
            self::TYPE_BRN => 'Business Registration Number or professional license certificate',
            self::TYPE_OTHER => 'Other relevant document',
            default => '',
        };
    }

    /**
     * Get placeholder text for document type
     */
    public static function getDocumentPlaceholder(string $type): string
    {
        return match ($type) {
            self::TYPE_CID => 'CID Number (e.g., 11509000001)',
            self::TYPE_CV => 'N/A',
            self::TYPE_BRN => 'License/BRN Number',
            self::TYPE_OTHER => 'Document reference (optional)',
            default => '',
        };
    }

    /**
     * Get icon for document type
     */
    public static function getDocumentIcon(string $type): string
    {
        return match ($type) {
            self::TYPE_CID => 'fa-id-card',
            self::TYPE_CV => 'fa-file-pdf',
            self::TYPE_BRN => 'fa-certificate',
            self::TYPE_OTHER => 'fa-file',
            default => 'fa-document',
        };
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    /**
     * Check if this document is required for a specific role
     */
    public function isRequiredForRole(string $role): bool
    {
        if (!$this->role_required) {
            return $this->is_required ?? false;
        }

        $roles = explode(',', $this->role_required);
        return in_array($role, $roles);
    }
}
