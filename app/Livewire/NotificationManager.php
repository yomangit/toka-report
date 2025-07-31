<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\OnesignalPlayer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;

class NotificationManager extends Component
{
    #[On('userSubscribed')]
    public function simpanPlayerId($player_id)
    {
        $playerId = $player_id;

        if ($playerId && Auth::check()) {
            OnesignalPlayer::firstOrCreate([
                'user_id' => Auth::user()->id,
                'player_id' => $playerId,
            ]);
        }
    }
    public function test()
    {
        $playerId = 'ea559b76-e8f2-49ee-9a79-db84da99d6fe';
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . config('services.onesignal.rest_api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => config('services.onesignal.app_id'),
            'include_player_ids' => [$playerId],
            'headings' => ['en' => 'Tes dari Laravel'],
            'contents' => ['en' => 'Halo! Ini notifikasi dari backend'],
            'url' => 'https://tokasafe.archimining.com',
        ]);
        return $response->json();
    }

    public function render()
    {
        return view('livewire.notification-manager');
    }
}
