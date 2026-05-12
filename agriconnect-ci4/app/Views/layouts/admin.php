<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Panel - Farmart') ?></title>
    <meta name="description" content="Admin panel for Farmart">

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

    <link rel="stylesheet" href="/css/farmart-ui.css">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 + animations -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/js/farmart-ui.js" defer></script>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Theme harmonization: remap blue utility accents to dark green system colors. */
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
<body class="min-h-screen flex farmart-page farmart-admin-body antialiased">

    <?= $this->include('components/admin_sidebar') ?>

    <div id="main-content" class="flex-1 ml-64 transition-all duration-300 farmart-admin-main">

        <!-- Main Content -->
        <main class="flex-1">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('admin-sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleBtn = document.getElementById('sidebar-toggle');
            const navContainer = document.getElementById('nav-container');
            const sidebarTitle = document.getElementById('sidebar-title');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            const sidebarIcons = document.querySelectorAll('.sidebar-link i');
            const chevronIcon = toggleBtn.querySelector('i');

            let isCollapsed = false;

            toggleBtn.addEventListener('click', function() {
                isCollapsed = !isCollapsed;

                if (isCollapsed) {
                    sidebar.classList.add('farmart-admin-sidebar-collapsed');
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-20');
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-20');
                    sidebarTitle.classList.add('hidden');
                    sidebarTexts.forEach(text => text.classList.add('hidden'));
                    sidebarLinks.forEach(link => {
                        link.classList.remove('px-4');
                    });
                    sidebarIcons.forEach(icon => {
                        icon.classList.remove('w-5', 'h-5', 'mr-3');
                        icon.classList.add('w-10', 'h-10');
                    });
                    chevronIcon.setAttribute('data-lucide', 'chevron-right');
                } else {
                    sidebar.classList.remove('farmart-admin-sidebar-collapsed');
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');
                    mainContent.classList.remove('ml-20');
                    mainContent.classList.add('ml-64');
                    sidebarTitle.classList.remove('hidden');
                    sidebarTexts.forEach(text => text.classList.remove('hidden'));
                    sidebarLinks.forEach(link => {
                        link.classList.add('px-4');
                    });
                    sidebarIcons.forEach(icon => {
                        icon.classList.remove('w-10', 'h-10');
                        icon.classList.add('w-5', 'h-5', 'mr-3');
                    });
                    chevronIcon.setAttribute('data-lucide', 'chevron-left');
                }

                // Re-initialize icons
                lucide.createIcons();
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>