<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnesignalPlayer extends Model
{
    protected $table = 'onesignal_players';
    protected $fillable = ['user_id', 'player_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
