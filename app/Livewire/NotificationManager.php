<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;

class NotificationManager extends Component
{
    #[On('userSubscribed')]
    public function simpanPlayerId($player_id)
    {
        if (auth()) {
            // Contoh: simpan ke user
            auth()->user()->update([
                'onesignal_player_id' => $player_id
            ]);
        }
    }
    public function test()
    {
        Http::withHeaders([
            'Authorization' => 'Basic ' . config('services.onesignal.rest_api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => config('services.onesignal.app_id'),
            'include_player_ids' => [auth()->user()->onesignal_player_id],
            'headings' => ['en' => 'Halo ' . auth()->user()->lookupname],
            'contents' => ['en' => 'Notifikasi dari Livewire'],
        ]);
    }

    public function render()
    {
        return view('livewire.notification-manager');
    }
}
