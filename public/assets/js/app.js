document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('themeToggle');
    const storedTheme = localStorage.getItem('mediai-theme');

    if (storedTheme) {
        document.body.setAttribute('data-theme', storedTheme);
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.body.setAttribute('data-theme', currentTheme);
            localStorage.setItem('mediai-theme', currentTheme);
        });
    }

    const loader = document.createElement('div');
    loader.className = 'loading-overlay';
    loader.innerHTML = '<div class="loader"></div>';
    document.body.appendChild(loader);

    document.addEventListener('click', (event) => {
        const target = event.target instanceof HTMLElement ? event.target.closest('a, button[type="submit"]') : null;
        if (!target) {
            return;
        }

        if (target.tagName === 'A' && target.getAttribute('href')?.startsWith('#')) {
            return;
        }

        loader.classList.add('show');
    });

    window.addEventListener('load', () => {
        loader.classList.remove('show');
    });
});
