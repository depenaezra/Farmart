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


    <!-- Custom CSS -->
    <link rel="stylesheet" href="/src/index.css">
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
            outline: 2px solid #2d7a3e;
            outline-offset: 2px;
            border-radius: 4px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-mint-light">
    
    <?= $this->include('components/navbar') ?>


    <!-- Main Content -->
    <main class="flex-1">
        <div class="flex min-h-screen">
            <?php
            $currentUri = uri_string();
            $showSidebar = false; // Sidebar content moved to navbar profile dropdown
            ?>

            <!-- Main Content Area -->
            <div id="main-content" class="flex-1 <?= session()->has('logged_in') && session()->get('logged_in') && session()->get('user_role') !== 'admin' ? 'mr-0' : '' ?>">
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
        document.addEventListener('DOMContentLoaded', function(){
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(el => {
                const cls = el.className || '';
                let icon = 'info';
                let title = '';
                if (/green|success/.test(cls)) { icon = 'success'; title = 'Success'; }
                else if (/red|error/.test(cls)) { icon = 'error'; title = 'Error'; }
                else if (/warning/.test(cls)) { icon = 'warning'; title = 'Warning'; }

                // Skip error messages - don't show them as popups
                if (icon === 'error') {
                    el.remove(); // Just remove error alerts without showing popup
                    return;
                }

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
        // Global SweetAlert2 confirm handler for forms and links/buttons
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

            // Intercept forms with data-confirm or class swal-confirm-form
            document.querySelectorAll('form[data-confirm], form.swal-confirm-form').forEach(form => {
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    const msg = form.getAttribute('data-confirm') || form.dataset.confirmMessage || 'Are you sure?';
                    showConfirm(msg).then(result => {
                        if (result && result.isConfirmed) form.submit();
                    });
                });
            });

            // Intercept links/buttons with data-confirm or class swal-confirm
            document.querySelectorAll('[data-confirm], .swal-confirm').forEach(el => {
                // skip forms (handled above)
                if (el.tagName === 'FORM') return;

                el.addEventListener('click', function(e){
                    const msg = el.getAttribute('data-confirm') || el.dataset.confirmMessage || 'Are you sure?';

                    // If element is a link
                    const href = el.getAttribute('href');
                    if (href) {
                        e.preventDefault();
                        showConfirm(msg).then(result => {
                            if (result && result.isConfirmed) window.location = href;
                        });
                        return;
                    }

                    // If element targets a form via data-target-form
                    const target = el.getAttribute('data-target-form');
                    if (target) {
                        e.preventDefault();
                        const form = document.querySelector(target);
                        if (form){
                            showConfirm(msg).then(result => { if (result && result.isConfirmed) form.submit(); });
                        }
                    }
                });
            });
        });
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
