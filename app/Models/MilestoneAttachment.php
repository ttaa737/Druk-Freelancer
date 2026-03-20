<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MilestoneAttachment extends Model {
    use HasFactory;
    protected $fillable = ['milestone_id','file_path','original_name','file_type','file_size','uploaded_by_role'];
    public function milestone() { return $this->belongsTo(Milestone::class); }
    public function getFileUrlAttribute(): string { return asset('storage/'.$this->file_path); }
}
