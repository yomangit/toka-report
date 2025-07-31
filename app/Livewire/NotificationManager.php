<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;

class NotificationManager extends Component
{
    #[On('userSubscribed')]
    public function simpanPlayerId($player_id)
    {
        auth()->user()->update([
            'onesignal_player_id' => $player_id,
        ]);
    }
    public function test()
    {
        $restApiKey = 'os_v2_app_wugfbgpj6rbz3khjggnq4ts6ddon2ktn3fbeuduhkekgx7odctgn7qfesyj4r4hcd7fjar4cidqbkmkqqna7h26oug3wnyxomvqfvni';
        $appId = 'b50c5099-e9f4-439d-a8e9-319b0e4e5e18';
        $playerId = 'ea559b76-e8f2-49ee-9a79-db84da99d6fe';

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $restApiKey,
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => $appId,
            'include_player_ids' => [$playerId],
            'headings' => ['en' => 'Tes Notifikasi'],
            'contents' => ['en' => 'Ini adalah notifikasi dari Laravel.'],
            'url' => 'https://tokasafe.archimining.com', // wajib kalau Chrome
        ]);
        dd($response->json());
        return $response->json();
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
