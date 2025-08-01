<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class NotificationHelper
{
    public static function sendToUser($user,  $heading,  $content, string $url): void
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
            'Authorization' => 'Basic ' . config('services.onesignal.rest_api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => config('services.onesignal.app_id'),
            'include_player_ids' => $playerIds,
            'headings' => $heading,
            'contents' => $content,
            'url' =>  $url,
        ]);
    }
}
