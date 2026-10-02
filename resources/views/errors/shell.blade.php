{{--
    Error page layout (production error pages and /unauthorized).

    Deliberately self-contained: inline CSS and JS, an inline SVG mark, system fonts. No CDN,
    no app assets, no layouts.app (which needs a signed-in user and the database), so the page
    still renders when the network, the database or the app itself is what failed.

    Sections: code, title, message. Optional: show_reload (any value shows a Reload button).
    $errorRef is shared by App\Exceptions\Handler; pages rendered without it hide the reference.
--}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('title') · FleetFreak</title>
    <style>
        :root {
            --brand: #640D5F;
            --brand-hover: #9B3496;
            --brand-pale: #f5eef5;
            --bg: #f8f6f8;
            --surface: #ffffff;
            --border: #ede5ed;
            --text: #1c111b;
            --text-body: #6b5069;
            --muted: #937a92;
            --display: "DM Sans", "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            --body: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            --mono: "DM Mono", ui-monospace, "Cascadia Mono", Consolas, monospace;
        }
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background: var(--bg);
            color: var(--text);
            font: 400 14px/1.55 var(--body);
        }
        .card {
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
        }
        .band {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 24px;
            background: var(--brand);
            color: #fff;
            font: 700 17px/1 var(--display);
            letter-spacing: -0.3px;
        }
        .mark {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.18);
        }
        .content { padding: 28px 28px 26px; }
        .code {
            margin: 0 0 6px;
            font: 500 12px/1.4 var(--mono);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
        }
        h1 {
            margin: 0 0 10px;
            font: 700 22px/1.25 var(--display);
            letter-spacing: -0.4px;
            color: var(--text);
        }
        .message { margin: 0 0 22px; color: var(--text-body); }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 18px;
            border: 0;
            border-radius: 8px;
            font: 600 13px/1.2 var(--body);
            text-decoration: none;
            cursor: pointer;
        }
        .btn-primary { background: var(--brand); color: #fff; }
        .btn-primary:hover { background: var(--brand-hover); }
        .btn-soft { background: var(--brand-pale); color: var(--brand); }
        .btn:focus-visible { outline: 2px solid var(--brand-hover); outline-offset: 2px; }
        .ref {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px 12px;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid var(--border);
            font-size: 12px;
            color: var(--muted);
        }
        .ref code {
            padding: 3px 8px;
            border-radius: 6px;
            background: var(--brand-pale);
            color: var(--brand);
            font: 500 13px/1.4 var(--mono);
            letter-spacing: 0.02em;
            user-select: all;
        }
        .ref button {
            padding: 3px 8px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--surface);
            color: var(--text-body);
            font: 500 12px/1.4 var(--body);
            cursor: pointer;
        }
        .ref-note { flex-basis: 100%; }
    </style>
</head>
<body>
    <main class="card">
        <div class="band">
            <span class="mark" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3a1 1 0 0 1-1-1v-4l2.5-6H19.5L22 12v4a1 1 0 0 1-1 1h-2"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="16.5" cy="17.5" r="2.5"/></svg>
            </span>
            FleetFreak
        </div>
        <div class="content">
            <p class="code">Error @yield('code')</p>
            <h1>@yield('title')</h1>
            <p class="message">@yield('message')</p>
            <div class="actions">
                <a class="btn btn-primary" href="{{ url('/') }}">Back to dashboard</a>
                @hasSection('show_reload')
                    <button type="button" class="btn btn-soft" onclick="window.location.reload()">Reload page</button>
                @endif
            </div>
            @if (!empty($errorRef))
                <div class="ref">
                    <span>Error reference</span>
                    <code id="error-ref">{{ $errorRef }}</code>
                    <button type="button" id="copy-ref">Copy</button>
                    <span class="ref-note">If you contact support, quote this reference so we can find what happened.</span>
                </div>
                <script>
                    document.getElementById('copy-ref').addEventListener('click', function () {
                        var button = this;
                        var ref = document.getElementById('error-ref').textContent;
                        var done = function () { button.textContent = 'Copied'; };
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(ref).then(done, function () { window.prompt('Copy this reference:', ref); });
                        } else {
                            window.prompt('Copy this reference:', ref);
                        }
                    });
                </script>
            @endif
        </div>
    </main>
</body>
</html>
