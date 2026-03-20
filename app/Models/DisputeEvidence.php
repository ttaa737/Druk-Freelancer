<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DisputeEvidence extends Model {
    use HasFactory;
    protected $fillable = ['dispute_id','submitted_by','evidence_type','description','file_path','original_name'];
    public function dispute() { return $this->belongsTo(DisputeCase::class, 'dispute_id'); }
    public function submittedBy() { return $this->belongsTo(User::class, 'submitted_by'); }
    public function getFileUrlAttribute(): ?string { return $this->file_path ? asset('storage/'.$this->file_path) : null; }
}
