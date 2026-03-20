<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DisputeComment extends Model {
    use HasFactory;
    protected $fillable = ['dispute_id','user_id','comment','is_admin_note'];
    protected $casts = ['is_admin_note' => 'boolean'];
    public function dispute() { return $this->belongsTo(DisputeCase::class, 'dispute_id'); }
    public function user() { return $this->belongsTo(User::class); }
}
