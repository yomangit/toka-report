<div>
    {{-- The Master doesn't talk, he acts. --}}
</div>
<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
<script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function(OneSignal) {
        await OneSignal.init({
            appId: "b50c5099-e9f4-439d-a8e9-319b0e4e5e18"
        , });
        const playerId = OneSignal.User.PushSubscription.id;
        console.log("Player ID:", playerId);
        // if (playerId) {
        //     Livewire.dispatch('userSubscribed', {
        //         // player_id: playerId
        //         player_id: 'playerId'
        //     });
        // } else {
        //     alert("Gagal mendapatkan Player ID setelah permintaan izin.");
        // }
    });
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            console.log('Dispatching...');
            Livewire.dispatch('userSubscribed', {
                player_id: 'abc-123-def'
            });
        }, 1000); // beri delay supaya Livewire sempat mounting
    });

</script>
