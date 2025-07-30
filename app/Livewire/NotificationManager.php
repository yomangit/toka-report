<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class NotificationManager extends Component
{
    #[On('userSubscribed')]
    public function savePlayerId($data)
    {
        dd($data);
        auth()->user()->update([
            'onesignal_player_id' => $data['player_id'],
        ]);
    }
    public function render()
    {
        return view('livewire.notification-manager');
    }
}
