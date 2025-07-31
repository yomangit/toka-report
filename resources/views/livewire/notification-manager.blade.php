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
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18",
            serviceWorkerPath: "/sw.js",
            serviceWorkerRegistration: await navigator.serviceWorker.ready,
            notifyButton: { enable: true }
        });

        const isPushSupported = await OneSignal.isPushSupported();
        if (!isPushSupported) {
            console.warn("🚫 Push Not Supported");
            return;
        }

        // Langkah 1: Subscribe jika belum
        const subscribed = await OneSignal.User.PushSubscription.exists();
        if (!subscribed) {
            try {
                await OneSignal.User.PushSubscription.subscribe();
                console.log("🔔 Subscribed successfully");
            } catch (err) {
                console.error("❌ Subscription failed", err);
                return;
            }
        }

        // Langkah 2: Tunggu sampai Player ID tersedia
        let playerId = OneSignal.User.PushSubscription.id;
        while (!playerId) {
            await new Promise(resolve => setTimeout(resolve, 300)); // tunggu 300ms
            playerId = OneSignal.User.PushSubscription.id;
        }

        console.log("🎯 Player ID ready:", playerId);

        // Langkah 3: Sekarang boleh login
        const userIdFromBackend = "{{ auth()->id() }}";
        if (userIdFromBackend) {
            try {
                await OneSignal.login(userIdFromBackend);
                console.log("✅ Logged in to OneSignal");
            } catch (err) {
                console.error("❌ Login failed:", err);
            }
        }

        // Langkah 4: Kirim ke Livewire
        Livewire.dispatch('userSubscribed', { player_id: playerId });

        // Langkah 5: Dengarkan perubahan subscription
        OneSignal.Notifications.addEventListener("subscriptionChange", async () => {
            const newPlayerId = OneSignal.User.PushSubscription.id;
            console.log("♻️ Subscription changed, new ID:", newPlayerId);
            Livewire.dispatch('userSubscribed', { player_id: newPlayerId });
        });
    });
</script>


