<div>

</div>

<!-- SDK OneSignal -->
<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" async></script>

<script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function(OneSignal) {
        await OneSignal.init({
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18"
            , serviceWorkerPath: "/sw.js"
            , serviceWorkerRegistration: await navigator.serviceWorker.ready
            , notifyButton: {
                enable: false
            }
        });
        console.log(OneSignal.User.getId());
        
        OneSignal.Notifications.addEventListener("permissionChange", async (event) => {
            if (event.to === "granted") {
                const playerId = OneSignal.User.PushSubscription.id;
                console.log(playerId);
                Livewire.dispatch('userSubscribed', {
                    player_id: playerId
                });
            }
        });
    });

</script>
