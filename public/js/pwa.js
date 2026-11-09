if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}

document.addEventListener('submit', (event) => {
    if (!event.target.matches('form[action$="/logout"]')) {
        return;
    }

    event.preventDefault();
    const form = event.target;
    const clear = window.NesiaOffline?.clearDeviceData?.() ?? Promise.resolve();
    clear.finally(() => form.submit());
});
