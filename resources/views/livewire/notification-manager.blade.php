<div>
    {{-- Komponen NotificationManager --}}

    <div class="p-4 border rounded shadow">
        @if (auth()->user()->onesignal_player_id)
        <p class="text-green-600">✅ Notifikasi sudah aktif untuk perangkat ini.</p>
        @else
        <button onclick="activateNotifications()" class="btn btn-primary">
            🔔 Aktifkan Notifikasi
        </button>
        @endif

        @if (session()->has('success'))
        <p class="mt-2 text-green-500">{{ session('success') }}</p>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        function activateNotifications() {
            window.OneSignalDeferred = window.OneSignalDeferred || [];
            OneSignalDeferred.push(async function(OneSignal) {
                await OneSignal.init({
                    appId: "{{ env('ONESIGNAL_APP_ID') }}"
                    , notifyButton: {
                        enable: false
                    }
                });

                const isPushSupported = await OneSignal.Notifications.isPushSupported();
                if (!isPushSupported) {
                    alert("Browser tidak mendukung push notification.");
                    return;
                }

                const permission = await OneSignal.Notifications.permissionNative();
                if (permission !== 'granted') {
                    await OneSignal.Notifications.requestPermission();
                }

                const playerId = await OneSignal.User.getId();

                if (playerId) {
                    Livewire.dispatch('userSubscribed', {
                        player_id: playerId
                    });
                } else {
                    alert("Gagal mendapatkan Player ID");
                }
            });
        }

    </script>
    @endpush




    {{-- <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script> --}}
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



</div>
