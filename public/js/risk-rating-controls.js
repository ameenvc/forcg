/* Enhance existing assessment fields without changing their names, values or handlers. */
(function () {
    'use strict';
    function enhance(scope) {
        scope.querySelectorAll('input.impactbtn, input.likelihoodbtn').forEach(function (button) {
            if (button.closest('.rf-rating-options')) return;
            var kind = button.classList.contains('impactbtn') ? 'impact' : 'likelihood';
            var parent = button.parentElement;
            var buttons = Array.from(parent.querySelectorAll('input.' + (kind === 'impact' ? 'impactbtn' : 'likelihoodbtn')));
            if (!buttons.length) return;
            var field = parent.querySelector('input[type="hidden"]');
            if (!field) return;
            var group = document.createElement('div');
            group.className = 'rf-rating-options';
            group.setAttribute('role', 'radiogroup');
            group.setAttribute('aria-label', kind === 'impact' ? 'Impact' : 'Likelihood');
            buttons[0].before(group);
            buttons.forEach(function (choice) { group.appendChild(choice); choice.setAttribute('role', 'radio'); });
            function sync() {
                var selected = buttons.find(function (choice) { return choice.value === field.value; });
                buttons.forEach(function (choice) {
                    var checked = choice === selected;
                    choice.setAttribute('aria-checked', String(checked));
                    choice.setAttribute('aria-pressed', String(checked));
                    choice.tabIndex = choice === (selected || buttons[0]) ? 0 : -1;
                });
                var assessmentForm = field.closest('form');
                if (assessmentForm) assessmentForm.dispatchEvent(new CustomEvent('risk-assessment-change'));
            }
            group.addEventListener('click', function () { sync(); });
            group.addEventListener('keydown', function (event) {
                if (!['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'].includes(event.key)) return;
                event.preventDefault();
                var index = buttons.indexOf(document.activeElement);
                if (index < 0) index = 0;
                if (event.key === 'Home') index = 0;
                else if (event.key === 'End') index = buttons.length - 1;
                else index = (index + (['ArrowLeft', 'ArrowUp'].includes(event.key) ? -1 : 1) + buttons.length) % buttons.length;
                buttons[index].click(); buttons[index].focus(); sync();
            });
            // Legacy jQuery handlers and AI/reset flows also update styles or values.
            var observer = new MutationObserver(sync);
            buttons.forEach(function (choice) { observer.observe(choice, {attributes: true, attributeFilter: ['class', 'style']}); });
            observer.observe(field, {attributes: true, attributeFilter: ['value']});
            field.addEventListener('change', sync);
            field.addEventListener('input', sync);
            var form = field.closest('form');
            if (form) form.addEventListener('reset', function () { setTimeout(sync, 0); });
            sync();
        });
    }
    function enhancePreviews(scope) {
        scope.querySelectorAll('form').forEach(function (form) {
            if (form.dataset.assessmentPreviewReady) return;
            var impact = form.querySelector('[name="impa"]'), likelihood = form.querySelector('[name="like"]');
            if (!impact || !likelihood) return;
            // Each pair represents actual risk assessment fields, not percentage reductions.
            var card = form.querySelector('#risk-score-card');
            if (!card) {
                card = document.createElement('div');
                card.className = 'risk-score-card rf-generated-preview';
                card.innerHTML = '<span class="risk-score-icon" aria-hidden="true"><i class="fa fa-th"></i></span><div><span class="risk-score-eyebrow">ASSESSMENT PREVIEW</span><strong data-score-value></strong><p data-score-description></p></div>';
                var container = likelihood.closest('.row');
                if (!container || !form.contains(container)) container = form;
                var footer = container.querySelector('.risk-form-actions');
                if (footer && footer.parentElement === container) footer.before(card);
                else container.appendChild(card);
            }
            card.classList.add('rf-assessment-preview');
            card.setAttribute('role', 'status'); card.setAttribute('aria-live', 'polite'); card.setAttribute('aria-atomic', 'true');
            var result = card.querySelector('#risk-score-value, [data-score-value]');
            var description = card.querySelector('#risk-score-description, [data-score-description]');
            var values = {Low: 1, Moderate: 2, High: 3};
            function update() {
                var a = values[impact.value], b = values[likelihood.value], score = typeof a === 'number' && typeof b === 'number' ? a * b : null;
                var rating = score === null ? 'unassessed' : (score <= 2 ? 'Low' : score <= 4 ? 'Moderate' : 'High');
                var title = score === null ? 'Not assessed yet' : rating + ' risk · ' + score + ' / 9';
                var note = score === null ? 'Choose impact and likelihood to see your 3 × 3 matrix result.' : 'Impact ' + a + ' × Likelihood ' + b + ' = ' + score + '. Confirm these selections against your approved criteria.';
                card.dataset.rating = rating;
                if (result.textContent !== title) result.textContent = title;
                if (description.textContent !== note) description.textContent = note;
            }
            form.dataset.assessmentPreviewReady = 'true';
            form.addEventListener('risk-assessment-change', update);
            [impact, likelihood].forEach(function (field) {
                field.addEventListener('input', update); field.addEventListener('change', update);
                new MutationObserver(update).observe(field, {attributes: true, attributeFilter: ['value']});
                if (window.jQuery) window.jQuery(field).on('change.riskAssessmentPreview', update);
            });
            form.addEventListener('reset', function () { setTimeout(update, 0); });
            update();
        });
    }
    function start() {
        enhance(document);
        enhancePreviews(document);
        if (window.jQuery) window.jQuery(document).off('shown.bs.modal.riskRatingControls').on('shown.bs.modal.riskRatingControls', function (event) { enhance(event.target); enhancePreviews(event.target); });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
    else start();
}());
