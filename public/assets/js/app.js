document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('themeToggle');
    const root = document.documentElement;
    const storedTheme = localStorage.getItem('mediai-theme');

    const applyTheme = (theme) => {
        root.setAttribute('data-bs-theme', theme);
        localStorage.setItem('mediai-theme', theme);
        if (themeToggle) {
            const icon = themeToggle.querySelector('i');
            if (icon) {
                icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
            }
        }
    };

    if (storedTheme) {
        applyTheme(storedTheme);
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            applyTheme(currentTheme);
        });
    }

    const showLoader = () => {
        const overlay = document.querySelector('.loading-overlay');
        if (overlay) {
            overlay.classList.add('visible');
        }
    };

    document.querySelectorAll('a, button[type="submit"]').forEach((element) => {
        element.addEventListener('click', () => {
            if (element.getAttribute('href') === '#') {
                return;
            }
            if (element.tagName === 'BUTTON' && element.type === 'submit') {
                showLoader();
            }
            if (element.tagName === 'A' && element.getAttribute('href') && ! element.getAttribute('href').startsWith('#')) {
                showLoader();
            }
        });
    });
});
