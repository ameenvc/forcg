(function () {
    'use strict';
    const root = document.getElementById('risk-analysis'); if (!root) return;
    let data = JSON.parse(root.dataset.analysis), filters = JSON.parse(root.dataset.filters), generatedAt = root.dataset.generatedAt;
    const get = id => document.getElementById(id);
    const ratings = ['High', 'Moderate', 'Low', 'Unassessed'], colors = {High: '#eb6476', Moderate: '#e8b453', Low: '#48a991', Unassessed: '#a2acc0'};
    const group = get('analysis-group'), search = get('analysis-department-search'), sort = get('analysis-department-sort');
    function current() { return group.value ? data.groups[group.value] : {counts: data.totals, departments: data.departments, dates: data.dates, stages: data.stages, responses: data.responses, quality: data.quality}; }
    function renderDepartments() {
        const departments = Object.entries(current().departments), max = Math.max(1, ...departments.map(([, counts]) => counts.total));
        const matching = departments.filter(([name]) => name.toLocaleLowerCase().includes(search.value.trim().toLocaleLowerCase()));
        matching.sort(([nameA, a], [nameB, b]) => sort.value === 'name' ? nameA.localeCompare(nameB) : ((sort.value === 'high' ? b.High - a.High : 0) || b.total - a.total || nameA.localeCompare(nameB)));
        const chart = get('analysis-department-chart'); chart.replaceChildren();
        get('analysis-department-count').textContent = `${matching.length} of ${departments.length} departments · ${group.value || 'All groups'}`;
        if (!matching.length) { const empty = document.createElement('p'); empty.className = 'analysis-empty'; empty.textContent = departments.length ? 'No matching departments. Try another search.' : 'No department data for this view yet.'; chart.append(empty); return; }
        matching.forEach(([name, counts]) => {
            const row = document.createElement('div'); row.className = 'analysis-department-row'; row.setAttribute('role', 'listitem');
            const label = document.createElement('strong'); label.textContent = name; label.title = name;
            const track = document.createElement('div'); track.className = 'analysis-bar-track'; track.setAttribute('aria-hidden', 'true');
            ratings.forEach(rating => { const segment = document.createElement('span'); segment.style.width = `${counts[rating] / max * 100}%`; segment.style.backgroundColor = colors[rating]; segment.title = `${rating}: ${counts[rating]}`; track.append(segment); });
            const total = document.createElement('b'); total.textContent = counts.total; total.title = 'Total risks';
            const detail = document.createElement('small'); detail.textContent = ratings.map(rating => `${rating}: ${counts[rating]}`).join(' · ');
            row.append(label, track, total, detail); chart.append(row);
        });
    }
    function render() {
        const {counts} = current();
        root.querySelectorAll('[data-analysis-count]').forEach(node => { node.textContent = counts[node.dataset.analysisCount]; });
        get('analysis-selected-scope').textContent = group.value || 'All groups'; get('analysis-donut-total').textContent = counts.total;
        let offset = 0; const stops = [];
        ratings.forEach(rating => {
            const share = counts.total ? counts[rating] / counts.total * 100 : 0;
            root.querySelector(`[data-analysis-legend="${rating}"]`).textContent = counts[rating];
            root.querySelector(`[data-analysis-percent="${rating}"]`).textContent = `${Math.round(share)}%`;
            stops.push(`${colors[rating]} ${offset}% ${offset + share}%`); offset += share;
        });
        const donut = get('analysis-donut'); donut.style.background = counts.total ? `conic-gradient(${stops.join(',')})` : 'var(--cgbod-border)';
        donut.setAttribute('aria-label', `${counts.total} risks. ${ratings.map(rating => `${rating}: ${counts[rating]}`).join(', ')}`);
        get('analysis-rating-note').textContent = counts.total ? `${Math.round(counts.High / counts.total * 100)}% of risks in this view are rated High. Ratings use the approved 3 × 3 matrix.` : 'No risks recorded in this view yet.';
        root.querySelectorAll('[data-analysis-group]').forEach(button => { const selected = button.dataset.analysisGroup === group.value; button.setAttribute('aria-pressed', String(selected)); button.closest('tr').classList.toggle('is-active', selected); });
        root.querySelectorAll('[data-analysis-quality]').forEach(node => { node.textContent = current().quality[node.dataset.analysisQuality]; });
        renderBreakdown('analysis-stages', current().stages); renderBreakdown('analysis-responses', current().responses);
        renderTrend(); renderDepartments();
    }
    group.addEventListener('change', render); search.addEventListener('input', renderDepartments); sort.addEventListener('change', renderDepartments);
    root.addEventListener('click', event => { const button = event.target.closest('[data-analysis-group]'); if (button) { group.value = group.value === button.dataset.analysisGroup ? '' : button.dataset.analysisGroup; render(); } });
    get('analysis-reset').addEventListener('click', () => { group.value = ''; search.value = ''; sort.value = 'total'; render(); });
    function renderBreakdown(id, values) {
        const container = get(id); container.replaceChildren();
        const entries = Object.entries(values).sort((a, b) => b[1] - a[1]);
        if (!entries.length) { container.textContent = 'No records in this view.'; return; }
        entries.forEach(([label, count]) => { const row = document.createElement('div'), name = document.createElement('span'), number = document.createElement('strong'); name.textContent = label; number.textContent = count; row.append(name, number); container.append(row); });
    }
    function renderTrend() {
        const values = current().dates, dates = Object.keys(values).sort(), chart = get('analysis-trend-chart'); chart.replaceChildren();
        if (!dates.length) { chart.textContent = 'No dated registrations in this view.'; get('analysis-trend-scope').textContent = 'Records with missing creation dates cannot be placed on the timeline.'; return; }
        const daily = get('analysis-trend-unit').value === 'day', end = filters.to || dates[dates.length - 1];
        const boundary = new Date(end + 'T00:00:00Z');
        if (daily) boundary.setUTCDate(boundary.getUTCDate() - 29); else { boundary.setUTCDate(1); boundary.setUTCMonth(boundary.getUTCMonth() - 11); }
        const start = [boundary.toISOString().slice(0, 10), filters.from || '0000-01-01'].sort()[1];
        const buckets = {}, cursor = new Date(start + 'T00:00:00Z');
        if (!daily) cursor.setUTCDate(1);
        for (let index = 0; index < (daily ? 30 : 12) && cursor.toISOString().slice(0, 10) <= end; index++) { const key = cursor.toISOString().slice(0, daily ? 10 : 7); buckets[key] = 0; if (daily) cursor.setUTCDate(cursor.getUTCDate() + 1); else cursor.setUTCMonth(cursor.getUTCMonth() + 1); }
        dates.filter(date => date >= start && date <= end).forEach(date => { const key = date.slice(0, daily ? 10 : 7); if (key in buckets) buckets[key] += values[date]; });
        const max = Math.max(1, ...Object.values(buckets));
        get('analysis-trend-scope').textContent = `${daily ? 'Up to 30 days' : 'Up to 12 months'}: ${start} to ${end} · ${group.value || 'All groups'} · creation dates only`;
        Object.entries(buckets).forEach(([label, count]) => { const row = document.createElement('div'), name = document.createElement('span'), track = document.createElement('div'), bar = document.createElement('i'), number = document.createElement('strong'); row.className = 'analysis-trend-row'; row.setAttribute('role', 'listitem'); name.textContent = label; number.textContent = count; track.className = 'analysis-trend-track'; track.setAttribute('aria-hidden', 'true'); bar.style.width = `${count / max * 100}%`; track.append(bar); row.append(name, track, number); chart.append(row); });
    }
    function syncGroupChoices() {
        const previous = group.value; group.replaceChildren(new Option('All groups', ''));
        const body = root.querySelector('.analysis-group-table tbody'); body.replaceChildren();
        Object.entries(data.groups).forEach(([label, info]) => {
            group.add(new Option(label, label));
            const row = document.createElement('tr'), heading = document.createElement('th'), button = document.createElement('button'); heading.scope = 'row'; button.type = 'button'; button.dataset.analysisGroup = label; button.textContent = label; heading.append(button); row.append(heading);
            ['total', ...ratings].forEach(rating => { const cell = document.createElement('td'); cell.textContent = info.counts[rating]; row.append(cell); }); body.append(row);
        });
        if (!Object.keys(data.groups).length) { const row = document.createElement('tr'), cell = document.createElement('td'); cell.colSpan = 6; cell.textContent = 'No risk groups to display yet.'; row.append(cell); body.append(row); }
        group.value = Object.prototype.hasOwnProperty.call(data.groups, previous) ? previous : '';
    }
    const form = get('analysis-date-form'), from = get('analysis-from'), to = get('analysis-to'), recordScope = get('analysis-record-scope'), live = get('analysis-live');
    let request = null, revision = 0, timer = null;
    function describeScope() {
        const label = {all: 'All recorded risks, including closed records.', current: 'Current register records; closed risks excluded.', closed: 'Closed risks only.'}[filters.scope];
        get('analysis-applied-scope').textContent = `${label} ${filters.from || filters.to ? 'Created ' + (filters.from || 'at any time') + (filters.to ? ' through ' + filters.to : ' onwards') + '.' : 'All creation dates.'}`;
        get('analysis-undated-note').textContent = data.excluded_undated ? `${data.excluded_undated} record(s) without a valid creation date were excluded by the date filter.` : '';
    }
    function showUpdated() {
        const stamp = new Date(generatedAt);
        get('analysis-data-status').textContent = `Updated ${stamp.toLocaleString()}${live.value !== '0' ? ' · auto-refresh enabled' : ' · manual refresh'}`;
    }
    function setForm() { from.value = filters.from; to.value = filters.to; recordScope.value = filters.scope; get('analysis-period').value = filters.from || filters.to ? 'custom' : 'all'; }
    async function refresh(useForm) {
        const next = useForm ? {from: from.value, to: to.value, scope: recordScope.value} : {...filters};
        if (next.from && next.to && next.from > next.to) { get('analysis-data-status').textContent = 'Choose an end date on or after the start date.'; return; }
        const currentRevision = ++revision; if (request) request.abort(); request = new AbortController(); const controller = request;
        const url = new URL(root.dataset.url, location.href); Object.entries(next).forEach(([key, value]) => { if (value) url.searchParams.set(key, value); });
        get('analysis-refresh').disabled = true; root.setAttribute('aria-busy', 'true'); get('analysis-data-status').textContent = 'Refreshing from the database…';
        const timeout = setTimeout(() => controller.abort(), 30000);
        try {
            const response = await fetch(url.toString(), {credentials: 'same-origin', signal: controller.signal, headers: {Accept: 'application/json'}});
            if (!response.ok) throw new Error('Refresh failed');
            const result = await response.json(); if (currentRevision !== revision) return;
            data = result.analysisData; filters = result.filters; generatedAt = result.generatedAt;
            syncGroupChoices(); setForm(); describeScope(); render(); showUpdated();
            history.replaceState(null, '', url.pathname + url.search);
        } catch (error) {
            if (currentRevision === revision) { setForm(); get('analysis-data-status').textContent = `Refresh failed. Showing the last successful snapshot from ${new Date(generatedAt).toLocaleString()}. Try again.`; }
        } finally { clearTimeout(timeout); if (currentRevision === revision) { request = null; get('analysis-refresh').disabled = false; root.setAttribute('aria-busy', 'false'); } }
    }
    form.addEventListener('submit', event => { event.preventDefault(); refresh(true); });
    get('analysis-refresh').addEventListener('click', () => refresh(false));
    get('analysis-period').addEventListener('change', event => {
        const period = event.target.value, today = generatedAt.slice(0, 10), date = new Date(today + 'T00:00:00Z');
        if (period === 'all') { from.value = ''; to.value = ''; }
        else if (period !== 'custom') { to.value = today; if (period === 'year') from.value = today.slice(0, 4) + '-01-01'; else { date.setUTCDate(date.getUTCDate() - Number(period) + 1); from.value = date.toISOString().slice(0, 10); } }
    });
    [from, to].forEach(input => input.addEventListener('change', () => { get('analysis-period').value = 'custom'; }));
    live.addEventListener('change', () => { clearInterval(timer); if (live.value !== '0') timer = setInterval(() => { if (!document.hidden && !request) refresh(false); }, Number(live.value) * 1000); showUpdated(); });
    document.addEventListener('visibilitychange', () => { if (!document.hidden && live.value !== '0' && !request) refresh(false); });
    window.addEventListener('pagehide', () => { clearInterval(timer); if (request) request.abort(); });
    get('analysis-trend-unit').addEventListener('change', renderTrend);
    setForm(); describeScope(); render(); showUpdated();
})();
