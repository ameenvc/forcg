(function () {
    'use strict';
    function start() {
        const root = document.getElementById('risk-closed-register'); if (!root) return;
        const get = id => document.getElementById(id), $ = window.jQuery;
        const feedback = get('closed-risk-feedback');
        if (!$?.fn?.DataTable) { feedback.textContent = 'Table controls could not load. Refresh to try again.'; return; }
        const table = $('#tabledata').DataTable({
            responsive: false, autoWidth: false, order: [[0, 'asc']], pageLength: 25, lengthChange: false,
            columnDefs: [{targets: 1, visible: false, searchable: false}, {targets: [0, 12], orderable: false, searchable: false},
                ...['52px', '0px', '90px', '280px', '110px', '130px', '110px', '170px', '100px', '100px', '100px', '110px', '145px'].map((width, targets) => ({targets, width}))],
            dom: 't<"register-table-footer"ip>',
            language: {emptyTable: 'No closed risks yet. Closed records will appear here.', zeroRecords: 'No matching closed risks. Try another search or clear the filters.', info: 'Showing _START_–_END_ of _TOTAL_ closed risks', infoEmpty: 'No closed risks to show', infoFiltered: '(from _MAX_ total)', paginate: {previous: 'Previous', next: 'Next'}}
        });
        root.querySelectorAll('.register-table-footer').forEach(footer => get('closed-risk-footer').append(footer));
        const search = get('closed-risk-search'), filters = [...root.querySelectorAll('[data-closed-filter]')];
        function refresh() {
            const matching = table.rows({search: 'applied'}), total = table.rows().count();
            get('closed-risk-count').textContent = `${matching.count()} of ${total} closed risks`;
            get('closed-risk-clear').hidden = !search.value && !filters.some(field => field.value);
            const ids = matching.data().toArray().map(row => String(row[1]).trim());
            root.querySelectorAll('[data-closed-export]').forEach(link => { const url = new URL(link.dataset.closedExport, location.href); url.searchParams.set('ids', JSON.stringify(ids)); link.href = url.toString(); link.setAttribute('aria-disabled', String(!ids.length)); });
            table.rows({page: 'current'}).every(function (_, __, offset) { table.cell(this.index(), 0).node().textContent = table.page.info().start + offset + 1; });
        }
        function filter() { filters.forEach(field => table.column(Number(field.dataset.closedFilter)).search(field.value ? '^' + $.fn.dataTable.util.escapeRegex(field.value) + '$' : '', true, false)); table.search(search.value).draw(); }
        table.on('draw.dt', refresh); search.addEventListener('input', filter); filters.forEach(field => field.addEventListener('change', filter));
        get('closed-risk-clear').addEventListener('click', () => { search.value = ''; filters.forEach(field => { field.value = ''; }); filter(); });
        get('closed-risk-filter-toggle').addEventListener('click', event => { const panel = get('closed-risk-filters'); panel.hidden = !panel.hidden; event.currentTarget.setAttribute('aria-expanded', String(!panel.hidden)); });
        get('closed-risk-length').addEventListener('change', event => table.page.len(Number(event.target.value)).draw());
        get('closed-risk-sort').addEventListener('change', event => { table.order({default: [[0, 'asc']], rating: [[6, 'desc']], name: [[3, 'asc']], target: [[9, 'asc']]}[event.target.value]).draw(); });
        root.addEventListener('click', async event => {
            const link = event.target.closest('[data-closed-export]');
            if (link?.getAttribute('aria-disabled') === 'true') event.preventDefault();
            const button = event.target.closest('[data-closed-view]'); if (!button || button.disabled) return;
            button.disabled = true; feedback.textContent = 'Loading risk details…';
            try {
                const response = await fetch(button.dataset.closedView, {headers: {Accept: 'application/json'}, credentials: 'same-origin'});
                if (!response.ok) throw new Error('Details unavailable');
                const data = await response.json(); if (!data.success || !data.view) throw new Error('Details unavailable');
                $(root.querySelector('.risk-area')).html(data.view); $('#' + button.dataset.modalId).one('hidden.bs.modal', () => button.focus()).modal('show'); feedback.textContent = '';
            } catch (error) { feedback.textContent = 'Risk details could not load. Please try again or open the risk name to view the full record.'; }
            finally { button.disabled = false; }
        });
        document.addEventListener('click', event => root.querySelectorAll('.register-menu[open]').forEach(menu => { if (!menu.contains(event.target)) menu.open = false; }));
        refresh();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
})();
