<div>
    <x-notification />
    <x-btn-add wire:click='test' />
</div>

<!-- SDK OneSignal -->
{{-- <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" async></script>

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

        // Langsung cek Player ID
        const playerId = OneSignal.User.PushSubscription.id;
        console.log("🎯 Player ID:", playerId);

        if (playerId) {
            Livewire.dispatch('userSubscribed', {
                player_id: playerId
            });
        }

        // Event listener saat status berubah
        OneSignal.Notifications.addEventListener("subscriptionChange", async (event) => {
            const newPlayerId = OneSignal.User.PushSubscription.id;
            console.log("🟢 Player ID Updated:", newPlayerId);
            if (newPlayerId) {
                Livewire.dispatch('userSubscribed', {
                    player_id: newPlayerId
                });
            }
        });
    });

</script> --}}

<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
<script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function(OneSignal) {
        const logoutLink = document.querySelector('a[href="/logout"]');
        await OneSignal.init({
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18"
            , serviceWorkerPath: "/sw.js"
            , serviceWorkerUpdaterPath: "/sw.js"
            , serviceWorkerRegistration: await navigator.serviceWorker.ready
        , });

        // ✅ Cek apakah user sudah subscribe
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
            console.log("🔕 User belum subscribe notifikasi");
        }
    });

    if (logoutLink) {
        logoutLink.addEventListener('click', async function(e) {
            e.preventDefault();

            try {
                // ✅ Logout dari OneSignal terlebih dahulu
                await OneSignal.logout();
                console.log("✅ OneSignal logout berhasil");
            } catch (err) {
                console.warn("⚠️ Gagal OneSignal logout:", err);
            }

            // ✅ Setelah itu, submit form logout Laravel
            document.getElementById('logout-form').submit();
        });
    }

</script>
