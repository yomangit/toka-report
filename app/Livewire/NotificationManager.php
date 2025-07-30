<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class NotificationManager extends Component
{

   public $playerId;

    #[On('userSubscribed')]
    public function savePlayerId($data)
    {
        $this->playerId = $data['player_id'];

        auth()->user()->update([
            'onesignal_player_id' => $this->playerId,
        ]);

        session()->flash('success', 'Notifikasi berhasil diaktifkan!');
    }

    public function render()
    {
        return view('livewire.notification-manager');
    }
}
