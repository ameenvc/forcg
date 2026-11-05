(function () {
    'use strict';
    const element = (tag, text, className) => { const node = document.createElement(tag); if (text) node.textContent = text; if (className) node.className = className; return node; };
    window.CgbodRiskAiFeedback = {
        mount(target, data) {
            if (!target) return;
            if (data.review?.organization_comments?.length) {
                const comments = element('section', '', 'risk-ai-org-comments'); comments.append(element('h4', 'Organization perspective'));
                data.review.organization_comments.forEach(text => comments.append(element('p', text))); target.append(comments);
            }
            if (!data.organization_context && !data.feedback) return;
            const panel = element('section', '', 'risk-ai-feedback'), context = data.organization_context;
            if (context) {
                const text = context.used ? `Organization context: ${context.name} · approved profile v${context.profile_revision}` : context.name ? 'Organization profile saved. Live AI uses it for tailored guidance; this result is a demo.' : 'Organization context has not been enabled yet.';
                panel.append(element('p', text, 'risk-ai-context-label'));
                if (context.source_reference || context.notes?.length) {
                    const sources = element('details'), list = element('ul'); sources.append(element('summary', context.used ? 'Organization sources supplied to the AI' : 'Approved organization sources available'));
                    if (context.source_reference) list.append(element('li', `Profile: ${context.source_reference}`));
                    (context.notes || []).forEach(note => list.append(element('li', `${note.id} · ${note.title} · ${note.source_reference}${note.review_by ? ' · review by ' + note.review_by : ''}`)));
                    sources.append(list); panel.append(sources);
                }
                if (context.url) { const url = new URL(context.url, location.href); if (url.origin === location.origin) { const link = element('a', 'Organization AI settings'); link.href = url.toString(); panel.append(link); } }
            }
            if (data.feedback) {
                const details = element('details', '', 'risk-ai-feedback-editor'); details.append(element('summary', 'Give feedback on this answer'));
                const fields = element('fieldset'), legend = element('legend', 'How useful was this answer?'), ratingLabel = element('label', 'Your assessment'), rating = element('select');
                [['helpful', 'Helpful'], ['not_relevant', 'Not relevant to our organization'], ['incorrect', 'Incorrect / needs correction']].forEach(([value, text]) => rating.add(new Option(text, value)));
                ratingLabel.append(rating);
                const commentLabel = element('label', 'What should be improved?'), comment = element('textarea'); comment.maxLength = 2000; comment.rows = 3; comment.placeholder = 'Explain the correction or missing organization context. A manager will review it.'; commentLabel.append(comment);
                const button = element('button', 'Send feedback', 'btn btn-primary'); button.type = 'button';
                const message = element('p'); message.setAttribute('role', 'status'); message.setAttribute('aria-live', 'polite');
                fields.append(legend, ratingLabel, commentLabel, element('small', 'This answer is saved for feedback review. Shared knowledge changes only after approval.'), button, message); details.append(fields); panel.append(details);
                button.addEventListener('click', async () => {
                    if (rating.value !== 'helpful' && !comment.value.trim()) { message.textContent = 'Please describe what is incorrect or not relevant.'; comment.focus(); return; }
                    button.disabled = true; rating.disabled = true; comment.disabled = true; message.textContent = 'Saving feedback…';
                    const controller = new AbortController(), timer = setTimeout(() => controller.abort(), 20000);
                    try {
                        const response = await fetch(data.feedback.url, {method: 'POST', credentials: 'same-origin', signal: controller.signal, headers: {'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('[name="_token"]')?.value || ''}, body: JSON.stringify({run_id: data.feedback.run_id, rating: rating.value, comment: comment.value.trim()})});
                        const result = await response.json(); if (!response.ok) throw new Error('Feedback could not be saved.');
                        message.textContent = result.message; button.textContent = 'Feedback submitted';
                    } catch (error) { message.textContent = 'Feedback could not be saved. Please try again. It may already have been submitted.'; button.disabled = false; rating.disabled = false; comment.disabled = false; }
                    finally { clearTimeout(timer); }
                });
            }
            target.append(panel);
        }
    };
})();
