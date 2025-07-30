<div>
    {{-- The Master doesn't talk, he acts. --}}
</div>
<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" async></script>
<script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function (OneSignal) {
        await OneSignal.init({
         appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18", // ← ganti dengan milikmu
        });

        // Minta izin notifikasi
        const permission = await OneSignal.Notifications.requestPermission();
        console.log('🔐 Izin:', permission); // 'granted'

        // Ambil player ID setelah diizinkan
        const playerId = await OneSignal.User.PushSubscription.id;// SDK v16: pakai async
        console.log("📥 playerId:", playerId);

        if (playerId) {
            Livewire.hook('component.initialized', (component) => {
                if (component.name === 'notification-manager') {
                    Livewire.dispatch('userSubscribed', {
                        player_id: playerId
                    });
                }
            });
        } else {
            console.warn("⚠️ Player ID belum tersedia setelah init.");
        }
    });
</script>
