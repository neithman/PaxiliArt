document.querySelectorAll('[data-search-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const header = document.querySelector('.site-header');
        header?.classList.toggle('search-open');
        header?.querySelector('.search-form input')?.focus();
    });
});

document.querySelectorAll('[data-confirm]').forEach((button) => {
    button.addEventListener('click', (event) => {
        if (!window.confirm(button.dataset.confirm)) {
            event.preventDefault();
        }
    });
});
