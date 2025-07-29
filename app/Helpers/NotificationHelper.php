<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('sendOneSignalNotification')) {
    function sendOneSignalNotification($playerId, $title, $message, $url = null)
    {
        if (!$playerId) return;

        Http::withHeaders([
            'Authorization' => 'Basic ' . config('services.onesignal.rest_api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => config('services.onesignal.app_id'),
            'include_player_ids' => [$playerId],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'url' => $url,
        ]);
    }
}
