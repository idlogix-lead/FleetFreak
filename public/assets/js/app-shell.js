/*
 * App shell (top bar + sidebar): resources/views/layouts/header.blade.php and nav.blade.php.
 * Loaded with defer from the header, so the markup is parsed and jQuery (head) is available.
 * Sidebar collapse, hover-expand and the mobile menu stay in Synadmin's app.js
 * (.toggle-icon, .mobile-toggle-menu); nothing here touches them.
 */
(function ($) {
    'use strict';

    // Active company switch: same request, reload and error messages as before.
    $('#active_company_dropdown').on('change', function () {
        var companyId = $(this).find('option:selected').val();
        if (!companyId) {
            return;
        }
        $.post($(this).data('change-url'), {
            _token: $('meta[name="csrf-token"]').attr('content'),
            company_id: companyId
        }).then(function (resp) {
            if (resp && resp.success) {
                location.reload();
            }
        }).fail(function (xhr) {
            var errors = xhr.responseJSON && xhr.responseJSON.errors;
            if (errors) {
                for (var key in errors) {
                    msgboxbox.show(errors[key] || 'error', 'error', null);
                }
            }
        });
    });

    // Notifications offcanvas: Unread / All tabs (same ids as before).
    $('#showUnreadMessages, #showAllMessages').on('click', function () {
        var unread = this.id === 'showUnreadMessages';
        $('#unreadMessagesSection').toggleClass('d-none', !unread);
        $('#allMessagesSection').toggleClass('d-none', unread);
        $('#showUnreadMessages').toggleClass('is-active', unread).attr('aria-selected', unread);
        $('#showAllMessages').toggleClass('is-active', !unread).attr('aria-selected', !unread);
    });

    // Refresh (dashboards only).
    $('[data-ffs-action="refresh"]').on('click', function () {
        window.location.reload();
    });

    // Search is a SAMPLE until Phase 7: Cmd/Ctrl+K only focuses it.
    var search = document.getElementById('ffs-search');
    var kbd = document.getElementById('ffs-search-kbd');
    var isMac = /Mac|iPhone|iPad/.test(navigator.platform || navigator.userAgent);
    if (kbd && !isMac) {
        kbd.textContent = 'Ctrl K';
    }
    document.addEventListener('keydown', function (e) {
        if (!search || !(e.metaKey || e.ctrlKey) || e.altKey || e.shiftKey || (e.key || '').toLowerCase() !== 'k') {
            return;
        }
        if (search.offsetParent === null) {
            return; // hidden below 1200px
        }
        e.preventDefault();
        search.focus();
    });
})(jQuery);
