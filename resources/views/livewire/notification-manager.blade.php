<div>
    {{-- Komponen NotificationManager --}}

    @push('scripts')
        <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
        <script>
            window.OneSignalDeferred = window.OneSignalDeferred || [];
            OneSignalDeferred.push(async function(OneSignal) {
                await OneSignal.init({
                    appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18",
                    notifyButton: { enable: true }
                });

                // ✅ Deteksi status langganan
                const isSubscribed = await OneSignal.User.PushSubscription.optedIn;
                if (isSubscribed) {
                    const playerId = await OneSignal.User.getId();
                    console.log("✅ Already subscribed. Player ID:", playerId);
                    window.Livewire.dispatch('userSubscribed', { playerId });
                }

                // ✅ Deteksi subscribe baru
                OneSignal.User.PushSubscription.addEventListener('change', async (state) => {
                    if (state.current.optedIn) {
                        const playerId = await OneSignal.User.getId();
                        console.log("✅ New subscription. Player ID:", playerId);
                        window.Livewire.dispatch('userSubscribed', { playerId });
                    }
                });
            });
        </script>
    @endpush
</div>
