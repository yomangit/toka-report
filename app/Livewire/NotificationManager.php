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
            // Pastikan player ID ini hanya terkait ke user yang sedang login

            // Unlink player ID dari user lain
            // OnesignalPlayer::where('player_id', $playerId)
            //     ->where('user_id', '!=', Auth::id())
            //     ->delete();

            OnesignalPlayer::updateOrCreate(
                ['player_id' => $playerId],
                ['user_id' => Auth::id()]
            );
        }
    }
    public function render()
    {
        return view('livewire.notification-manager');
    }
}
