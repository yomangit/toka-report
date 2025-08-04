<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\OnesignalPlayer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;

class NotificationManager extends Component
{
    #[On('userSubscribed')]
    public  $playerId;
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
