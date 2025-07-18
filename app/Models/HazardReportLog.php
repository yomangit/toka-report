<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HazardReportLog extends Model
{
    protected $fillable = [
        'hazard_report_id',
        'user_id',
        'action',
        'description',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hazardReport()
    {
        return $this->belongsTo(HazardReport::class);
    }
}
