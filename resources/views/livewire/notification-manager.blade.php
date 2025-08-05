<div></div>

<!-- SDK OneSignal -->
<script async src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js"></script>

<script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];

    OneSignalDeferred.push(async function (OneSignal) {
        const registration = await navigator.serviceWorker.ready;

        await OneSignal.init({
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18",
            serviceWorkerPath: "/sw.js",
            serviceWorkerRegistration: registration,
            notifyButton: {
                enable: false,
            },
        });

        const playerId = await OneSignal.User.getId();
        console.log('✅ OneSignal playerId:', playerId);

        OneSignal.Notifications.addEventListener("permissionChange", async (event) => {
            if (event.to === "granted") {
                const playerId = await OneSignal.User.getId(); // ✅ gunakan getId
                console.log('🔔 Permission granted. playerId:', playerId);
                Livewire.dispatch('userSubscribed', {
                    playerId: playerId
                });
            }
        });
    });
</script>
