(function () {
    'use strict';
    document.addEventListener('click', async event => {
        const button = event.target.closest('[data-risk-review]');
        if (!button || button.disabled) return;
        const panel = button.closest('.risk-review-assist');
        const status = panel.querySelector('.risk-review-status'), results = panel.querySelector('.risk-review-results');
        button.disabled = true; status.textContent = 'Reviewing this risk…'; results.hidden = true;
        const controller = new AbortController(), timer = setTimeout(() => controller.abort(), 55000);
        try {
            const response = await fetch(panel.dataset.reviewUrl, {
                method: 'POST', credentials: 'same-origin', signal: controller.signal,
                headers: {'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''},
                body: JSON.stringify({...JSON.parse(panel.dataset.reviewContext), action: 'review'})
            });
            if (!response.ok) throw new Error('Request failed');
            const data = await response.json();
            results.replaceChildren();
            const summary = document.createElement('p'); summary.textContent = data.review.summary; results.append(summary);
            [['gaps', 'Information to check'], ['proposed_controls', 'Controls to consider'], ['questions', 'Reviewer questions']].forEach(([key, title]) => {
                const section = document.createElement('section'), heading = document.createElement('h4'), list = document.createElement('ul');
                heading.textContent = title; section.append(heading);
                const items = data.review[key];
                (items.length ? items : ['No specific gaps identified; verify the supporting evidence.']).forEach(text => { const item = document.createElement('li'); item.textContent = text; list.append(item); });
                section.append(list); results.append(section);
            });
            status.textContent = data.notice; results.hidden = false;
        } catch (error) {
            status.textContent = 'Review could not be completed. Please try again. If this continues, ask your administrator to check AI configuration.';
        } finally { clearTimeout(timer); button.disabled = false; }
    });
})();
