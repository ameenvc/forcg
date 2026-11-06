/* Board interactions never mark source work complete; all actions open the owning module. */
(function () {
    'use strict';
    const root = document.getElementById('my-board'); if (!root) return;
    const get = id => document.getElementById(id);
    let board = JSON.parse(get('my-board-data').textContent);
    let filter = 'all', kind = 'all', page = 1, updateCount = 6, searching, refreshing = false;
    const pageSize = 10;
    function el(tag, className, text) {
        const node = document.createElement(tag); if (className) node.className = className;
        if (text !== undefined) node.textContent = String(text); return node;
    }
    function icon(name) { const node = el('i', 'fa ' + name); node.setAttribute('aria-hidden', 'true'); return node; }
    function safeUrl(value) {
        try {
            const url = new URL(value, location.origin);
            // TLS may terminate at the deployment proxy while Laravel generates HTTP URLs.
            if (location.protocol === 'https:' && url.protocol === 'http:' && url.hostname === location.hostname && !url.port) url.protocol = 'https:';
            return url.origin === location.origin && /^https?:$/.test(url.protocol) ? url.href : null;
        }
        catch (error) { return null; }
    }
    function taskRow(task) {
        const row = el('li', 'mb-task'); row.dataset.taskKey = task.key;
        const glyph = el('span', 'mb-task-icon'); glyph.setAttribute('aria-hidden', 'true'); glyph.append(icon(task.icon));
        const copy = el('div', 'mb-task-copy');
        copy.append(el('span', 'mb-task-meta', task.module_name + ' · ' + task.reference), el('h3', '', task.title), el('p', '', task.instruction + (task.status ? ' · ' + task.status : '')));
        const due = el('div', 'mb-task-due'); due.append(el('span', 'mb-kind mb-kind-' + task.kind, {action: 'Action', review: 'Review', approval: 'Approval'}[task.kind]), el('span', task.overdue ? 'mb-overdue' : '', (task.overdue ? 'Overdue · ' : '') + task.due_label));
        if (task.priority) due.append(el('small', 'mb-priority-' + task.priority.toLowerCase(), task.priority + ' priority'));
        row.append(glyph, copy, due);
        const actions = el('div', 'mb-task-actions');
        const actionUrl = safeUrl(task.action_url || task.url);
        if (actionUrl) { const action = el('a', 'mb-task-action', task.action_label || 'Open task'); action.href = actionUrl; action.setAttribute('aria-label', (task.action_label || 'Open task') + ' · ' + task.reference); actions.append(action); }
        const url = task.url && safeUrl(task.url);
        if (url) { const link = el('a', 'mb-open'); link.href = url; link.title = task.direct ? 'View details' : 'View module'; link.setAttribute('aria-label', 'View ' + task.reference); link.append(icon('fa-eye')); actions.append(link); }
        copy.append(actions);
        return row;
    }
    function renderTasks() {
        const term = get('mb-search').value.trim().toLocaleLowerCase(), module = get('mb-module').value;
        const matches = board.tasks.filter(task => (!module || task.module === module) && (kind === 'all' || task.kind === kind)
            && (filter === 'all' || filter === 'decisions' && task.kind !== 'action' || filter === 'overdue' && task.overdue || filter === 'due_soon' && task.due_soon)
            && (!term || [task.title, task.reference, task.instruction, task.module_name, task.status].join(' ').toLocaleLowerCase().includes(term)));
        page = Math.max(1, Math.min(page, Math.ceil(matches.length / pageSize) || 1));
        get('mb-tasks').replaceChildren(...matches.slice((page - 1) * pageSize, page * pageSize).map(taskRow));
        get('mb-empty').hidden = matches.length > 0;
        get('mb-empty').querySelector('h3').textContent = board.tasks.length ? 'No matching work' : 'No connected actions pending';
        get('mb-empty').querySelector('p').textContent = board.tasks.length ? 'Try another search or clear your filters.' : 'New assignments, reviews, and approvals will appear here. Check module coverage below for work outside this feed.';
        get('mb-matches').textContent = (matches.length ? (page - 1) * pageSize + 1 : 0) + '–' + Math.min(page * pageSize, matches.length) + ' of ' + matches.length + ' items';
        get('mb-page').textContent = page; get('mb-prev').disabled = page === 1; get('mb-next').disabled = page * pageSize >= matches.length;
        get('mb-clear').hidden = filter === 'all' && kind === 'all' && !module && !term;
        root.querySelectorAll('[data-board-kind]').forEach(button => button.setAttribute('aria-pressed', String(button.dataset.boardKind === kind)));
        root.querySelectorAll('.mb-metric').forEach(button => { const active = button.dataset.boardFilter === filter; button.classList.toggle('is-active', active); button.setAttribute('aria-pressed', String(active)); });
    }
    function renderUpdates() {
        get('mb-update-list').replaceChildren(...board.updates.slice(0, updateCount).map(update => {
            const li = el('li'), dot = el('span', 'mb-update-dot'), body = el('div'); dot.setAttribute('aria-hidden', 'true');
            const url = update.url && safeUrl(update.url), heading = el(url ? 'a' : 'strong', '', update.reference + ' · ' + update.module_name);
            if (url) heading.href = url;
            body.append(heading, el('p', '', update.status), el('small', '', update.time_label)); li.append(dot, body); return li;
        }));
        get('mb-update-empty').hidden = board.updates.length > 0; get('mb-more-updates').hidden = board.updates.length <= updateCount;
        const alerts = [];
        [[board.counts.overdue, 'overdue', 'overdue items need attention', 'fa-clock'], [board.counts.review + board.counts.approval, 'decisions', 'reviews or approvals await you', 'fa-user-check']].forEach(([count, value, label, glyph]) => {
            if (!count) return; const button = el('button'); button.type = 'button'; button.dataset.boardFilter = value;
            button.append(icon(glyph), el('span', '', count + ' ' + label), icon('fa-arrow-right')); alerts.push(button);
        });
        get('mb-alerts').replaceChildren(...alerts);
    }
    function renderSummary() {
        root.querySelectorAll('[data-board-count]').forEach(node => { const key = node.dataset.boardCount; node.textContent = key === 'decisions' ? board.counts.review + board.counts.approval : board.counts[key]; });
        const score = board.score;
        get('mb-score-value').textContent = score.value === null ? '—' : score.value;
        get('mb-score-ring').style.setProperty('--mb-score', score.value || 0);
        get('mb-score-ring').setAttribute('aria-label', 'Contribution score ' + (score.value === null ? 'not yet available' : score.value) + ' out of 100');
        get('mb-score-caption').textContent = score.value === null ? 'Your score starts when connected work is recorded.' : (score.timeliness_percent === null ? 'Based on recorded completion; timing evidence is unavailable.' : 'Based on recorded delivery and timeliness.');
        get('mb-completed').textContent = score.completed; get('mb-on-time').textContent = score.on_time; get('mb-score-pending').textContent = score.pending;
    }
    function renderModules() {
        const selected = get('mb-module').value;
        get('mb-module').replaceChildren(new Option('All modules', ''), ...board.modules.map(module => new Option(module.name + ' (' + module.pending + ')', module.key)));
        get('mb-module').value = board.modules.some(module => module.key === selected) ? selected : '';
        const links = [];
        board.modules.forEach(module => {
            const url = module.url && safeUrl(module.url); if (!url) return;
            const link = el('a'); link.href = url; const glyph = el('span', 'mb-module-icon'); glyph.append(icon(module.icon));
            const copy = el('span', '', module.name); copy.append(el('small', '', module.area)); link.append(glyph, copy);
            if (module.pending) link.append(el('b', '', module.pending));
            const arrow = icon('fa-chevron-right'); arrow.classList.add('mb-module-arrow'); link.append(arrow); links.push(link);
        });
        get('mb-modules').replaceChildren(...links);
        get('mb-scope-note').hidden = !board.coverage.has_gaps;
        get('mb-coverage-summary').textContent = board.coverage.connected + ' of ' + board.coverage.total + ' modules have connected task feeds';
        get('mb-coverage-list').replaceChildren(...board.modules.map(module => {
            const row = el('li'); row.append(el('span', '', module.name), el('span', 'mb-coverage-' + module.state, {connected: 'Connected', partial: 'Some workflows connected', unavailable: 'Check module directly'}[module.state])); return row;
        }));
    }
    root.addEventListener('click', event => {
        const bucket = event.target.closest('[data-board-filter]'), type = event.target.closest('[data-board-kind]');
        if (bucket) { filter = bucket.dataset.boardFilter; kind = 'all'; page = 1; renderTasks(); }
        if (type) { kind = type.dataset.boardKind; filter = 'all'; page = 1; renderTasks(); }
    });
    get('mb-search').addEventListener('input', () => { cancelAnimationFrame(searching); searching = requestAnimationFrame(() => { page = 1; renderTasks(); }); });
    get('mb-module').addEventListener('change', () => { page = 1; renderTasks(); });
    get('mb-prev').addEventListener('click', () => { page--; renderTasks(); }); get('mb-next').addEventListener('click', () => { page++; renderTasks(); });
    get('mb-clear').addEventListener('click', () => { filter = kind = 'all'; page = 1; get('mb-search').value = ''; get('mb-module').value = ''; renderTasks(); });
    get('mb-more-updates').addEventListener('click', () => { updateCount += 6; renderUpdates(); });
    get('mb-refresh').addEventListener('click', async () => {
        if (refreshing) return; refreshing = true; const button = get('mb-refresh'); button.disabled = true; button.setAttribute('aria-busy', 'true');
        const abort = new AbortController(), timeout = setTimeout(() => abort.abort(), 15000);
        const feedback = get('mb-feedback'); feedback.hidden = false; feedback.textContent = 'Checking your latest work…';
        try {
            const response = await fetch(root.dataset.refreshUrl, {credentials: 'same-origin', cache: 'no-store', signal: abort.signal, headers: {Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest'}});
            if (!response.ok || response.redirected || !(response.headers.get('content-type') || '').includes('application/json')) throw new Error('Board unavailable');
            const updated = await response.json();
            if (!Array.isArray(updated.tasks) || !updated.counts || !updated.score || !Array.isArray(updated.modules) || !Array.isArray(updated.updates)) throw new Error('Unexpected response');
            board = updated; renderModules(); renderSummary(); renderTasks(); renderUpdates();
            get('mb-updated').textContent = 'Updated ' + new Intl.DateTimeFormat(undefined, {hour: 'numeric', minute: '2-digit', timeZone: board.timezone || 'UTC'}).format(new Date(board.updated_at));
            feedback.textContent = 'Your board is up to date.';
        } catch (error) { feedback.textContent = 'The board could not update. Your previous view is still shown. Try again, or reload if your session has expired.'; }
        finally { clearTimeout(timeout); refreshing = false; button.disabled = false; button.removeAttribute('aria-busy'); }
    });
    renderTasks();
})();
