<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class NotificationManager extends Component
{
    #[On('userSubscribed')]
    public function simpanPlayerId($player_id)
    {
        if (auth()) {
            // Contoh: simpan ke user
            auth()->user()->update([
                'onesignal_player_id' => $player_id
            ]);
        }
    }
    public function render()
    {
        return view('livewire.notification-manager');
    }
}
