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
        await OneSignal.init({
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18"
            , serviceWorkerPath: "/sw.js"
            , serviceWorkerRegistration: await navigator.serviceWorker.ready
        , });

        // ✅ Loginkan user ke OneSignal pakai ID user sistemmu (misal user ID Laravel)
        const userIdFromBackend = "{{ auth()->id() }}"; // atau bisa dari cookie, API, dsb
        if (userIdFromBackend) {
            await OneSignal.login(userIdFromBackend);
        }

        // Lanjutkan cek subscription
        const isSubscribed = OneSignal.User.PushSubscription.optedIn;
        if (isSubscribed) {
            const playerId = OneSignal.User.PushSubscription.id;
            console.log("🎯 Player ID:", playerId);
            if (playerId) {
                Livewire.dispatch('userSubscribed', {
                    player_id: playerId
                });
            }
        } else {
            console.log("🔕 User belum subscribe");
        }

        // // 🔄 Jika status subscription berubah
        // OneSignal.Notifications.addEventListener("subscriptionChange", async (event) => {
        //     const updated = await OneSignal.User.PushSubscription.get();
        //     if (updated ? .id) {
        //         console.log("🟢 Player ID Updated:", updated.id);
        //         Livewire.dispatch('userSubscribed', {
        //             player_id: updated.id
        //         });
        //     }
        // });

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
