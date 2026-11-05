(function () {
    'use strict';
    const root = document.getElementById('risk-analysis'); if (!root) return;
    const data = JSON.parse(root.dataset.analysis);
    const get = id => document.getElementById(id);
    const ratings = ['High', 'Moderate', 'Low', 'Unassessed'], colors = {High: '#eb6476', Moderate: '#e8b453', Low: '#48a991', Unassessed: '#a2acc0'};
    const group = get('analysis-group'), search = get('analysis-department-search'), sort = get('analysis-department-sort');
    function current() { return group.value ? data.groups[group.value] : {counts: data.totals, departments: data.departments, stages: data.stages, responses: data.responses, quality: data.quality}; }
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
        renderDepartments();
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
    render();
})();
