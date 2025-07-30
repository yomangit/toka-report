<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationManager extends Component
{
     public function savePlayerId(Request $request)
    {
        $request->validate([
            'player_id' => 'required|string',
        ]);

        $user = Auth::user();

        if ($user) {
            $user->onesignal_player_id = $request->player_id;
            $user->save();

            return response()->json(['message' => 'Player ID saved']);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
    public function render()
    {
        return view('livewire.notification-manager');
    }
}
