<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class NotificationHelper
{
    public static function sendToUser($user, string $heading, string $content, string $url): void
    {
        $playerIds = $user->oneSignalPlayers()->pluck('player_id')->toArray();

        if (empty($playerIds)) {
            return;
        }
        $restApiKey = 'os_v2_app_wugfbgpj6rbz3khjggnq4ts6ddon2ktn3fbeuduhkekgx7odctgn7qfesyj4r4hcd7fjar4cidqbkmkqqna7h26oug3wnyxomvqfvni';
        $appId = 'b50c5099-e9f4-439d-a8e9-319b0e4e5e18';
        Http::withHeaders([
            'Authorization' => 'Basic ' . $restApiKey,
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => $appId,
            'include_player_ids' => $playerIds,
            'headings' => ['en' => $heading],
            'contents' => ['en' => $content],
            'url' => $url ?? config('app.url'),
        ]);
    }
}
