<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OnesignalPlayer extends Model
{
    use HasFactory;
    protected $table = 'onesignal_players';
    protected $fillable = ['user_id', 'player_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
