<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class NotificationHelper
{
    public static function sendToUser($user,  $heading,  $content, $url): void
    {
        $playerIds = $user->oneSignalPlayers()->pluck('player_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (empty($playerIds)) {
            return;
        }
        Http::withHeaders([
            'Authorization' => 'Basic ' . 'os_v2_app_wugfbgpj6rbz3khjggnq4ts6ddon2ktn3fbeuduhkekgx7odctgn7qfesyj4r4hcd7fjar4cidqbkmkqqna7h26oug3wnyxomvqfvni',
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => 'b50c5099-e9f4-439d-a8e9-319b0e4e5e18',
            'include_player_ids' => $playerIds,
            'headings' => $heading,
            'contents' => $content,
            'url' =>  $url,
        ]);
    }
}
