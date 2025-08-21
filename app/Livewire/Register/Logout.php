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
       return redirect()->route('login');
    }
    public function render()
    {
        return view('livewire.register.logout');
    }
}
