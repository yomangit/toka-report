<?php

namespace App\Livewire\Register;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
class Logout extends Component
{
    public function clickLogout(){
       Auth::logout();

        // invalidate session agar benar-benar keluar
        session()->invalidate();
        session()->regenerateToken();

        // redirect ke halaman login
       $this->dispatch('logged-out');
    }
    public function clickUser(){
        return redirect()->route('updateUser');
    }
    public function render()
    {
        return view('livewire.register.logout');
    }
}
