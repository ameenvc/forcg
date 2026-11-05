(function () {
    'use strict';
    function start() {
        const root = document.getElementById('risk-register'), panel = document.getElementById('risk-register-ai');
        if (!root || !panel) return;
        const toggle = document.getElementById('risk-register-ai-toggle'), run = document.getElementById('risk-register-ai-run');
        const scope = document.getElementById('risk-register-ai-scope'), status = document.getElementById('risk-register-ai-status'), results = document.getElementById('risk-register-ai-results');
        let controller = null, generation = 0;
        const table = () => window.jQuery?.fn?.dataTable?.isDataTable('#tabledata') ? window.jQuery('#tabledata').DataTable() : null;
        const matchingRows = () => table()?.rows({search: 'applied', order: 'applied'}).nodes().toArray() || [];
        function refresh() {
            generation++; if (controller) controller.abort(); controller = null;
            results.hidden = true; results.replaceChildren(); status.textContent = '';
            const count = matchingRows().length;
            scope.textContent = count ? `Review scope: first ${Math.min(count, 50)} of ${count} matching risks, across all pages, in the current sort order.` : 'No matching risks to review. Adjust your search or filters.';
            if (run) { run.disabled = count === 0; run.textContent = 'Review matching risks'; }
        }
        const close = document.getElementById('risk-register-ai-close');
        let previousOverflow = '';
        function openModal(open) {
            if (open === !panel.hidden) return;
            if (open) { previousOverflow = document.body.style.overflow; document.body.style.overflow = 'hidden'; }
            else { document.body.style.overflow = previousOverflow; }
            panel.hidden = !open; toggle.setAttribute('aria-expanded', String(open));
            if (open) { refresh(); (run && !run.disabled ? run : close).focus(); }
            else { refresh(); toggle.focus(); }
        }
        toggle.addEventListener('click', () => openModal(panel.hidden));
        close.addEventListener('click', () => openModal(false));
        panel.addEventListener('click', event => { if (event.target === panel) openModal(false); });
        panel.addEventListener('keydown', event => {
            if (event.key === 'Escape') { event.preventDefault(); openModal(false); }
            if (event.key === 'Tab') {
                const items = [...panel.querySelectorAll('button, a[href], input, select, textarea, summary, [tabindex="0"]')].filter(item => !item.disabled && item.getClientRects().length);
                const first = items[0], last = items[items.length - 1];
                if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
                else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
            }
        });
        root.addEventListener('risk-register-viewchange', refresh);
        if (!run) return;
        run.addEventListener('click', async () => {
            const rows = matchingRows(); if (!rows.length || run.disabled) return;
            const current = ++generation;
            controller = new AbortController(); const requestController = controller;
            const dt = table();
            const cell = (row, column) => dt.cell(dt.row(row).index(), column).node();
            const text = (row, column, max = 190) => (cell(row, column)?.textContent || '').trim().slice(0, max);
            const risks = rows.slice(0, 50).map(row => ({
                id: text(row, 3), name: text(row, 4),
                rating: ['High', 'Moderate', 'Low'].includes(row.dataset.riskRank) ? row.dataset.riskRank : null,
                group: text(row, 5), area: text(row, 6),
                response: text(row, 10, 500).replace(/^—$/, ''),
                stage: text(row, 11), owner_assigned: row.dataset.ownerAssigned === 'true'
            }));
            run.disabled = true; if (document.activeElement === run) close.focus(); run.textContent = 'Reviewing…'; results.hidden = true; status.textContent = 'Preparing a review of the matching risks…';
            const timer = setTimeout(() => requestController.abort(), 55000);
            try {
                const response = await fetch(panel.dataset.url, {
                    method: 'POST', credentials: 'same-origin', signal: requestController.signal,
                    headers: {'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''},
                    body: JSON.stringify({action: 'register_review', concern: 'Review the current filtered risk register', risks, total_matching: rows.length})
                });
                if (!response.ok) throw new Error('Review failed');
                const data = await response.json(); if (current !== generation) return;
                results.replaceChildren();
                const summary = document.createElement('p'); summary.textContent = data.review.summary; results.append(summary);
                [['gaps', 'Information gaps'], ['proposed_controls', 'Recommended follow-up'], ['questions', 'Reviewer questions']].forEach(([key, label]) => {
                    const section = document.createElement('section'), heading = document.createElement('h4'), list = document.createElement('ul');
                    heading.textContent = label; section.append(heading);
                    (data.review[key].length ? data.review[key] : ['No specific findings in the sampled register fields. Check the full records for supporting evidence.']).forEach(value => {
                        const item = document.createElement('li'); item.textContent = value; list.append(item);
                    });
                    section.append(list); results.append(section);
                });
                window.CgbodRiskAiFeedback?.mount(results, data);
                status.textContent = data.notice; results.hidden = false;
            } catch (error) {
                if (current === generation) status.textContent = 'The review could not be completed. Please try again or ask your administrator to check the AI configuration.';
            } finally {
                clearTimeout(timer);
                if (current === generation) { controller = null; run.disabled = false; run.textContent = 'Review matching risks'; }
            }
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
})();
