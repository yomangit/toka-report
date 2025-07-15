<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cjmellor\Approval\Concerns\MustBeApproved;

class DocHazPelapor extends Model
{
    use MustBeApproved;
    protected $table = 'hazard_documentations';
    protected $fillable = [
        'name_doc',
        'hazard_id',
        'description'
    ];
}
