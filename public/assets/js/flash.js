(() => {
    const DELAY_MS = 5000;

    document.querySelectorAll('.alert[data-autodismiss]').forEach((alert) => {
        if (alert.classList.contains('alert-danger')) {
            return;
        }

        setTimeout(() => {
            if (window.bootstrap) {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            } else {
                alert.remove();
            }
        }, DELAY_MS);
    });
})();
