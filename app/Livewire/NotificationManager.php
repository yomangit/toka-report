<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class NotificationManager extends Component
{
    public $onesignal_player_id;
    // #[On('userSubscribed')]
    // public function savePlayerId($datas)
    // {
    //     dd($datas);
    //     // Akses nilai dari payload JS
    //     $playerId = $data['player_id'];

    //     // Contoh: simpan ke user
    //     auth()->user()->update([
    //         'onesignal_player_id' => $playerId
    //     ]);
    // }
    public function render()
    {
        dd($this->onesignal_player_id);
        return view('livewire.notification-manager');
    }
}
