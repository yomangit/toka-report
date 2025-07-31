<div>
    <x-btn-add wire:click='test' />
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
        if (playerId) {
            Livewire.dispatch('userSubscribed', {
                player_id: playerId
            });
        } else {
            alert("Gagal mendapatkan Player ID setelah permintaan izin.");
        }
    });
   



</script>

