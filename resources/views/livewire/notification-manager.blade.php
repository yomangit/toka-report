<div>
    <x-notification />
    <x-btn-add wire:click='test' />
</div>
<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
{{-- <script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function(OneSignal) {
        await OneSignal.init({
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18"
            , serviceWorkerPath: "/OneSignalSDKWorker.js"
            , serviceWorkerUpdaterPath: "/OneSignalSDKUpdaterWorker.js"
        , });
        const playerId = OneSignal.User.PushSubscription.id;
        console.log("Player ID:", playerId);
        if (playerId) {
            Livewire.dispatch('userSubscribed', {
                player_id: playerId
            });
        } else {
            alert("Gagal mendapatkan Player ID setelah permintaan izin.");
        }
    });

</script> --}}
<script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function (OneSignal) {
        await OneSignal.init({
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18",
            serviceWorkerPath: "/OneSignalSDKWorker.js",
            serviceWorkerUpdaterPath: "/OneSignalSDKUpdaterWorker.js",
            autoResubscribe: true,
            notifyButton: { enable: true }, // opsional
        });

        // Dapatkan player ID setelah subscribe
        const playerId = await OneSignal.User.PushSubscription.getId();
        console.log("✅ Player ID:", playerId);

        // Kirim ke Livewire jika berhasil subscribe
        if (playerId) {
            Livewire.dispatch('userSubscribed', {
                player_id: playerId
            });
        }
    });
</script>

<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" async></script>
