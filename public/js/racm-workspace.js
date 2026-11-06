/* Selection persists across sorting, filtering and DataTables pagination. */
(function () {
    'use strict';
    const root = document.querySelector('.workflow-racm');
    if (!root || !window.jQuery) return;
    const $ = window.jQuery, selected = new Set();
    const table = document.getElementById('tabledata');
    const bar = document.getElementById('racm-selection-bar');
    function visible() { return Array.from(table.querySelectorAll('tbody [data-racm-select]')); }
    function sync() {
        visible().forEach(box => { box.checked = selected.has(box.id); });
        bar.hidden = selected.size === 0;
        document.getElementById('racm-selection-count').textContent = selected.size;
        const all = document.getElementById('racm-select-visible'), boxes = visible();
        all.checked = boxes.length > 0 && boxes.every(box => box.checked);
        all.indeterminate = boxes.some(box => box.checked) && !all.checked;
    }
    root.addEventListener('change', event => {
        if (event.target.matches('[data-racm-select]')) {
            if (event.target.checked) selected.add(event.target.id); else selected.delete(event.target.id);
            sync();
        } else if (event.target.id === 'racm-select-visible') {
            visible().forEach(box => { if (event.target.checked) selected.add(box.id); else selected.delete(box.id); });
            sync();
        } else if (event.target.id === 'racm-status-filter' && $.fn.dataTable.isDataTable(table)) {
            const value = event.target.value;
            $(table).DataTable().column(6).search(value ? '^' + $.fn.dataTable.util.escapeRegex(value) + '(?:\\s|$)' : '', true, false).draw();
        }
    });
    document.getElementById('racm-clear-selection').addEventListener('click', () => { selected.clear(); sync(); });
    document.getElementById('request-review-approve').addEventListener('click', () => {
        if (!selected.size) return;
        document.getElementById('recordids').value = Array.from(selected).join(',');
        document.getElementById('racm-review-selection-summary').textContent = 'Request review for ' + selected.size + ' selected criteria. Reviewers assess the thresholds before the approver makes the final decision.';
        const modal = $('#add-request-review-approve');
        modal.modal('show');
        modal.find('select').each(function () {
            if (!$(this).data('select2')) $(this).select2({ width: '100%', dropdownParent: modal });
        });
    });
    $(table).on('draw.dt', sync);
    sync();
})();
