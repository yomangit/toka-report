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
    public function simpanPlayerId($player_id)
    {
        $playerId = $player_id;

        if (isset($playerId) && Auth::check()) {
            OnesignalPlayer::updateOrCreate(
                ['player_id' => $playerId],
                ['user_id' => Auth::user()->id]
            );
        }
    }
    #[On('user_out')]
    public function handleUserLoggedOut($player_id)
    {
        OnesignalPlayer::where('player_id', $player_id)->where('user_id', Auth::user()->id)->delete();
    }
    public function render()
    {
        return view('livewire.notification-manager');
    }
}
