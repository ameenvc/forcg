(function () {
    'use strict';
    var root = document.getElementById('risk-history-workspace');
    if (!root) return;
    var description = root.querySelector('.rh-description'), descriptionWasOpen = false;
    window.addEventListener('beforeprint', function () { if (description) { descriptionWasOpen = description.open; description.open = true; } });
    window.addEventListener('afterprint', function () { if (description) description.open = descriptionWasOpen; });
    var list = root.querySelector('#history-timeline');
    if (!list) return;
    var rows = Array.from(list.querySelectorAll('[data-history-event]'));
    var search = root.querySelector('#history-search'), type = root.querySelector('#history-event-type'), sort = root.querySelector('#history-sort'), clear = root.querySelector('#history-clear');
    function refresh() {
        var term = search.value.trim().toLocaleLowerCase(), count = 0;
        rows.sort(function (a, b) { return (Number(a.dataset.eventOrder) - Number(b.dataset.eventOrder)) * (sort.value === 'oldest' ? -1 : 1); }).forEach(function (row) {
            row.hidden = Boolean((type.value && row.dataset.eventType !== type.value) || (term && !row.textContent.toLocaleLowerCase().includes(term)));
            if (!row.hidden) count++;
            list.appendChild(row);
        });
        root.querySelector('#history-match-count').textContent = count + ' of ' + rows.length + ' entries';
        root.querySelector('#history-no-matches').hidden = count > 0;
        clear.hidden = !term && !type.value;
    }
    search.addEventListener('input', refresh); type.addEventListener('change', refresh); sort.addEventListener('change', refresh);
    clear.addEventListener('click', function () { search.value = ''; type.value = ''; refresh(); search.focus(); });
    refresh();
}());
