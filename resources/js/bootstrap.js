window.addEventListener('DOMContentLoaded', () => {
    document.documentElement.classList.add('is-ready');
});

const forceTopOnReload = () => {
    const navigation = performance.getEntriesByType('navigation')[0];
    const shouldReset = navigation?.type === 'reload';

    if (!shouldReset) {
        return;
    }

    if (window.location.hash) {
        history.replaceState(null, '', window.location.pathname + window.location.search);
    }

    [0, 50, 150, 350, 700].forEach((delay) => {
        window.setTimeout(() => window.scrollTo({ top: 0, left: 0, behavior: 'instant' }), delay);
    });
};

if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}

window.addEventListener('DOMContentLoaded', forceTopOnReload);
window.addEventListener('load', forceTopOnReload);
window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        window.scrollTo(0, 0);
    }

    forceTopOnReload();
});
