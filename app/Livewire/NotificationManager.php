<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class NotificationManager extends Component
{
    public function render()
    {
        return view('livewire.notification-manager');
    }
}
