<div>
    <x-notification />
    <x-btn-add wire:click='test' />
</div>

<!-- SDK OneSignal -->
<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" async></script>

<script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function (OneSignal) {
        await OneSignal.init({
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18",
            serviceWorkerPath: "/sw.js",
            serviceWorkerRegistration: await navigator.serviceWorker.ready,
            notifyButton: { enable: true }
        });

        // Versi SDK 16: cara benar pantau perubahan subscription
        OneSignal.Notifications.addEventListener("subscriptionChange", async (event) => {
            console.log("🟢 Subscription berubah:", event.to);
            if (event.to) {
                const playerId = await OneSignal.User.getId();
                console.log("🎯 Player ID:", playerId);
                if (playerId) {
                    Livewire.dispatch('userSubscribed', {
                        player_id: playerId
                    });
                }
            }
        });

        // Cek langsung jika sudah login
        const playerId = await OneSignal.User.getId();
        console.log("🔍 Player ID langsung:", playerId);
        if (playerId) {
            Livewire.dispatch('userSubscribed', {
                player_id: playerId
            });
        }
    });
</script>


