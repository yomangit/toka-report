<div>
    <x-notification />
    <x-btn-add wire:click='test' />
</div>

<!-- SDK OneSignal -->
<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" async></script>

<script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function(OneSignal) {
        await OneSignal.init({
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18",
            serviceWorkerPath: "/sw.js",
            serviceWorkerRegistration: await navigator.serviceWorker.ready,
            notifyButton: {
                enable: true
            },
        });
        console.log(OneSignal);
        
        // Dapatkan Player ID dengan benar
        const playerId = await OneSignal.getUserId();
        console.log("✅ Player ID:", playerId);

        if (playerId) {
            Livewire.dispatch('userSubscribed', {
                player_id: playerId
            });
        } else {
            console.warn("❌ Player ID belum tersedia. Mungkin user belum mengizinkan notifikasi.");
        }
    });
</script>
