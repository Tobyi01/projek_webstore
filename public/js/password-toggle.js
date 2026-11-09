function updatePasswordToggle(container) {
    const input = container.querySelector('input[type="password"], input[type="text"]');
    const button = container.querySelector('[data-password-toggle-button]');
    if (!input || !button) {
        return;
    }

    const isVisible = input.type === 'text';
    const visibleIcon = button.querySelector('[data-password-visible-icon]');
    const hiddenIcon = button.querySelector('[data-password-hidden-icon]');

    if (visibleIcon && hiddenIcon) {
        visibleIcon.style.display = isVisible ? '' : 'none';
        hiddenIcon.style.display = isVisible ? 'none' : '';
    }

    button.setAttribute('aria-label', `${isVisible ? 'Sembunyikan' : 'Tampilkan'} password`);
    button.setAttribute('aria-pressed', String(isVisible));
}

document.querySelectorAll('[data-password-toggle]').forEach(updatePasswordToggle);

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

    input.type = input.type === 'password' ? 'text' : 'password';
    updatePasswordToggle(container);
});
