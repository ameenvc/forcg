(function () {
    'use strict';
    document.querySelectorAll('[data-organization-feedback-review]').forEach(form => {
        const decision = form.querySelector('[name="decision"]'), editor = form.querySelector('.org-editor');
        function refresh() { const approve = decision.value === 'approve'; if (approve) editor.open = true; ['title', 'content', 'source_reference'].forEach(name => { form.querySelector(`[name="${name}"]`).required = approve; }); }
        decision.addEventListener('change', refresh); refresh();
    });
    const profile = document.querySelector('[data-organization-profile]');
    if (profile) { const enabled = profile.querySelector('input[type="checkbox"][name="enabled"]'), source = profile.querySelector('[name="source_reference"]'); const refresh = () => { source.required = enabled.checked; }; enabled.addEventListener('change', refresh); refresh(); }
})();
