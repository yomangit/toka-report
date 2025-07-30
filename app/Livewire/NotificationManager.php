<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class NotificationManager extends Component
{
    public $onesignal_player_id;
    public function mount()
    {
        dd($this->onesignal_player_id);
        auth()->user()->update([
            'onesignal_player_id' => $this->onesignal_player_id
        ]);
    }
    public function render()
    {
        return view('livewire.notification-manager');
    }
}
