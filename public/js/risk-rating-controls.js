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
    function start() {
        enhance(document);
        if (window.jQuery) window.jQuery(document).off('shown.bs.modal.riskRatingControls').on('shown.bs.modal.riskRatingControls', function (event) { enhance(event.target); });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
    else start();
}());
