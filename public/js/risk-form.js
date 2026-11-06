(function () {
    'use strict';
    const form = document.getElementById('risk-entry-form');
    if (!form) return;
    function refreshAssessment() {
        const impact = document.getElementById('impact').value;
        const likelihood = document.getElementById('likelihood').value;
        [['.impactbtn', impact], ['.likelihoodbtn', likelihood]].forEach(([selector, selected]) => {
            form.querySelectorAll(selector).forEach(button => {
                const pressed = button.value === selected;
                button.setAttribute('aria-pressed', String(pressed));
                if (!pressed) {
                    button.style.backgroundColor = '';
                    button.style.border = '';
                    button.classList.remove('btn-alt-danger', 'btn-alt-warning', 'btn-alt-success');
                }
            });
        });
        form.dispatchEvent(new CustomEvent('risk-assessment-change'));
    }
    form.addEventListener('click', event => {
        if (event.target.matches('.impactbtn, .likelihoodbtn')) {
            // Keep the original hidden field names and existing submission handler.
            document.getElementById(event.target.matches('.impactbtn') ? 'impact' : 'likelihood').value = event.target.value;
            refreshAssessment();
        }
    });
    form.addEventListener('reset', () => setTimeout(() => {
        // Hidden inputs keep their assigned value through a native reset.
        document.getElementById('impact').value = '';
        document.getElementById('likelihood').value = '';
        document.getElementById('impact-error').style.display = 'none';
        document.getElementById('likelihood-error').style.display = 'none';
        refreshAssessment();
    }, 0));
    refreshAssessment();
}());
