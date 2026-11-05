/* Keep Bootstrap menus outside the scrolling table; preserve existing action handlers. */
(function ($) {
    'use strict';
    if (!$) return;
    var active = null;
    function closeMenu() {
        if (active) $(active.toggle).dropdown('hide');
    }
    $(document).on('shown.bs.dropdown', '.risk-workflow-page #tabledata .dropdown', function () {
        closeMenu();
        var toggle = this.querySelector('[data-toggle="dropdown"]');
        var menu = this.querySelector('.dropdown-menu');
        if (!toggle || !menu) return;
        var marker = document.createComment('workflow-menu');
        menu.before(marker);
        active = {toggle: toggle, menu: menu, marker: marker};
        document.body.appendChild(menu);
        menu.classList.add('workflow-floating-menu');
        var rect = toggle.getBoundingClientRect();
        var width = Math.min(240, window.innerWidth - 24);
        menu.style.setProperty('position', 'fixed', 'important');
        menu.style.setProperty('transform', 'none', 'important');
        menu.style.setProperty('width', width + 'px');
        menu.style.setProperty('max-height', (window.innerHeight - 24) + 'px');
        menu.style.setProperty('overflow-y', 'auto');
        menu.style.setProperty('left', Math.max(12, Math.min(rect.right - width, window.innerWidth - width - 12)) + 'px', 'important');
        var height = menu.getBoundingClientRect().height;
        var top = rect.bottom + 6;
        if (top + height > window.innerHeight - 12) top = Math.max(12, rect.top - height - 6);
        menu.style.setProperty('top', top + 'px', 'important');
        menu.style.setProperty('bottom', 'auto', 'important');
        menu.style.setProperty('right', 'auto', 'important');
    });
    $(document).on('hidden.bs.dropdown', '.risk-workflow-page #tabledata .dropdown', function () {
        if (!active || !this.contains(active.toggle)) return;
        active.marker.replaceWith(active.menu);
        active.menu.classList.remove('workflow-floating-menu');
        active.menu.removeAttribute('style');
        active = null;
    });
    // Portaled items still bubble to the original delegated body action handlers.
    document.addEventListener('keydown', function (event) {
        var menu = event.target.closest('.workflow-floating-menu');
        if (!menu) return;
        if (!['ArrowDown', 'ArrowUp', 'Escape'].includes(event.key)) return;
        event.preventDefault(); event.stopImmediatePropagation();
        if (event.key === 'Escape') {
            var toggle = active && active.toggle;
            closeMenu(); if (toggle) toggle.focus(); return;
        }
        var items = Array.from(menu.querySelectorAll('.dropdown-item:not(.disabled):not([disabled])'));
        if (!items.length) return;
        var index = items.indexOf(document.activeElement);
        index = event.key === 'ArrowDown' ? (index + 1) % items.length : (index <= 0 ? items.length - 1 : index - 1);
        items[index].focus();
    }, true);
    window.addEventListener('resize', closeMenu);
    document.addEventListener('scroll', function (event) {
        if (active && !active.menu.contains(event.target)) closeMenu();
    }, true);
})(window.jQuery);
