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
            'Authorization' => 'Basic ' . "wvl3wiusquq3uvs6ueyhrdhg6",
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => "b50c5099-e9f4-439d-a8e9-319b0e4e5e18",
            'include_player_ids' => [auth()->user()->onesignal_player_id],
            'headings' => ['en' => 'Halo ' . auth()->user()->lookup_name],
            'contents' => ['en' => 'Notifikasi dari Livewire'],
        ]);
        $this->dispatch('alert', [
            'text'            => "Laporan Hazard Anda Sudah Terkirim, Terima kasih sudah melapor!!!",
            'duration'        => 5000,
            'destination'     => '/contact',
            'newWindow'       => true,
            'close'           => true,
            'backgroundColor' => "linear-gradient(to right, #06b6d4, #22c55e)",
        ]);
    }

    public function render()
    {
        return view('livewire.notification-manager');
    }
}
