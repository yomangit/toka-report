<div>
    {{-- Komponen NotificationManager --}}

    @push('scripts')
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>

    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18"
                , notifyButton: {
                    enable: true
                }
                , serviceWorkerPath: "/OneSignalSDKWorker.js"
                , serviceWorkerUpdaterPath: "/OneSignalSDKUpdaterWorker.js"
            , });

            console.log("✅ OneSignal initialized");

            // 🔍 Cek apakah user sudah subscribe
            const isSubscribed = await OneSignal.User.PushSubscription.optedIn;
            console.log("🔍 Initial subscription status:", isSubscribed);

            if (isSubscribed) {
                const playerId = await OneSignal.User.PushSubscription.token;
                console.log("✅ Already subscribed. Player ID:", playerId);
                window.Livewire.dispatch('userSubscribed', {
                    playerId
                });
            }

            // 📡 Dengarkan event perubahan status langganan
            OneSignal.User.PushSubscription.addEventListener('change', async (state) => {
                if (state.current.optedIn) {
                   const playerId = await OneSignal.User.PushSubscription.token;
                    console.log("✅ New subscription. Player ID:", playerId);
                    window.Livewire.dispatch('userSubscribed', {
                        playerId
                    });
                }
            });
        });

    </script>

    @endpush
</div>
