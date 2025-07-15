<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cjmellor\Approval\Concerns\MustBeApproved;

class DocHazPelapor extends Model
{
    protected $table = 'action_hazards';
    protected $fillable = [
        'hazard_id',
        'followup_action',
        'actionee_comment',
        'action_condition',
        'responsibility',
        'due_date',
        'completion_date',
    ];
}
