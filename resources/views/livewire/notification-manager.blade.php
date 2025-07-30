<div>
    {{-- Komponen NotificationManager --}}

    <div class="p-4 border rounded shadow">
        <button onclick="activateNotifications()">Aktifkan Notifikasi</button>
    </div>

    @push('scripts')
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
    window.OneSignal = window.OneSignal || [];
    OneSignal.push(function () {
        OneSignal.init({
            appId: "{{ env('ONESIGNAL_APP_ID') }}",
            notifyButton: {
                enable: false
            },
            allowLocalhostAsSecureOrigin: true // optional: untuk dev
        });

        OneSignal.isPushNotificationsSupported().then(function (isSupported) {
            if (!isSupported) {
                alert("Browser tidak mendukung push notification.");
                return;
            }

            OneSignal.getNotificationPermission().then(function (permission) {
                console.log("Permission:", permission);

                if (permission !== 'granted') {
                    OneSignal.showSlidedownPrompt();
                }

                OneSignal.isPushNotificationsEnabled().then(function (isEnabled) {
                    console.log("isPushEnabled:", isEnabled);

                    if (isEnabled) {
                        OneSignal.getUserId().then(function (playerId) {
                            console.log("Player ID:", playerId);

                            if (playerId) {
                                Livewire.dispatch('userSubscribed', {
                                    player_id: playerId
                                });
                            } else {
                                alert("Gagal mendapatkan Player ID");
                            }
                        });
                    } else {
                        console.log("User belum mengaktifkan push notifications");
                    }
                });
            });
        });
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
