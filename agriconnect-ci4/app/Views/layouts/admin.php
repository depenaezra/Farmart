<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Panel - Farmart') ?></title>
    <meta name="description" content="Admin panel for Farmart">
    <link rel="icon" href="/img/farmart_logo_only.png" type="image/png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#2d7a3e',
                        'primary-hover': '#236330',
                        'secondary': '#8b6f47',
                        'accent': '#d97706',
                        'success': '#16a34a',
                        'warning': '#f59e0b',
                        'error': '#dc2626',
                        'mint': '#a7f3d0',
                        'mint-light': '#d1fae5',
                        'mint-dark': '#6ee7b7',
                    }
                }
            }
        }
    </script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 + animations -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex bg-mint-light">

    <?= $this->include('components/admin_sidebar') ?>

    <div id="main-content" class="flex-1 ml-64 transition-all duration-300">

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

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(el => {
                const cls = el.className || '';
                let icon = 'info';
                let title = '';
                if (/green|success/.test(cls)) { icon = 'success'; title = 'Success'; }
                else if (/red|error/.test(cls)) { icon = 'error'; title = 'Error'; }
                else if (/warning/.test(cls)) { icon = 'warning'; title = 'Warning'; }

                // Extract message HTML: prefer list items, otherwise paragraph text
                let html = '';
                const ul = el.querySelector('ul');
                if (ul) {
                    html = '<ul style="text-align:left">' + Array.from(ul.querySelectorAll('li')).map(li => '<li>' + li.innerHTML + '</li>').join('') + '</ul>';
                } else {
                    const p = el.querySelector('p');
                    html = p ? p.innerHTML : el.innerHTML;
                }

                // Remove original element so it doesn't show in page
                el.remove();

                try {
                    const isList = !!el.querySelector('ul');
                    const textOnly = (function(){
                        const p = el.querySelector('p');
                        if (p) return p.innerText.trim();
                        return el.innerText.trim();
                    })();

                    const useToast = icon === 'success' && !isList && textOnly.length > 0 && textOnly.length < 160;

                    if (useToast) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: icon,
                            title: textOnly,
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                            showClass: { popup: 'animate__animated animate__fadeInDown' },
                            hideClass: { popup: 'animate__animated animate__fadeOutUp' }
                        });
                    } else {
                        Swal.fire({
                            title: title,
                            html: html,
                            icon: icon,
                            showCloseButton: true,
                            showClass: { popup: 'animate__animated animate__bounceIn' },
                            hideClass: { popup: 'animate__animated animate__fadeOutUp' },
                            timer: icon === 'success' ? 2500 : undefined
                        });
                    }
                } catch (e) {
                    console.error('SweetAlert2 not available', e);
                }
            });
        });
    </script>

    <script>
        // Admin area confirm wrapper
        document.addEventListener('DOMContentLoaded', function(){
            function showConfirm(message, confirmText = 'Yes', cancelText = 'Cancel'){
                if (!(window.Swal)) return Promise.resolve({ isConfirmed: confirm(message) });
                return Swal.fire({
                    title: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    reverseButtons: true,
                    showClass: { popup: 'animate__animated animate__zoomIn' },
                    hideClass: { popup: 'animate__animated animate__fadeOutUp' }
                });
            }

            document.querySelectorAll('form[data-confirm], form.swal-confirm-form').forEach(form => {
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    const msg = form.getAttribute('data-confirm') || form.dataset.confirmMessage || 'Are you sure?';
                    showConfirm(msg).then(result => {
                        if (result && result.isConfirmed) form.submit();
                    });
                });
            });

            document.querySelectorAll('[data-confirm], .swal-confirm').forEach(el => {
                if (el.tagName === 'FORM') return;

                el.addEventListener('click', function(e){
                    const msg = el.getAttribute('data-confirm') || el.dataset.confirmMessage || 'Are you sure?';
                    const href = el.getAttribute('href');
                    if (href) {
                        e.preventDefault();
                        showConfirm(msg).then(result => { if (result && result.isConfirmed) window.location = href; });
                        return;
                    }

                    const target = el.getAttribute('data-target-form');
                    if (target) {
                        e.preventDefault();
                        const form = document.querySelector(target);
                        if (form){ showConfirm(msg).then(result => { if (result && result.isConfirmed) form.submit(); }); }
                    }
                });
            });
        });
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
                    // Collapse sidebar to icon-only
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-20');
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-20');
                    sidebarTitle.classList.add('hidden');
                    sidebarTexts.forEach(text => text.classList.add('hidden'));
                    sidebarLinks.forEach(link => {
                        link.classList.remove('px-4');
                        link.classList.remove('hover:bg-primary', 'hover:text-white');
                    });
                    sidebarIcons.forEach(icon => {
                        icon.classList.remove('w-5', 'h-5', 'mr-3');
                        icon.classList.add('w-10', 'h-10');
                    });
                    chevronIcon.setAttribute('data-lucide', 'chevron-right');
                } else {
                    // Expand sidebar
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');
                    mainContent.classList.remove('ml-20');
                    mainContent.classList.add('ml-64');
                    sidebarTitle.classList.remove('hidden');
                    sidebarTexts.forEach(text => text.classList.remove('hidden'));
                    sidebarLinks.forEach(link => {
                        link.classList.add('px-4');
                        link.classList.add('hover:bg-primary', 'hover:text-white');
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