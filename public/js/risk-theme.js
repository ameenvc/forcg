(function () {
    'use strict';
    if (window.cgbodRiskThemeReady) return; window.cgbodRiskThemeReady = true;
    const key = 'cgbod-risk-theme';
    let preference = 'light';
    try {
        const saved = localStorage.getItem(key);
        if (saved === 'light' || saved === 'dark') preference = saved;
        // Resolve the former System setting once, then keep an explicit choice.
        else if (saved === 'system') {
            preference = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            localStorage.setItem(key, preference);
        }
    } catch (error) { /* Theme still works when browser storage is unavailable. */ }
    function apply() {
        document.documentElement.dataset.riskTheme = preference;
        document.documentElement.style.colorScheme = preference;
        document.querySelectorAll('[data-risk-theme-select]').forEach(select => { select.value = preference; });
        document.querySelectorAll('[data-risk-theme-toggle]').forEach(button => {
            const dark = preference === 'dark';
            const label = dark ? 'Switch to light theme' : 'Switch to dark theme';
            button.setAttribute('aria-label', label);
            button.title = label;
            button.setAttribute('aria-pressed', String(dark));
            const icon = button.querySelector('i');
            if (icon) icon.className = dark ? 'fa fa-sun' : 'fa fa-moon';
        });
        document.documentElement.dispatchEvent(new CustomEvent('cgbod:themechange'));
    }
    document.addEventListener('click', event => {
        if (!event.target.closest('[data-risk-theme-toggle]')) return;
        preference = preference === 'dark' ? 'light' : 'dark';
        try { localStorage.setItem(key, preference); } catch (error) {}
        apply();
    });
    document.addEventListener('change', event => {
        if (!event.target.matches('[data-risk-theme-select]')) return;
        preference = event.target.value === 'dark' ? 'dark' : 'light';
        try { localStorage.setItem(key, preference); } catch (error) {}
        apply();
    });
    window.addEventListener('storage', event => {
        if (event.key === key || event.key === null) { preference = event.newValue === 'dark' ? 'dark' : 'light'; apply(); }
    });
    apply();
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', apply);
})();
