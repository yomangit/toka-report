<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cjmellor\Approval\Concerns\MustBeApproved;

class DocHazPelapor extends Model
{
    use MustBeApproved;
    protected $table = 'action_hazards';
    protected $fillable = [
        'hazard_id',
        'followup_action',
        'actionee_comment',
        'action_condition',
        'responsibility',
        'due_date',
        'completion_date',
        'is_temporary',
        'token',
    ];
    public function Hazard()
    {
        return $this->belongsTo(HazardReport::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'responsibility');
    }
    public function scopeSearchHazard($query, $term)
       {
           $query->when(
               $term ?? false,
               fn($query, $term) =>
               $query->whereHas('Hazard', function ($q) use ($term) {
                   $q->where('task_being_done', 'like', '%' . $term . '%');
               })->orwhereHas('users', function ($q) use ($term) {
                   $q->where('lookup_name', 'like', '%' . $term . '%');
               })
           );
       }
}

