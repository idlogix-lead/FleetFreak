/*
 * App shell (top bar + sidebar): resources/views/layouts/header.blade.php and nav.blade.php.
 * Loaded at the end of the header, not deferred: the header markup above it exists, jQuery and
 * msgboxbox come from the layout's <head>, and ffsQuiet() below is defined before page scripts run.
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

/*
 * Failed AJAX requests: one dismissible msgboxbox toast for every jQuery request, so pages
 * need no changes. Error JSON from App\Exceptions\Handler carries a plain message and a
 * reference ID, and the toast shows both.
 *
 * A call site that already shows its own error message opts out by wrapping the call:
 *     ffsQuiet($.post(url, data)).then(...).fail(...)
 * or, for $.ajax, with the option { ffsQuiet: true }. Validation errors (422) and cancelled
 * requests are always left to the call site.
 */
(function () {
    'use strict';

    // Marks the request at send time: .fail() handlers chained after .then() run only after
    // jQuery's global ajaxError event, so they can't opt out themselves.
    window.ffsQuiet = function (jqXHR) {
        if (jqXHR) {
            jqXHR.ffsQuiet = true;
        }
        return jqXHR;
    };

    // Requests cut off by navigating away fail with status 0; that's not worth a toast.
    var leaving = false;
    window.addEventListener('beforeunload', function () { leaving = true; });
    window.addEventListener('pagehide', function () { leaving = true; });

    var MESSAGES = {
        0: 'Can’t reach FleetFreak. Check your connection and try again.',
        401: 'Your session has ended. Please sign in again.',
        403: 'You don’t have permission to do that.',
        404: 'We couldn’t find what you were looking for.',
        419: 'Your session expired. Reload the page and try again.',
        429: 'Too many requests. Wait a minute and try again.'
    };

    function escapeHtml(text) {
        return String(text).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function messageFor(jqXHR, settings) {
        var status = jqXHR.status;
        var json = jqXHR.responseJSON || null;
        var text;
        if (json && json.ref && json.message) {
            text = json.message; // from App\Exceptions\Handler
        } else if ((status === 401 || status === 403) && json && json.errors && json.errors.length) {
            text = json.errors[0]; // RolePermissions denial: the role's configured message
        } else if (status === 0 && settings && settings.crossDomain) {
            text = 'A service this page uses didn’t respond. Please try again.';
        } else {
            text = MESSAGES[status] || (status >= 500
                ? 'Something went wrong on our side. Please try again.'
                : 'That request couldn’t be completed.');
        }
        var html = escapeHtml(text);
        if (json && json.ref) {
            html += '<br><small>Reference: ' + escapeHtml(json.ref) + '</small>';
        }
        return html;
    }

    function onAjaxError(event, jqXHR, settings) {
        if (!jqXHR || jqXHR.ffsQuiet || (settings && settings.ffsQuiet) || leaving) {
            return;
        }
        if (jqXHR.statusText === 'abort' || jqXHR.status === 422 || typeof msgboxbox === 'undefined') {
            return;
        }
        msgboxbox.show(messageFor(jqXHR, settings), 'error');
    }

    // Bind to the layout's jQuery now, and to the page's own copy once the page has loaded,
    // if it brought a second one (the driver-assignment pages and the receipts index do).
    var bound = [];
    function bind(jq) {
        if (!jq || bound.indexOf(jq) !== -1) {
            return;
        }
        bound.push(jq);
        jq(document).on('ajaxError', onAjaxError);
    }
    bind(window.jQuery);
    document.addEventListener('DOMContentLoaded', function () {
        bind(window.jQuery);
    });
})();
