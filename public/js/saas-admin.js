/* Interaction helpers for the CGBOD OneUI SaaS screens. */
(function () {
    'use strict';
    document.addEventListener('DOMContentLoaded', function () {
        var feedback = document.getElementById('saas-feedback');
        var timer;
        function announce(message) {
            if (!feedback) return;
            clearTimeout(timer);
            feedback.textContent = message;
            feedback.hidden = false;
            timer = setTimeout(function () { feedback.hidden = true; }, 3500);
        }
        document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                var input = document.getElementById(button.dataset.passwordToggle);
                if (!input) return;
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(show));
                button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                button.querySelector('i').className = show ? 'fa fa-eye-slash' : 'fa fa-eye';
            });
        });
        var slug = document.getElementById('tenant-slug');
        var preview = document.querySelector('[data-slug-preview]');
        if (slug && preview) {
            slug.addEventListener('input', function () { preview.textContent = slug.value || 'your-organization'; });
        }
        document.querySelectorAll('[data-copy]').forEach(function (button) {
            button.addEventListener('click', function () {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(button.dataset.copy).then(function () { announce('Provisioning command copied.'); }, function () { announce('Copy the command shown in the dialog.'); });
                } else {
                    announce('Select and copy the provisioning command shown in the dialog.');
                }
            });
        });
        var sectionLinks = document.querySelectorAll('[data-section-link]');
        function highlightSection(hash) {
            sectionLinks.forEach(function (link) {
                var current = document.getElementById('overview') && new URL(link.href).hash === hash;
                link.classList.toggle('active', current);
                if (current) link.setAttribute('aria-current', 'location');
                else link.removeAttribute('aria-current');
            });
        }
        highlightSection(window.location.hash || '#overview');
        window.addEventListener('hashchange', function () { highlightSection(window.location.hash || '#overview'); });
        sectionLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                highlightSection(new URL(link.href).hash);
                if (window.innerWidth < 992 && window.One) window.One.layout('sidebar_close');
            });
        });
        document.addEventListener('keydown', function (event) {
            if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
                var search = document.getElementById('tenant-search');
                if (search) { event.preventDefault(); search.focus(); search.scrollIntoView({ block: 'center' }); }
            }
        });
        if (window.jQuery) {
            window.jQuery('[data-open-on-error="true"]').modal('show');
            if (window.location.hash === '#register') window.jQuery('#createTenantModal').modal('show');
            if (window.location.hash === '#setup') window.jQuery('#setupGuideModal').modal('show');
            window.jQuery('.modal').on('shown.bs.modal', function () {
                var first = this.querySelector('input:not([type="hidden"]), select');
                if (first) first.focus();
            });
        }
    });
}());
