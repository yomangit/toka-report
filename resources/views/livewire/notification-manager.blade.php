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
    OneSignalDeferred.push(async function(OneSignal) {
        // Init SDK
        await OneSignal.init({
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18",
            serviceWorkerPath: "/sw.js",
            serviceWorkerRegistration: await navigator.serviceWorker.ready,
            notifyButton: { enable: true }
        });

        // Cek apakah browser mendukung push
        const isPushSupported = await OneSignal.isPushSupported();
        if (!isPushSupported) {
            console.warn("🚫 Push not supported on this browser.");
            return;
        }

        // Subscribe kalau belum
        const alreadySubscribed = await OneSignal.User.PushSubscription.exists();
        if (!alreadySubscribed) {
            try {
                await OneSignal.User.PushSubscription.subscribe();
                console.log("✅ User now subscribed");
            } catch (err) {
                console.error("❌ Failed to subscribe:", err);
                return;
            }
        }

        // ✅ Login setelah sudah pasti subscription ada
        const userIdFromBackend = "{{ auth()->id() }}";
        if (userIdFromBackend) {
            try {
                await OneSignal.login(userIdFromBackend);
                console.log("🔑 Logged in to OneSignal with ID:", userIdFromBackend);
            } catch (e) {
                console.error("❌ Failed to login to OneSignal:", e);
            }
        }

        // Ambil dan kirim player_id ke Livewire
        const playerId = OneSignal.User.PushSubscription.id;
        if (playerId) {
            console.log("🎯 Player ID:", playerId);
            Livewire.dispatch('userSubscribed', { player_id: playerId });
        } else {
            console.warn("⚠️ Player ID not available yet.");
        }

        // Update kalau ada perubahan subscription
        OneSignal.Notifications.addEventListener("subscriptionChange", async () => {
            const newPlayerId = OneSignal.User.PushSubscription.id;
            if (newPlayerId) {
                console.log("🔄 Player ID updated:", newPlayerId);
                Livewire.dispatch('userSubscribed', { player_id: newPlayerId });
            }
        });
    });
</script>

