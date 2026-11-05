(function () {
    'use strict';
    const panel = document.getElementById('risk-ai-panel');
    if (!panel) return;
    const toggle = document.getElementById('risk-ai-toggle');
    const close = document.getElementById('risk-ai-close');
    function setOpen(open) {
        panel.hidden = !open;
        if (toggle) toggle.setAttribute('aria-expanded', String(open));
        const concern = document.getElementById('risk-ai-concern');
        if (open && concern) {
            if (!concern.value) {
                const description = document.getElementById('riskdec');
                const name = document.getElementById('riskname');
                concern.value = ((description && description.value) || (name && name.value) || '').slice(0, 10000);
            }
            concern.focus();
        } else if (!open && toggle) toggle.focus();
    }
    if (toggle) toggle.addEventListener('click', () => setOpen(panel.hidden));
    if (close) close.addEventListener('click', () => setOpen(false));
    const button = document.getElementById('risk-ai-generate');
    if (!button) return;
    const message = document.getElementById('risk-ai-message');
    const results = document.getElementById('risk-ai-results');
    const applyAll = document.getElementById('risk-ai-apply-all');
    const fields = {risk_name: '#riskname', risk_description: '#riskdec', risk_drivers: '#riskdri'};
    let latestDraft = null;
    const value = selector => { const field = document.querySelector(selector); return field ? field.value : ''; };
    function applyField(key, content) {
        const target = document.querySelector(fields[key]);
        if (!target) return;
        target.value = Array.isArray(content) ? content.join('\n') : content;
        target.classList.remove('risk-ai-applied');
        void target.offsetWidth;
        target.classList.add('risk-ai-applied');
        target.dispatchEvent(new Event('input', {bubbles: true}));
        target.dispatchEvent(new Event('change', {bubbles: true}));
    }
    if (applyAll) applyAll.addEventListener('click', () => {
        if (!latestDraft) return;
        const keys = Object.keys(fields).filter(key => latestDraft[key] !== undefined);
        if (keys.some(key => value(fields[key]).trim()) && !window.confirm('Replace the risk name, description and drivers with these suggestions? Existing controls and ratings will stay as entered.')) return;
        keys.forEach(key => applyField(key, latestDraft[key]));
        message.textContent = 'Suggested fields applied. Review the form before submitting.';
    });
    button.addEventListener('click', async function () {
        results.replaceChildren();
        latestDraft = null;
        if (applyAll) applyAll.hidden = true;
        const concern = value('#risk-ai-concern').trim();
        if (concern.length < 10) { message.textContent = 'Please describe the concern in at least 10 characters.'; return; }
        button.disabled = true;
        message.textContent = 'Preparing draft…';
        try {
            const department = document.querySelector('[name="riskarea"]');
            const ratings = {Low: 1, Moderate: 2, High: 3};
            const impact = ratings[value('#impact')], likelihood = ratings[value('#likelihood')];
            const response = await fetch(panel.dataset.url, {
                method: 'POST', credentials: 'same-origin',
                headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': value('[name="_token"]')},
                body: JSON.stringify({concern, risk_group: value('#riskgroup'), risk_area: department && department.value ? department.options[department.selectedIndex].text : '', risk_source: value('#riskscr'), existing_controls: value('#existing_controls'), ...(impact && likelihood ? {impact, likelihood} : {})})
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Unable to prepare draft.');
            latestDraft = data.draft;
            if (applyAll) applyAll.hidden = false;
            message.textContent = data.notice;
            if (data.assessment) message.textContent += ` Selected assessment: ${data.assessment.score} · ${data.assessment.rating} (your inputs).`;
            Object.entries(data.draft).forEach(([key, content]) => {
                const block = document.createElement('div');
                block.className = 'risk-ai-result';
                const heading = document.createElement('strong'); heading.textContent = key.replace(/_/g, ' ');
                const text = document.createElement('p');
                text.style.whiteSpace = 'pre-wrap';
                text.textContent = Array.isArray(content) ? content.join('\n') : content;
                block.append(heading, text);
                if (fields[key]) {
                    const apply = document.createElement('button');
                    apply.type = 'button'; apply.className = 'btn btn-sm btn-outline-primary mb-3'; apply.textContent = 'Apply this field';
                    apply.addEventListener('click', function () {
                        const target = document.querySelector(fields[key]);
                        if (!target || (target.value && !window.confirm('Replace the current field text with this suggestion?'))) return;
                        applyField(key, content);
                        apply.textContent = 'Applied';
                    });
                    block.append(apply);
                }
                results.append(block);
            });
        } catch (error) { message.textContent = error.message; }
        finally { button.disabled = false; }
    });
}());
