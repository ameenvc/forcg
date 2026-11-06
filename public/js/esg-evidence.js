(function () {
    'use strict';
    const form = document.getElementById('esg-source-form');
    if (!form) return;
    const kind = form.elements.kind;
    function update() {
        form.querySelectorAll('[data-esg-kind]').forEach(section => {
            section.hidden = section.dataset.esgKind !== kind.value;
            section.disabled = section.hidden;
            section.querySelectorAll('input,select').forEach(field => { field.required = !section.hidden; });
        });
        if (kind.value === 'emission') form.elements.pillar.value = 'E';
        const quantity = form.elements.quantity.value, factor = form.elements.factor.value;
        const estimate = quantity !== '' && factor !== '' ? Number(quantity) * Number(factor) / 1000 : NaN;
        document.getElementById('esg-emission-estimate').textContent = 'Calculated estimate: ' +
            (Number.isFinite(estimate) && estimate >= 0 ? new Intl.NumberFormat(undefined, {maximumFractionDigits: 6}).format(estimate) : '—') + ' tCO₂e';
    }
    kind.addEventListener('change', update);
    form.addEventListener('input', update);
    update();
}());
