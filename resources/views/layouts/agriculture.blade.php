<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Lagonglong FARMS' }}</title>
    <x-agriculture-assets />
    @stack('additional-css')
</head>
<body class="bg-gray-50">
    <style>
        :root { --sb-width: 260px; --sb-collapsed: 74px; }
        .sidebar { width: 100%; min-height: 100vh; display: flex; flex-direction: column; padding: 12px 10px; position: sticky; top: 0; transition: width 0.4s ease-in-out !important; }
        .sidebar__brand { display: flex; align-items: center; gap: 10px; margin: 6px 6px 14px; }
        .sidebar__toggle { background: rgba(255,255,255,0.12); border: none; color: #fff; width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s; }
        .sidebar__toggle:hover { background: rgba(255,255,255,0.2); }
        #topbarSidebarToggle { background: rgba(22,163,74,0.1); color: #16a34a; }
        #topbarSidebarToggle:hover { background: rgba(22,163,74,0.15); }
        .sidebar__logo { font-size: 22px; margin-left: 2px; }
        .sidebar__title { font-weight: 700; font-size: 16px; letter-spacing: .3px; white-space: nowrap; }
        .sidebar__nav { margin-top: 8px; display: flex; flex-direction: column; gap: 4px; }
        .sidebar-collapsed .sidebar__nav { padding-top: 48px !important; margin-top: 0 !important; }
        .sidebar__section { color: rgba(255,255,255,0.7); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; padding: 10px 12px 6px; }
        .sidebar__link { display: flex; align-items: center; gap: 12px; color: #fff; text-decoration: none; padding: 10px 12px; border-radius: 10px; opacity: 0.95; transition: background .2s, opacity .2s, color .2s; }
        .sidebar__link i { width: 18px; text-align: center; font-size: 14px; }
        .sidebar__link:hover { background: rgba(255,255,255,0.12); opacity: 1; }
        .sidebar__link.is-active { background: #ffffff; color: #16a34a; }
        .sidebar__link.is-active i { color: #16a34a; }
        .app-shell { min-height: 100vh; display: grid; grid-template-columns: var(--sb-width) 1fr; transition: grid-template-columns 0.6s ease-in-out !important; }
        .app-sidebar-col { width: var(--sb-width); transition: width 0.6s ease-in-out !important; }
        .app-main { display: flex; flex-direction: column; min-width: 0; }
        .app-topbar { background: #ffffff; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: flex-end; padding: 10px 16px; position: sticky; top: 0; z-index: 40; }
        .app-content { padding: 20px; }
        @media (max-width: 1024px) {
            :root { --sb-width: 220px; }
        }
        .sidebar-collapsed .app-shell { grid-template-columns: var(--sb-collapsed) 1fr; }
        .sidebar-collapsed .app-sidebar-col { width: var(--sb-collapsed); }
        .sidebar-collapsed .sidebar__title { display: none; }
        .sidebar-collapsed .sidebar__section { display: none; }
        .sidebar-collapsed .sidebar__link span { display: none; }
        /* Remove redundant per-page navigation switchers */
        button[onclick*="toggleNavigationDropdown"] { display: none !important; }
        #navigationDropdown { display: none !important; }
        [id="navigationDropdown"] { display: none !important; }
        [id="navigationArrow"] { display: none !important; }
    </style>
    <script>
        // Clean up any legacy "Go to Page" UI if present
        (function(){
            function removeRedundantNav(){
                try {
                    document.querySelectorAll('button[onclick*="toggleNavigationDropdown"]').forEach(function(btn){
                        var container = btn.closest('.relative');
                        if (container) { container.remove(); } else { btn.remove(); }
                    });
                    document.querySelectorAll('#navigationDropdown, [id="navigationDropdown"]').forEach(function(el){ el.remove(); });
                } catch(e) {}
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', removeRedundantNav);
            } else {
                removeRedundantNav();
            }
            // Neutralize any page-scoped function
            try { window.toggleNavigationDropdown = function(){ return false; }; } catch(e) {}
        })();
    </script>
    
    <div class="app-shell">
        <div class="app-sidebar-col">
            <x-sidebar />
        </div>
        <div class="app-main">
            <div class="app-topbar" style="display: flex; align-items: center;">
                <div style="flex: 1;"></div>
                <x-navigation />
            </div>
            <main class="app-content">
                @yield('content')
            </main>
        </div>
    </div>
    
    @if(session('success') || ($errors ?? null) && $errors->any())
    <script>
        // Auto-refresh the page after showing success or error messages
        (function(){
            setTimeout(function(){
                window.location.reload();
            }, 2000);
        })();
    </script>
    @endif

    @stack('additional-js')
</body>
</html>