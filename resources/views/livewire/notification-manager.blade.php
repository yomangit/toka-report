<div>
    {{-- Komponen NotificationManager --}}

    <div class="p-4 border rounded shadow">
        <button onclick="activateNotifications()">Aktifkan Notifikasi</button>
    </div>

    @push('scripts')

    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        function activateNotifications() {
            console.log("activateNotifications dipanggil");

            window.OneSignalDeferred = window.OneSignalDeferred || [];
            OneSignalDeferred.push(async function(OneSignal) {
                console.log("OneSignal init mulai...");

                await OneSignal.init({
                    appId: "{{ env('ONESIGNAL_APP_ID') }}"
                    , serviceWorkerPath: "/OneSignalSDKWorker.js"
                    , serviceWorkerUpdaterPath: "/OneSignalSDKUpdaterWorker.js"
                    , notifyButton: {
                        enable: false
                    }
                });

                const isPushSupported = await OneSignal.Notifications.isPushSupported();
                console.log("Push supported:", isPushSupported);

                if (!isPushSupported) {
                    alert("Browser tidak mendukung push notification.");
                    return;
                }

                const permission = await OneSignal.Notifications.permissionNative();
                console.log("Permission:", permission);

                const isSubscribed = await OneSignal.User.PushSubscription.isSubscribed();
                console.log("isSubscribed:", isSubscribed);

                if (!isSubscribed || permission !== 'granted') {
                    console.log("Meminta izin...");
                    await OneSignal.Notifications.showSlidedownPrompt();
                }

                setTimeout(async () => {
                    const playerId = await OneSignal.User.getId();
                    console.log("Player ID:", playerId);

                    if (playerId) {
                        Livewire.dispatch('userSubscribed', {
                            player_id: playerId
                        });
                    } else {
                        alert("Gagal mendapatkan Player ID setelah permintaan izin.");
                    }
                }, 3000);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log("DOM Loaded, panggil activateNotifications()");
            activateNotifications();
        });

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
