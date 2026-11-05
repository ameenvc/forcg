(function () {
    'use strict';
    if (window.cgbodRiskThemeReady) return; window.cgbodRiskThemeReady = true;
    const key = 'cgbod-risk-theme', media = window.matchMedia('(prefers-color-scheme: dark)');
    let preference = 'system';
    try { const saved = localStorage.getItem(key); if (['system', 'light', 'dark'].includes(saved)) preference = saved; } catch (error) { /* Theme still works when browser storage is unavailable. */ }
    function apply() {
        document.documentElement.dataset.riskTheme = preference === 'system' ? (media.matches ? 'dark' : 'light') : preference;
        document.documentElement.style.colorScheme = document.documentElement.dataset.riskTheme;
        document.querySelectorAll('[data-risk-theme-select]').forEach(select => { select.value = preference; });
    }
    document.addEventListener('change', event => { if (!event.target.matches('[data-risk-theme-select]')) return; preference = event.target.value; try { localStorage.setItem(key, preference); } catch (error) {} apply(); });
    window.addEventListener('storage', event => { if (event.key === key) { preference = ['light', 'dark'].includes(event.newValue) ? event.newValue : 'system'; apply(); } });
    if (media.addEventListener) media.addEventListener('change', apply); else media.addListener(apply);
    apply();
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', apply);
})();
