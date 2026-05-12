<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Farmart - Nasugbu Agricultural Marketplace') ?></title>
    <meta name="description" content="Farmart - Direct marketplace connecting Nasugbu farmers with local buyers">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#166534',
                        'primary-hover': '#14532d',
                        'secondary': '#57534e',
                        'accent': '#b45309',
                        'success': '#15803d',
                        'warning': '#d97706',
                        'error': '#b91c1c',
                        'mint': '#b9d4be',
                        'mint-light': '#eef2ed',
                        'mint-dark': '#5f8f68',
                    }
                }
            }
        }
    </script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Farmart shared UI -->
    <link rel="stylesheet" href="/css/farmart-ui.css">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 + animations -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/js/farmart-ui.js" defer></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        /* Profile sidebar collapse styles */
        #profile-sidebar-container {
            transition: width .18s ease;
        }
        #profile-sidebar-container.collapsed {
            width: 5rem; /* ~w-20 */
        }
        #profile-sidebar-container .sidebar-text {
            transition: opacity .12s ease, transform .12s ease;
        }
        #profile-sidebar-container.collapsed .sidebar-text {
            opacity: 0;
            transform: translateX(-6px);
            pointer-events: none;
            display: none;
        }
        /* ensure chevron rotation uses Tailwind-compatible class */
        .rotate-180 { transform: rotate(180deg); }
        /* Tailwind-style tooltip support for collapsed sidebar items */
        [data-tooltip] {
            position: relative;
        }
        [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 8px;
            padding: 6px 10px;
            background-color: #1f2937;
            color: #fff;
            border-radius: 6px;
            font-size: 13px;
            white-space: nowrap;
            z-index: 40;
            pointer-events: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        /* Focus ring for keyboard accessibility */
        button:focus-visible, a:focus-visible {
            outline: 2px solid #166534;
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* Theme harmonization: remap blue utility accents to brand greens. */
        [class~="bg-blue-50"] { background-color: #f0fdf4 !important; }
        [class~="bg-blue-100"] { background-color: #dcfce7 !important; }
        [class~="bg-blue-200"] { background-color: #bbf7d0 !important; }
        [class~="bg-blue-500"] { background-color: #166534 !important; }
        [class~="bg-blue-600"] { background-color: #14532d !important; }
        [class~="bg-blue-700"] { background-color: #0f3d22 !important; }

        [class~="text-blue-500"] { color: #166534 !important; }
        [class~="text-blue-600"] { color: #14532d !important; }
        [class~="text-blue-700"] { color: #0f3d22 !important; }
        [class~="text-blue-800"] { color: #0a2a18 !important; }
        [class~="text-blue-900"] { color: #052012 !important; }

        [class~="border-blue-100"] { border-color: #bbf7d0 !important; }
        [class~="border-blue-200"] { border-color: #86efac !important; }
        [class~="border-blue-300"] { border-color: #4ade80 !important; }
        [class~="border-blue-500"] { border-color: #166534 !important; }
        [class~="border-l-blue-500"] { border-left-color: #166534 !important; }

        [class~="from-blue-50"] { --tw-gradient-from: #f0fdf4 var(--tw-gradient-from-position) !important; --tw-gradient-to: rgb(240 253 244 / 0) var(--tw-gradient-to-position) !important; }
        [class~="from-blue-100"] { --tw-gradient-from: #dcfce7 var(--tw-gradient-from-position) !important; --tw-gradient-to: rgb(220 252 231 / 0) var(--tw-gradient-to-position) !important; }
        [class~="from-blue-400"] { --tw-gradient-from: #4ade80 var(--tw-gradient-from-position) !important; --tw-gradient-to: rgb(74 222 128 / 0) var(--tw-gradient-to-position) !important; }
        [class~="from-blue-600"] { --tw-gradient-from: #166534 var(--tw-gradient-from-position) !important; --tw-gradient-to: rgb(22 101 52 / 0) var(--tw-gradient-to-position) !important; }
        [class~="to-blue-100"] { --tw-gradient-to: #dcfce7 var(--tw-gradient-to-position) !important; }
        [class~="to-blue-500"] { --tw-gradient-to: #166534 var(--tw-gradient-to-position) !important; }
        [class~="to-blue-700"] { --tw-gradient-to: #0f3d22 var(--tw-gradient-to-position) !important; }

        [class*="focus:ring-blue-"]:focus {
            --tw-ring-color: #166534 !important;
        }
        [class~="hover:bg-blue-100"]:hover { background-color: #dcfce7 !important; }
        [class~="hover:bg-blue-200"]:hover { background-color: #bbf7d0 !important; }
        [class~="hover:bg-blue-600"]:hover { background-color: #14532d !important; }
        [class~="hover:bg-blue-700"]:hover { background-color: #0f3d22 !important; }
        [class~="hover:text-blue-800"]:hover { color: #0a2a18 !important; }
    </style>
</head>
<body class="min-h-screen flex flex-col farmart-page farmart-app antialiased">
    
    <?= $this->include('components/navbar') ?>


    <!-- Main Content -->
    <main class="flex-1">
        <div class="flex min-h-screen">
            <?php
            $currentUri = uri_string();
            $showSidebar = false; // Sidebar content moved to navbar profile dropdown
            ?>

            <!-- Main Content Area -->
            <div id="main-content" class="flex-1 farmart-main-rail <?= session()->has('logged_in') && session()->get('logged_in') && session()->get('user_role') !== 'admin' ? 'mr-0' : '' ?>">
                <?= $this->renderSection('content') ?>
            </div>

            <!-- Sidebar for profile and buyer pages (rendered on the right) - HIDDEN: moved to navbar -->
            <?php if ($showSidebar): ?>
                <aside id="profile-sidebar-container" class="w-64 bg-white shadow-md">
                    <?= $this->include('components/profile_sidebar') ?>
                </aside>
            <?php endif; ?>
        </div>
    </main>
    
    <?php if (!session()->has('logged_in') || !session()->get('logged_in')): ?>
        <?= $this->include('components/footer') ?>
    <?php endif; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>

    <script>
        // Sidebar collapse toggle: persists state in localStorage with responsive behavior
        document.addEventListener('DOMContentLoaded', function(){
            const btn = document.getElementById('sidebar-collapse-btn');
            const container = document.getElementById('profile-sidebar-container');
            if (!btn || !container) return;

            // Tailwind breakpoints
            const SM_BREAKPOINT = 640;  // sm
            const MD_BREAKPOINT = 768;  // md
            
            // Restore saved state or auto-collapse on small screens
            const saved = localStorage.getItem('profileSidebarCollapsed');
            const chevron = btn.querySelector('i[data-lucide="chevron-left"]');
            
            function shouldAutoCollapse() {
                return window.innerWidth < MD_BREAKPOINT && saved === null;
            }

            // If user has a saved preference, use it. Otherwise auto-collapse on screens < md (768px).
            if (saved === 'true' || shouldAutoCollapse()) {
                container.classList.add('collapsed');
                if (chevron) chevron.classList.add('rotate-180');
                btn.setAttribute('aria-expanded', 'false');
            }

            // Update data-tooltip attributes for collapsed items (CSS handles tooltip display)
            function updateSidebarTooltips() {
                const items = container.querySelectorAll('nav a, nav button, nav form button');
                const collapsed = container.classList.contains('collapsed');
                items.forEach(el => {
                    const text = el.innerText || el.textContent || '';
                    const label = text.trim();
                    if (collapsed && label.length) {
                        el.setAttribute('data-tooltip', label);
                        el.removeAttribute('title');
                    } else {
                        el.removeAttribute('data-tooltip');
                        el.removeAttribute('title');
                    }
                });
            }

            updateSidebarTooltips();

            btn.addEventListener('click', function(){
                const collapsed = container.classList.toggle('collapsed');
                localStorage.setItem('profileSidebarCollapsed', collapsed ? 'true' : 'false');
                if (chevron) chevron.classList.toggle('rotate-180');
                btn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                updateSidebarTooltips();

                // adjust main content padding when sidebar visible/hidden
                const main = document.getElementById('main-content');
                if (main) {
                    if (collapsed) main.classList.remove('pr-6');
                    else main.classList.add('pr-6');
                }
            });

            // Listen for window resize and auto-collapse if crossing md breakpoint with no saved preference
            window.addEventListener('resize', function() {
                if (saved === null) {
                    const shouldCollapse = window.innerWidth < MD_BREAKPOINT;
                    const isCollapsed = container.classList.contains('collapsed');
                    if (shouldCollapse && !isCollapsed) {
                        btn.click();
                    } else if (!shouldCollapse && isCollapsed) {
                        btn.click();
                    }
                }
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
