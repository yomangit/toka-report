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
        $playerId = $player_id[0]??null;

        if (isset($playerId) && Auth::check()) {
            OnesignalPlayer::updateOrCreate(
                ['player_id' => $playerId],
                ['user_id' => Auth::user()->id]
            );
        }
    }
    #[On('removePlayerId')]
    public function removePlayerId($player_id)
    {
        $playerId = $player_id ?? null;

        if (!$playerId || !auth()->check()) return;

        OnesignalPlayer::where('user_id', auth()->id())
            ->where('player_id', $playerId)
            ->delete();
    }
    public function render()
    {
        return view('livewire.notification-manager');
    }
}
