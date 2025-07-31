<div>
    <x-notification />
    <x-btn-add wire:click='test' />
</div>

<!-- SDK OneSignal -->
<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" async></script>

{{-- <script>
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

        // Langsung cek Player ID
        const playerId = OneSignal.User.PushSubscription.id;
        console.log("🎯 Player ID:", playerId);

        if (playerId) {
            Livewire.dispatch('userSubscribed', {
                player_id: playerId
            });
        }
        OneSignal.Notifications.addEventListener("subscriptionChange", async (event) => {
            const newPlayerId = OneSignal.User.PushSubscription.id;
            if (newPlayerId) {
                Livewire.dispatch('userSubscribed', {
                    player_id: newPlayerId
                });
            }
        })
    });

</script> --}}

<script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function (OneSignal) {
        await OneSignal.init({
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18"
            serviceWorkerPath: "/sw.js",
            serviceWorkerUpdaterPath: "/sw.js",
            serviceWorkerRegistration: await navigator.serviceWorker.ready,
        });

        // 🛑 Pastikan permission diberikan
        const permission = await OneSignal.Notifications.permission;
        if (permission !== "granted") {
            await OneSignal.Notifications.requestPermission();
        }

        // ✅ Tunggu player_id muncul
        async function waitForPlayerId(maxRetries = 10, delay = 1000) {
            for (let i = 0; i < maxRetries; i++) {
                const subscription = await OneSignal.User.PushSubscription.get();
                if (subscription?.id) return subscription.id;
                await new Promise(resolve => setTimeout(resolve, delay));
            }
            return null;
        }

        const playerId = await waitForPlayerId();
        if (playerId) {
            console.log("🎯 Player ID:", playerId);
            Livewire.dispatch('userSubscribed', { player_id: playerId });
        } else {
            console.warn("❌ Player ID not found after retries.");
        }

        // 🔄 Jika status subscription berubah
        OneSignal.Notifications.addEventListener("subscriptionChange", async (event) => {
            const updated = await OneSignal.User.PushSubscription.get();
            if (updated?.id) {
                console.log("🟢 Player ID Updated:", updated.id);
                Livewire.dispatch('userSubscribed', { player_id: updated.id });
            }
        });

        // 🚪 Logout bersih (opsional)
        window.addEventListener("beforeunload", async () => {
            try {
                // Belum tersedia di SDK v16 (logout belum support), jadi cukup rely on backend
                console.log("🚪 Unloading... (player still cached on device)");
            } catch (e) {
                console.warn("Logout error:", e);
            }
        });
    });
</script>
