<div></div>

@push('scripts')
    <!-- OneSignal SDK -->
    @push('scripts')
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" async></script>

    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];

        OneSignalDeferred.push(async function (OneSignal) {
            await OneSignal.init({
                appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18",
                serviceWorkerPath: "/sw.js",
                serviceWorkerRegistration: await navigator.serviceWorker.ready,
                notifyButton: { enable: false },
                autoResubscribe: false,
                useRedirect: false
            });

            const isSubscribed = await OneSignal.User.PushSubscription.optedIn;

            if (isSubscribed) {
                const playerId = OneSignal.User.PushSubscription.id;

                if (playerId) {
                    console.log("Dispatching playerId:", playerId);

                    Livewire.dispatch('userSubscribed', {
                        player_id: playerId
                    });
                } else {
                    console.warn("Player ID belum tersedia saat ini.");
                }
            }

            OneSignal.Notifications.addEventListener("permissionChange", async (event) => {
                if (event.to === "granted") {
                    const playerId = OneSignal.User.PushSubscription.id;
                    if (playerId) {
                        Livewire.dispatch('userSubscribed', {
                            player_id: playerId
                        });
                    }
                }
            });

            window.handleLogout = async function () {
                try {
                    const playerId = OneSignal.User.PushSubscription.id;
                    if (playerId) {
                        Livewire.dispatch('removePlayerId', {
                            player_id: playerId
                        });

                        await OneSignal.logout();
                    }
                } catch (error) {
                    console.error('Logout error:', error);
                }

                setTimeout(() => {
                    document.getElementById('logout-form').submit();
                }, 300);
            };
        });
    </script>
@endpush

@endpush
