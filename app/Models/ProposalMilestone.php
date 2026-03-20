<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ProposalMilestone extends Model {
    use HasFactory;
    protected $fillable = ['proposal_id','title','description','amount','duration_days','sort_order'];
    protected $casts = ['amount' => 'decimal:2'];
    public function proposal() { return $this->belongsTo(Proposal::class); }
}
