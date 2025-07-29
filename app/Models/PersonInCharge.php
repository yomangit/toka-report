<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonInCharge extends Model
{
   protected $table = 'person_in_charges';
   protected $fillable = [
       'user_id',
      'division_id',
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
