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

        Http::withHeaders([
            'Authorization' => 'Basic ' . config('services.onesignal.rest_api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => config('services.onesignal.app_id'),
            'include_player_ids' => $playerIds,
            'headings' => ['en' => $heading],
            'contents' => ['en' => $content],
            'url' => $url ?? config('app.url'),
        ]);
    }
}
