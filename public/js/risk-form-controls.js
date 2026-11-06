/* Progressive form accessibility; native/server validation and submit handlers stay authoritative. */
(function () {
    'use strict';
    var sequence = 0;
    function labelFor(field, form) {
        var label = Array.from(form.querySelectorAll('label')).find(function (item) { return item.htmlFor === field.id && field.id; });
        if (!label) label = field.closest('.form-group') && field.closest('.form-group').querySelector('label');
        return label ? label.textContent.replace(/\*/g, '').trim() : 'This field';
    }
    function clear(field) {
        if (!field.dataset.rfErrorId) return;
        var id = field.dataset.rfErrorId, error = document.getElementById(id);
        if (error) error.remove();
        var descriptions = (field.getAttribute('aria-describedby') || '').split(/\s+/).filter(function (value) { return value && value !== id; });
        if (descriptions.length) field.setAttribute('aria-describedby', descriptions.join(' '));
        else field.removeAttribute('aria-describedby');
        field.removeAttribute('aria-invalid');
        field.classList.remove('rf-invalid');
        delete field.dataset.rfErrorId;
    }
    function invalid(field, form) {
        if (!field.matches('input,select,textarea')) return;
        clear(field);
        var error = document.createElement('p');
        error.id = 'rf-field-error-' + (++sequence);
        error.className = 'rf-field-error';
        error.textContent = field.validationMessage || 'Check this field.';
        field.dataset.rfErrorId = error.id;
        field.setAttribute('aria-invalid', 'true');
        field.setAttribute('aria-describedby', ((field.getAttribute('aria-describedby') || '') + ' ' + error.id).trim());
        field.classList.add('rf-invalid');
        var select2 = field.nextElementSibling;
        (select2 && select2.classList.contains('select2') ? select2 : field).after(error);
        var summary = form.querySelector('.rf-validation-summary');
        if (!summary) {
            summary = document.createElement('div');
            summary.className = 'rf-validation-summary';
            summary.setAttribute('role', 'alert');
            form.prepend(summary);
        }
        summary.textContent = 'Please check the highlighted fields before continuing.';
    }
    function enhance(scope) {
        scope.querySelectorAll('form.risk-module-form').forEach(function (form) {
            if (form.dataset.rfControlsReady) return;
            form.dataset.rfControlsReady = 'true';
            form.querySelectorAll('input:not([type=hidden]),select,textarea').forEach(function (field) {
                if (!field.id) field.id = 'rf-control-' + (++sequence);
                if (!field.labels || !field.labels.length) {
                    var label = field.closest('.form-group') && field.closest('.form-group').querySelector('label:not([for])');
                    if (label && !field.matches('[type=radio],[type=checkbox],[type=button],[type=submit]')) label.htmlFor = field.id;
                }
                if (field.required) field.setAttribute('aria-required', 'true');
                if (!field.getAttribute('aria-label') && (!field.labels || !field.labels.length) && field.matches('select,textarea,input:not([type])')) field.setAttribute('aria-label', labelFor(field, form));
            });
            form.addEventListener('invalid', function (event) { invalid(event.target, form); }, true);
            function update(event) {
                var field = event.target;
                if (field.dataset && field.dataset.rfErrorId && field.validity.valid) clear(field);
                if (!form.querySelector('[data-rf-error-id]')) {
                    var summary = form.querySelector('.rf-validation-summary');
                    if (summary) summary.remove();
                }
            }
            form.addEventListener('input', update);
            form.addEventListener('change', update);
            form.addEventListener('reset', function () {
                setTimeout(function () {
                    form.querySelectorAll('[data-rf-error-id]').forEach(clear);
                    var summary = form.querySelector('.rf-validation-summary');
                    if (summary) summary.remove();
                }, 0);
            });
        });
    }
    function start() {
        enhance(document);
        if (window.jQuery) {
            window.jQuery(document).on('shown.bs.modal', function (event) { enhance(event.target); });
            window.jQuery(document).on('select2:open', '.risk-module-form select', function () {
                document.querySelectorAll('.select2-container--open .select2-dropdown').forEach(function (dropdown) { dropdown.classList.add('rf-select-dropdown'); });
            });
        }
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
    else start();
})();
