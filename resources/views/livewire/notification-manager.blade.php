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
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18"
            , serviceWorkerPath: "/sw.js"
            , serviceWorkerRegistration: await navigator.serviceWorker.ready
            , notifyButton: {
                enable: true
            }
        });

        // Tunggu sampai status subscribe berubah
        OneSignal.on('subscriptionChange', async function(isSubscribed) {
            console.log("🟢 Status subscription berubah:", isSubscribed);
            if (isSubscribed) {
                const playerId = await OneSignal.getUserId();
                console.log("🎯 Player ID (subscriptionChange):", playerId);

                if (playerId) {
                    Livewire.dispatch('userSubscribed', {
                        player_id: playerId
                    });
                }
            }
        });

        // Coba ambil langsung juga kalau sudah login sebelumnya
        const playerId = await OneSignal.getUserId();
        if (playerId) {
            console.log("✅ Player ID (langsung):", playerId);
            Livewire.dispatch('userSubscribed', {
                player_id: playerId
            });
        } else {
            console.log("⏳ Belum ada playerId, menunggu izin notifikasi.");
        }
    });

</script>
