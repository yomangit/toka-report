import OneSignal from 'onesignal';

export const initOneSignal = async () => {
    await OneSignal.init({
        appId: import.meta.env.VITE_ONESIGNAL_APP_ID,
        notifyButton: { enable: true },
        allowLocalhostAsSecureOrigin: true,
    });

    const isPushEnabled = await OneSignal.isPushNotificationsEnabled();
    if (!isPushEnabled) {
        await OneSignal.showSlidedownPrompt();
    }

    OneSignal.on('subscriptionChange', async function (isSubscribed) {
        if (isSubscribed) {
            const playerId = await OneSignal.getUserId();
            window.livewire?.all().forEach((component) => {
                if (component.savePlayerId) {
                    component.set('playerId', playerId);
                    component.call('savePlayerId');
                }
            });
        }
    });
};
