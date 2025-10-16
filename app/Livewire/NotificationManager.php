<?php

namespace App\Livewire;

use App\Models\OnesignalPlayer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class NotificationManager extends Component
{
    #[On('userSubscribed')]
    public function simpanPlayerId($data)
    {
        $playerId = $data['player_id'] ?? null;

        if ($playerId && Auth::check()) {
            OnesignalPlayer::updateOrCreate(
                ['player_id' => $playerId],
                ['user_id' => Auth::id()]
            );
        }
    }

    #[On('removePlayerId')]
    public function removePlayerId($data)
    {
        $playerId = $data['player_id'] ?? null;

        if (!$playerId || !Auth::check()) return;

        OnesignalPlayer::where('user_id', Auth::id())
            ->where('player_id', $playerId)
            ->delete();
    }

    public function render()
    {
        return view('livewire.notification-manager');
    }
}
