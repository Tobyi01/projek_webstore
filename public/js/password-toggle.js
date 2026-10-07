document.addEventListener('click', (event) => {
    const button = event.target instanceof Element
        ? event.target.closest('[data-password-toggle-button]')
        : null;

    if (!button) {
        return;
    }

    const container = button.closest('[data-password-toggle]');
    const input = container?.querySelector('input[type="password"], input[type="text"]');

    if (!input) {
        return;
    }

    const isVisible = input.type === 'password';
    input.type = isVisible ? 'text' : 'password';
    const visibleIcon = button.querySelector('[data-password-visible-icon]');
    const hiddenIcon = button.querySelector('[data-password-hidden-icon]');

    if (visibleIcon && hiddenIcon) {
        visibleIcon.style.display = isVisible ? 'none' : '';
        hiddenIcon.style.display = isVisible ? '' : 'none';
    }

    button.setAttribute('aria-label', `${isVisible ? 'Sembunyikan' : 'Tampilkan'} password`);
    button.setAttribute('aria-pressed', String(isVisible));
});
