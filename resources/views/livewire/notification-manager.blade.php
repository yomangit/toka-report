<div>
    {{-- Komponen NotificationManager --}}

    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    {{-- <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18"
                , serviceWorkerPath: "/OneSignalSDKWorker.js"
                , serviceWorkerUpdaterPath: "/OneSignalSDKUpdaterWorker.js"
                , notifyButton: {
                    enable: true
                }
            , });

            console.log("✅ OneSignal initialized");

            // 🔍 Cek apakah user sudah subscribe
            const isSubscribed = await OneSignal.User.PushSubscription.optedIn;
            console.log("🔍 Initial subscription status:", isSubscribed);

            if (isSubscribed) {
                const playerId = await OneSignal.User.PushSubscription.token;
                console.log("✅ Already subscribed. Player ID:", playerId);
                  Livewire.dispatch('userSubscribed', { player_id: playerId });
            }

            // 📡 Dengarkan event perubahan status langganan
            OneSignal.User.PushSubscription.addEventListener('change', async (state) => {
                if (state.current.optedIn) {
                    const playerId = await OneSignal.User.PushSubscription.token;
                    console.log("✅ New subscription. Player ID:", playerId);
                     Livewire.dispatch('userSubscribed', { player_id: playerId });
                }
            });
        });

    </script> --}}
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(function(OneSignal) {
            OneSignal.init({
                appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18"
                , notifyButton: {
                    enable: true
                }
            , });

            OneSignal.Notifications.addEventListener('permissionChange', function(state) {
                if (state === 'granted') {
                    OneSignal.getUserId().then(function(playerId) {
                         console.log("✅ Already subscribed. Player ID:", playerId);
                        if (playerId) {
                            Livewire.dispatch('userSubscribed', {
                                player_id: playerId
                            });
                        }
                    });
                }
            });
        });

    </script>
</div>
