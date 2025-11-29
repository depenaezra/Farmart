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
<body class="min-h-screen flex flex-col bg-mint-light">
    
    <?= $this->include('components/navbar') ?>
    
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success') || session()->getFlashdata('error') || session()->getFlashdata('errors')): ?>
    <div class="container mx-auto px-4 mt-4 bg-white bg-opacity-80 rounded-lg p-4 shadow-sm">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-md mb-4" role="alert">
                <div class="flex items-center">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                    <p><?= esc(session()->getFlashdata('success')) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-md mb-4" role="alert">
                <div class="flex items-center">
                    <i data-lucide="alert-circle" class="w-5 h-5 mr-2"></i>
                    <p><?= esc(session()->getFlashdata('error')) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-md mb-4" role="alert">
                <div class="flex items-start">
                    <i data-lucide="alert-circle" class="w-5 h-5 mr-2 mt-0.5"></i>
                    <div>
                        <p class="font-semibold mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="flex-1">
        <?= $this->renderSection('content') ?>
    </main>
    
    <?= $this->include('components/footer') ?>
    
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

    <?= $this->renderSection('scripts') ?>
</body>
</html>
