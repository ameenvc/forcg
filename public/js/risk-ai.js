(function () {
    'use strict';
    const panel = document.getElementById('risk-ai-panel');
    if (!panel) return;
    const get = id => document.getElementById(id);
    const toggle = get('risk-ai-toggle'), close = get('risk-ai-close');
    const concern = get('risk-ai-concern');
    let originalOverflow = '';
    function openPanel(open) {
        if (open && panel.hidden) { originalOverflow = document.body.style.overflow; document.body.style.overflow = 'hidden'; }
        if (!open && !panel.hidden) document.body.style.overflow = originalOverflow;
        panel.hidden = !open;
        document.querySelectorAll('#risk-ai-toggle, [data-risk-ai-open]').forEach(button => button.setAttribute('aria-expanded', String(open)));
        if (open && concern) {
            if (!concern.value) concern.value = ((get('riskdec') && get('riskdec').value) || (get('riskname') && get('riskname').value) || '').slice(0, 10000);
            const focusTarget = concern.getClientRects().length ? concern : get('risk-ai-draft-name');
            if (focusTarget) focusTarget.focus();
        } else if (open && close) close.focus();
        else if (!open && toggle) toggle.focus();
    }
    document.querySelectorAll('#risk-ai-toggle, [data-risk-ai-open]').forEach(button => button.addEventListener('click', () => openPanel(panel.hidden)));
    if (close) close.addEventListener('click', () => openPanel(false));
    panel.addEventListener('click', event => { if (event.target === panel) openPanel(false); });
    panel.addEventListener('keydown', event => {
        if (event.key === 'Escape') { event.preventDefault(); openPanel(false); }
        if (event.key === 'Tab') {
            const focusable = [...panel.querySelectorAll('button, input, textarea, summary')].filter(el => !el.disabled && el.getClientRects().length);
            const first = focusable[0], last = focusable[focusable.length - 1];
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
        }
    });
    const find = get('risk-ai-generate');
    if (!find) return;
    const message = get('risk-ai-message'), ideas = get('risk-ai-ideas');
    const editor = get('risk-ai-editor'), apply = get('risk-ai-apply-all'), support = get('risk-ai-results');
    const topicArea = get('risk-ai-topic-area');
    const topicButtons = [...panel.querySelectorAll('[data-risk-topic]')];
    let busy = false, hasDraft = false;
    const value = selector => { const field = document.querySelector(selector); return field ? field.value : ''; };
    function status(text, error = false) {
        message.textContent = text;
        message.classList.toggle('text-danger', error);
    }
    function resetDraft() { editor.hidden = true; apply.hidden = true; hasDraft = false; topicArea.hidden = false; ideas.hidden = false; }
    get('risk-ai-back').addEventListener('click', () => { resetDraft(); status('Choose another risk, or refine your topic.'); (ideas.querySelector('button[aria-pressed="true"]') || concern).focus(); });
    function setBusy(active) {
        busy = active; find.disabled = active; concern.disabled = active;
        topicButtons.forEach(button => { button.disabled = active; });
        ideas.querySelectorAll('button').forEach(button => { button.disabled = active; });
        panel.setAttribute('aria-busy', String(active));
    }
    async function request(action, selected) {
        const department = document.querySelector('[name="riskarea"]');
        const ratings = {Low: 1, Moderate: 2, High: 3};
        const impact = ratings[value('#impact')], likelihood = ratings[value('#likelihood')];
        const response = await fetch(panel.dataset.url, {
            method: 'POST', credentials: 'same-origin',
            headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': value('[name="_token"]')},
            body: JSON.stringify({action, concern: concern.value.trim(), ...(selected ? {selected_risk_name: selected} : {}), risk_group: value('#riskgroup'), risk_area: department && department.value ? department.options[department.selectedIndex].text : '', risk_source: value('#riskscr'), existing_controls: value('#existing_controls'), ...(impact && likelihood ? {impact, likelihood} : {})})
        });
        if (!response.headers.get('content-type')?.includes('application/json')) throw new Error('Your session may have expired. Refresh the page and try again.');
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Unable to prepare suggestions. Please try again.');
        return data;
    }
    async function chooseRisk(name) {
        if (busy) return;
        resetDraft(); setBusy(true); status('Preparing a draft for your selected risk…');
        try {
            const data = await request('draft', name), draft = data.draft;
            get('risk-ai-draft-name').value = draft.risk_name;
            get('risk-ai-draft-description').value = draft.risk_description;
            get('risk-ai-draft-drivers').value = draft.risk_drivers.join('\n');
            support.replaceChildren();
            [['Suggested actions', draft.proposed_controls], ['Questions to refine this risk', draft.questions]].forEach(([title, items]) => {
                const heading = document.createElement('strong'); heading.textContent = title;
                const list = document.createElement('ul');
                items.forEach(item => { const li = document.createElement('li'); li.textContent = item; list.append(li); });
                support.append(heading, list);
            });
            ideas.querySelectorAll('button').forEach(button => button.setAttribute('aria-pressed', String(button.dataset.riskName === name)));
            hasDraft = true; topicArea.hidden = true; ideas.hidden = true; editor.hidden = false; apply.hidden = false; status(data.notice);
            get('risk-ai-draft-name').focus();
        } catch (error) { status(error.message, true); }
        finally { setBusy(false); }
    }
    async function findIdeas() {
        if (busy) return;
        if (concern.value.trim().length < 3) { status('Enter a topic or a short description, such as geopolitical or supplier dependency.', true); concern.focus(); return; }
        resetDraft(); ideas.replaceChildren(); setBusy(true); status('Finding risk ideas for your topic…');
        try {
            const data = await request('suggest');
            status(data.notice);
            if (data.suggestions.length) {
                const heading = document.createElement('h3'); heading.textContent = 'Which risk best matches your situation?'; ideas.append(heading);
            }
            data.suggestions.forEach(item => {
                const button = document.createElement('button'); button.type = 'button'; button.className = 'risk-ai-choice'; button.dataset.riskName = item.risk_name; button.setAttribute('aria-pressed', 'false');
                const title = document.createElement('strong'); title.textContent = item.risk_name;
                const reason = document.createElement('span'); reason.textContent = item.reason;
                button.append(title, reason); button.addEventListener('click', () => chooseRisk(item.risk_name)); ideas.append(button);
            });
        } catch (error) { status(error.message, true); }
        finally { setBusy(false); }
    }
    find.addEventListener('click', findIdeas);
    concern.addEventListener('input', () => { resetDraft(); ideas.replaceChildren(); status(''); });
    topicButtons.forEach(button => button.addEventListener('click', () => { concern.value = button.dataset.riskTopic; findIdeas(); }));
    apply.addEventListener('click', () => {
        if (!hasDraft) return;
        const draft = {riskname: get('risk-ai-draft-name').value.trim(), riskdec: get('risk-ai-draft-description').value.trim(), riskdri: get('risk-ai-draft-drivers').value.trim()};
        if (!draft.riskname || !draft.riskdec) { status('Add a risk name and description before using this draft.', true); return; }
        if (Object.keys(draft).some(id => get(id).value.trim() && get(id).value.trim() !== draft[id]) && !window.confirm('Replace the current risk name, description and drivers with this draft? Your other fields will stay as entered.')) return;
        Object.entries(draft).forEach(([id, text]) => {
            const field = get(id); field.value = text;
            field.dispatchEvent(new Event('input', {bubbles: true})); field.dispatchEvent(new Event('change', {bubbles: true}));
            field.classList.remove('risk-ai-applied'); void field.offsetWidth; field.classList.add('risk-ai-applied');
        });
        openPanel(false); get('riskname').focus();
    });
    const form = get('risk-entry-form');
    if (form) form.addEventListener('reset', () => { resetDraft(); ideas.replaceChildren(); status(''); });
}());
