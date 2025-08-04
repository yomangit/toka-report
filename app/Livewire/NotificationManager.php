<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\OnesignalPlayer;
use Illuminate\Support\Facades\Auth;

class NotificationManager extends Component
{
    public  $playerId;
    #[On('userSubscribed')]
    public function simpanPlayerId($player_id)
    {
        $this->playerId = $player_id;

        if (isset($this->playerId) && Auth::check()) {
            OnesignalPlayer::updateOrCreate(
                ['player_id' => $this->playerId],
                ['user_id' => Auth::user()->id]
            );
        }
    }
    #[On('user_out')]
    public function handleUserLoggedOut()
    {
        OnesignalPlayer::where('player_id', $this->playerId)
            ->where('user_id', Auth::user()->id)
            ->delete();
    }
    public function render()
    {
        return view('livewire.notification-manager');
    }
}
