function initFilterToggle() {
    const toggleBtn = document.querySelector('.filter-toggle');
    const filterPanel = document.getElementById('filter-panel');
    const backdrop = document.querySelector('.filter-backdrop');
    const closeBtn = filterPanel?.querySelector('.filter-close');

    if (!toggleBtn || !filterPanel) return;

    function openPanel() {
        toggleBtn.setAttribute('aria-expanded', 'true');
        filterPanel.setAttribute('aria-hidden', 'false');
        filterPanel.classList.add('is-open');
        backdrop?.classList.add('is-open');
        toggleBtn.textContent = 'Filter ausblenden';

        const firstFocusable = filterPanel.querySelector(
            'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        firstFocusable?.focus();
    }

    function closePanel() {
        toggleBtn.setAttribute('aria-expanded', 'false');
        filterPanel.setAttribute('aria-hidden', 'true');
        filterPanel.classList.remove('is-open');
        backdrop?.classList.remove('is-open');
        toggleBtn.textContent = 'Filter einblenden';
        toggleBtn.focus();
    }

    toggleBtn.addEventListener('click', () => {
        const isOpen = toggleBtn.getAttribute('aria-expanded') === 'true';
        isOpen ? closePanel() : openPanel();
    });

    closeBtn?.addEventListener('click', closePanel);
    backdrop?.addEventListener('click', closePanel);

    filterPanel.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closePanel();
    });

    window.matchMedia('(max-width: 460px)').addEventListener('change', (e) => {
        if (!e.matches) {
            toggleBtn.setAttribute('aria-expanded', 'false');
            filterPanel.setAttribute('aria-hidden', 'false');
            filterPanel.classList.remove('is-open');
            backdrop?.classList.remove('is-open');
        }
    });
}

document.addEventListener('DOMContentLoaded', initFilterToggle);