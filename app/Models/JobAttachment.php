<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class JobAttachment extends Model {
    use HasFactory;
    protected $fillable = ['job_id','file_path','original_name','file_type','file_size'];
    public function job() { return $this->belongsTo(Job::class); }
    public function getFileUrlAttribute(): string { return asset('storage/'.$this->file_path); }
}
