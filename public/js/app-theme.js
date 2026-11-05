(function () {
    'use strict';
    if (window.cgbodAppThemeAdapterReady) return; window.cgbodAppThemeAdapterReady = true;
    const savedStyles = new WeakMap(), chartStyles = new WeakMap(), chartStates = new WeakMap(), chartFonts = new WeakSet(), chartLibraryFonts = new WeakSet();
    const dark = () => document.documentElement.dataset.riskTheme === 'dark';
    function neutral(value, bright) {
        const match = value?.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*([\d.]+))?\)$/);
        if (!match || (match[4] !== undefined && Number(match[4]) < .8)) return false;
        const colors = match.slice(1, 4).map(Number);
        return Math.max(...colors) - Math.min(...colors) <= (bright ? 18 : 55) && (bright ? Math.min(...colors) >= 220 : Math.max(...colors) <= 125);
    }
    function adaptInline(root) {
        if (!(root instanceof Element)) return;
        const elements = [...(root.matches('[style]') ? [root] : []), ...root.querySelectorAll('[style]')];
        elements.forEach(element => {
            if (!(element instanceof HTMLElement) || !element.closest('#main-container,.modal,.app-theme-preview') || element.closest('[data-theme-preserve],.badge,.status,.alert') || element.matches('img,canvas,iframe,video,button,a')) return;
            let saved = savedStyles.get(element);
            if (!dark()) {
                if (saved) { Object.entries(saved).forEach(([key, original]) => { if (element.style.getPropertyValue(key) === original.applied) element.style.setProperty(key, original.value, original.priority); }); savedStyles.delete(element); }
                return;
            }
            function override(key, variable) { if (!saved) { saved = {}; savedStyles.set(element, saved); } if (saved[key]) return; const original = {value: element.style.getPropertyValue(key), priority: element.style.getPropertyPriority(key), applied: variable}; saved[key] = original; element.style.setProperty(key, variable, 'important'); }
            const background = element.style.backgroundColor;
            if (neutral(background, true)) override('background-color', 'var(--app-surface)');
            if (neutral(element.style.color, false)) {
                // Preserve dark text on deliberately coloured light status surfaces.
                let parent = element, lightColour = false;
                for (let i = 0; parent && i < 4; i++, parent = parent.parentElement) { const bg = getComputedStyle(parent).backgroundColor; if (bg !== 'rgba(0, 0, 0, 0)' && bg !== 'transparent') { const values = bg.match(/[\d.]+/g)?.slice(0, 3).map(Number); lightColour = values && Math.min(...values) > 150 && !neutral(bg, true); break; } }
                if (!lightColour) override('color', 'var(--app-text)');
            }
        });
    }
    function adaptCharts(active = dark()) {
        const Chart = window.Chart; if (!Chart) return;
        if (!chartLibraryFonts.has(Chart)) {
            chartLibraryFonts.add(Chart); const family = getComputedStyle(document.body).fontFamily;
            if (Chart.defaults?.global) { Chart.defaults.global.defaultFontFamily = family; Chart.defaults.global.defaultFontSize = 12; }
            else if (Chart.defaults?.font) { Chart.defaults.font.family = family; Chart.defaults.font.size = 12; }
        }
        Object.values(Chart.instances || {}).forEach(chart => {
            if (!chart.options || chart.canvas?.closest('[data-theme-preserve]')) return;
            const legacy = !!Chart.defaults?.global || Array.isArray(chart.options.scales?.xAxes) || Array.isArray(chart.options.scales?.yAxes);
            const fontChanged = !chartFonts.has(chart);
            if (fontChanged) {
                chartFonts.add(chart); const family = getComputedStyle(document.body).fontFamily;
                if (legacy) {
                    if (chart.options.legend?.labels) { chart.options.legend.labels.fontFamily = family; chart.options.legend.labels.fontSize = 12; }
                    if (chart.options.title) chart.options.title.fontFamily = family;
                    if (chart.options.tooltips) ['titleFontFamily', 'bodyFontFamily', 'footerFontFamily'].forEach(key => { chart.options.tooltips[key] = family; });
                } else if (chart.options.plugins?.legend?.labels) chart.options.plugins.legend.labels.font = {...chart.options.plugins.legend.labels.font, family, size:12};
                Object.values(chart.options.scales || {}).forEach(axes => (Array.isArray(axes) ? axes : [axes]).forEach(scale => {
                    if (!scale.ticks) return;
                    if (legacy) { scale.ticks.fontFamily = family; scale.ticks.fontSize = 12; }
                    else if (typeof scale.ticks.font !== 'function') scale.ticks.font = {...scale.ticks.font, family, size:12};
                }));
                document.fonts?.ready.then(() => { if (chart.ctx) { try { chart.update(legacy ? 0 : 'none'); } catch (error) {} } });
            }
            if (chartStates.get(chart) === active && !fontChanged) return;
            chartStates.set(chart, active);
            let snapshot = chartStyles.get(chart); if (!snapshot) { snapshot = []; chartStyles.set(chart, snapshot); }
            const parentAt = path => path.slice(0, -1).reduce((object, key) => object?.[key], chart.options);
            function paint(path, value) { const object = parentAt(path), key = path[path.length - 1]; if (!object) return; snapshot.push({path, own: Object.prototype.hasOwnProperty.call(object, key), value: object[key]}); object[key] = value; }
            if (!active) {
                snapshot.forEach(entry => { const object = parentAt(entry.path), key = entry.path[entry.path.length - 1]; if (object) { if (entry.own) object[key] = entry.value; else delete object[key]; } });
                chartStyles.delete(chart);
            } else {
                paint(['legend', 'labels', 'fontColor'], '#bfd0eb'); paint(['title', 'fontColor'], '#dce7f8');
                paint(['plugins', 'legend', 'labels', 'color'], '#bfd0eb'); paint(['plugins', 'title', 'color'], '#dce7f8');
                Object.entries(chart.options.scales || {}).forEach(([name, axes]) => (Array.isArray(axes) ? axes : [axes]).forEach((scale, index) => {
                    const path = ['scales', name, ...(Array.isArray(axes) ? [index] : [])];
                    paint([...path, 'ticks', legacy ? 'fontColor' : 'color'], '#a9bddb');
                    paint([...path, scale.gridLines ? 'gridLines' : 'grid', 'color'], '#30415b');
                }));
            }
            if (active || snapshot.length || fontChanged) { try { chart.update(legacy ? 0 : 'none'); } catch (error) { /* A chart may be loading or being removed. */ } }
        });
    }
    let pending = false;
    function apply() { if (pending) return; pending = true; requestAnimationFrame(() => { pending = false; adaptInline(document.body); adaptCharts(); }); }
    document.documentElement.addEventListener('cgbod:themechange', apply);
    const start = () => { apply(); new MutationObserver(records => { if (records.some(record => record.addedNodes.length)) apply(); }).observe(document.body, {childList: true, subtree: true}); };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
    window.addEventListener('load', () => setTimeout(apply, 0));
    window.addEventListener('beforeprint', () => adaptCharts(false));
    window.addEventListener('afterprint', apply);
})();
