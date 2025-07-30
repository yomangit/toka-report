<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class NotificationManager extends Component
{
    #[On('userSubscribed')]
    public function savePlayerId($data)
    {
        // Akses nilai dari payload JS
        $playerId = $data['player_id'];

        // Contoh: simpan ke user
        auth()->user()->update([
            'onesignal_player_id' => $playerId
        ]);
    }
    public function render()
    {
        return view('livewire.notification-manager');
    }
}
