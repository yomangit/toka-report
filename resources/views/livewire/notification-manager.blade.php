<div>
    <x-notification />
    <x-btn-add wire:click='test' />
</div>

<!-- SDK OneSignal -->
<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" async></script>

<script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function (OneSignal) {
        // Aktifkan log
        OneSignal.log.setLevel("trace");

        await OneSignal.init({
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18",
            serviceWorkerPath: "/sw.js",
            serviceWorkerRegistration: await navigator.serviceWorker.ready,
            notifyButton: { enable: true }
        });

        // Listener baru sesuai SDK v16
        OneSignal.Notifications.addEventListener("subscriptionChange", async (event) => {
            console.log("🟢 Status subscription berubah:", event.to);
            if (event.to) {
                const playerId = await OneSignal.User.getId();
                console.log("🎯 Player ID (subscriptionChange):", playerId);
                if (playerId) {
                    Livewire.dispatch('userSubscribed', {
                        player_id: playerId
                    });
                }
            }
        });

        // Coba ambil langsung kalau sudah ada
        const playerId = await OneSignal.User.getId();
        console.log("🔍 Player ID langsung:", playerId);
        if (playerId) {
            Livewire.dispatch('userSubscribed', {
                player_id: playerId
            });
        }
    });
</script>

