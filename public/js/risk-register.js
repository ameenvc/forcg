(function () {
    'use strict';
    const root = document.getElementById('risk-register');
    if (!root) return;
    const get = id => document.getElementById(id);
    function start() {
        const $ = window.jQuery;
        if (!$ || !$.fn.DataTable) { get('risk-register-feedback').textContent = 'Table controls could not load. Refresh the page to try again.'; return; }
        const table = $('#tabledata').DataTable({
            responsive: false, autoWidth: false, order: [], pageLength: 25, lengthChange: false,
            columnDefs: [
                {targets: [2, 8], visible: false}, {targets: [0, 12], orderable: false}, {targets: [0, 1, 2, 8, 12], searchable: false},
                {targets: 0, width: '40px'}, {targets: 1, width: '52px'}, {targets: 3, width: '90px'},
                {targets: 4, width: '280px'}, {targets: 5, width: '110px'}, {targets: 6, width: '120px'},
                {targets: 7, width: '110px'}, {targets: 9, width: '190px'}, {targets: 10, width: '100px'},
                {targets: 11, width: '200px'}, {targets: 12, width: '110px'}
            ],
            dom: 't<"register-table-footer"ip>',
            language: {emptyTable: 'No risks have been added to this view.', zeroRecords: 'No matching risks. Try a different search or clear the filters.', info: 'Showing _START_–_END_ of _TOTAL_ risks', infoEmpty: 'No risks to show', infoFiltered: '(from _MAX_ in this view)', paginate: {previous: 'Previous', next: 'Next'}}
        });
        $('#tabledata').on('shown.bs.dropdown', '.btn-group', function () {
            const menu = this.querySelector('.dropdown-menu'), trigger = this.querySelector('[data-toggle="dropdown"]');
            if (!menu || !trigger) return;
            const rect = trigger.getBoundingClientRect(), width = menu.offsetWidth, height = menu.offsetHeight;
            menu.style.left = `${Math.max(8, Math.min(innerWidth - width - 8, rect.right - width))}px`;
            menu.style.top = `${rect.bottom + height + 8 < innerHeight ? rect.bottom + 5 : Math.max(8, rect.top - height - 5)}px`;
            this.closest('td').style.zIndex = '12';
        }).on('hidden.bs.dropdown', '.btn-group', function () { this.closest('td').style.zIndex = ''; });
        const closeOwnerMenus = except => table.rows().nodes().toArray().forEach(row => row.querySelectorAll('.register-owner-more[open]').forEach(menu => { if (menu !== except) menu.open = false; }));
        const closeRowMenus = () => { $(table.rows().nodes().toArray()).find('[data-toggle="dropdown"]').dropdown('hide'); closeOwnerMenus(); };
        get('content').addEventListener('scroll', closeRowMenus);
        window.addEventListener('resize', closeRowMenus);
        root.addEventListener('toggle', event => {
            const menu = event.target;
            if (!menu.matches('.register-owner-more')) return;
            if (!menu.open) { menu.closest('td').style.zIndex = ''; return; }
            closeOwnerMenus(menu);
            const popup = menu.querySelector('.register-owner-popover'), rect = menu.querySelector('summary').getBoundingClientRect();
            const width = popup.offsetWidth, height = popup.offsetHeight;
            popup.style.left = `${Math.max(8, Math.min(innerWidth - width - 8, rect.right - width))}px`;
            popup.style.top = `${rect.bottom + height + 8 < innerHeight ? rect.bottom + 5 : Math.max(8, rect.top - height - 5)}px`;
            menu.closest('td').style.zIndex = '11';
        }, true);
        root.addEventListener('keydown', event => {
            if (event.key === 'Escape' && event.target.closest('.register-owner-more')) {
                const menu = event.target.closest('.register-owner-more'); menu.open = false; menu.querySelector('summary').focus();
            }
        });
        root.querySelectorAll('.register-table-footer').forEach(footer => get('risk-table-footer').append(footer));
        const selected = new Set(), filters = [...root.querySelectorAll('[data-filter-column]')];
        const search = get('risk-register-search'), selectPage = get('risk-select-page');
        let busy = false;
        const pageNodes = () => table.rows({page: 'current', search: 'applied'}).nodes().toArray();
        function syncSelection() {
            table.rows().every(function () {
                const row = this.node(), checked = selected.has(row.dataset.riskId);
                row.classList.toggle('is-selected', checked);
                const checkbox = row.querySelector('.risk-row-select');
                if (checkbox) { checkbox.checked = checked; checkbox.disabled = busy; }
            });
            const nodes = pageNodes(), currentSelected = nodes.filter(row => selected.has(row.dataset.riskId)).length;
            selectPage.disabled = busy || nodes.length === 0;
            selectPage.checked = nodes.length > 0 && currentSelected === nodes.length;
            selectPage.indeterminate = currentSelected > 0 && currentSelected < nodes.length;
            get('risk-selection-bar').hidden = selected.size === 0;
            get('risk-selected-count').textContent = `${selected.size} selected`;
            get('risk-selected-note').textContent = selected.size > currentSelected ? 'Includes selections from other pages' : '';
        }
        function refreshView() {
            root.dispatchEvent(new CustomEvent('risk-register-viewchange'));
            syncSelection();
            const total = table.rows().count(), matching = table.rows({search: 'applied'}).count();
            root.dataset.empty = String(matching === 0);
            get('risk-register-empty').hidden = matching !== 0;
            get('risk-empty-title').textContent = total ? 'No matching risks' : 'No risks in this view';
            get('risk-empty-description').textContent = total ? 'Try another search or clear the filters to see your risks.' : 'Add a risk or clear any page filters to see more records.';
            get('risk-match-count').textContent = `${matching} of ${total} risks in this view`;
            const count = filters.filter(field => field.value).length;
            get('risk-filter-count').hidden = count === 0;
            get('risk-filter-count').textContent = count;
            get('risk-clear-filters').hidden = !count && !search.value;
            const rating = get('register-filter-rating').value;
            root.querySelectorAll('[data-register-rating]').forEach(card => card.setAttribute('aria-pressed', String(card.dataset.registerRating === rating)));
            const counts = {High: 0, Moderate: 0, Low: 0};
            table.rows().nodes().toArray().forEach(row => { if (row.dataset.riskRank in counts) counts[row.dataset.riskRank]++; });
            root.querySelectorAll('[data-register-count]').forEach(node => { node.textContent = node.dataset.registerCount ? counts[node.dataset.registerCount] : total; });
            const ids = table.rows({search: 'applied'}).data().toArray().map(row => String(row[2]).trim());
            root.querySelectorAll('.export-link').forEach(link => {
                const url = new URL(link.dataset.exportBase, window.location.href);
                url.searchParams.set('ids', JSON.stringify(ids)); link.href = url.toString();
                link.setAttribute('aria-disabled', String(ids.length === 0));
            });
            table.rows({page: 'current'}).every(function (_, __, offset) { const cell = table.cell(this.index(), 1).node(); if (cell) cell.textContent = table.page.info().start + offset + 1; });
        }
        function applyFilters() {
            if (busy) return;
            selected.clear();
            filters.forEach(field => {
                const escaped = $.fn.dataTable.util.escapeRegex(field.value);
                const term = !field.value ? '' : field.dataset.matchOwner ? `(^|\\s)${escaped}(?=\\s|$)` : `^${escaped}$`;
                table.column(Number(field.dataset.filterColumn)).search(term, true, false);
            });
            table.search(search.value).draw();
        }
        table.on('draw.dt', refreshView);
        table.on('preDraw.dt', closeRowMenus);
        search.addEventListener('input', applyFilters);
        filters.forEach(field => field.addEventListener('change', applyFilters));
        get('risk-toggle-filters').addEventListener('click', () => {
            const panel = get('risk-register-filters'); panel.hidden = !panel.hidden;
            get('risk-toggle-filters').setAttribute('aria-expanded', String(!panel.hidden));
        });
        get('risk-clear-filters').addEventListener('click', () => { if (busy) return; search.value = ''; filters.forEach(field => { field.value = ''; }); applyFilters(); });
        root.querySelectorAll('[data-register-rating]').forEach(card => card.addEventListener('click', () => { if (busy) return; get('register-filter-rating').value = card.dataset.registerRating; applyFilters(); }));
        root.querySelectorAll('[data-register-column]').forEach(field => field.addEventListener('change', () => {
            const column = table.column(Number(field.dataset.registerColumn));
            if (busy) { field.checked = column.visible(); return; }
            column.visible(field.checked); table.columns.adjust();
        }));
        get('risk-register-length').addEventListener('change', event => { if (!busy) table.page.len(Number(event.target.value)).draw(); });
        get('risk-register-sort').addEventListener('change', event => {
            if (busy) return;
            const orders = {default: [], rating: [[7, 'desc']], name: [[4, 'asc']], id: [[2, 'desc']]};
            table.order(orders[event.target.value]).draw();
        });
        $('#tabledata').on('change', '.risk-row-select', function () { if (busy) return; this.checked ? selected.add(this.value) : selected.delete(this.value); syncSelection(); });
        selectPage.addEventListener('change', () => { if (busy) return; pageNodes().forEach(row => selectPage.checked ? selected.add(row.dataset.riskId) : selected.delete(row.dataset.riskId)); syncSelection(); });
        get('risk-clear-selection').addEventListener('click', () => { if (!busy) { selected.clear(); syncSelection(); } });
        root.querySelectorAll('.export-link').forEach(link => link.addEventListener('click', event => {
            if (link.getAttribute('aria-disabled') === 'true') { event.preventDefault(); get('risk-register-feedback').textContent = 'There are no matching risks to export.'; }
        }));
        const edit = get('edit'), remove = get('del');
        if (edit) edit.addEventListener('click', () => {
            if (busy || !selected.size) return;
            const url = new URL(root.dataset.editUrl, window.location.href); url.searchParams.set('val', [...selected].join(',')); window.location.assign(url.toString());
        });
        if (remove) remove.addEventListener('click', async () => {
            if (busy || !selected.size || !window.confirm(`Delete ${selected.size} selected risk(s)? This includes selected risks on other pages.`)) return;
            busy = true; root.setAttribute('aria-busy', 'true');
            const locked = [...root.querySelectorAll('button, input, select')].map(control => [control, control.disabled]);
            locked.forEach(([control]) => { control.disabled = true; });
            const ids = [...selected], deleted = [], failed = [];
            for (const id of ids) {
                get('risk-register-feedback').textContent = `Deleting selected risks… ${deleted.length + failed.length + 1} of ${ids.length}`;
                try {
                    const url = new URL(root.dataset.deleteUrl, window.location.href); url.searchParams.set('val', id);
                    const response = await fetch(url, {credentials: 'same-origin', redirect: 'error', headers: {Accept: 'application/json'}});
                    if (!response.ok) throw new Error('Delete failed');
                    deleted.push(id);
                } catch (_) { failed.push(id); }
            }
            table.rows((index, data) => deleted.includes(String(data[2]).trim())).remove();
            deleted.forEach(id => selected.delete(id));
            busy = false; root.setAttribute('aria-busy', 'false'); locked.forEach(([control, disabled]) => { control.disabled = disabled; });
            table.draw(false);
            get('risk-register-feedback').textContent = `Deleted ${deleted.length} risk(s).${failed.length ? ` ${failed.length} could not be deleted and remain selected. Please check your access or try again.` : ''}`;
        });
        document.addEventListener('click', event => { root.querySelectorAll('.register-menu[open], .register-owner-more[open]').forEach(menu => { if (!menu.contains(event.target)) menu.open = false; }); });
        refreshView();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
}());
