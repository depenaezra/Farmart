<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'AgriConnect - Nasugbu Agricultural Marketplace') ?></title>
    <meta name="description" content="AgriConnect - Direct marketplace connecting Nasugbu farmers with local buyers">
    
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
                    }
                }
            }
        }
    </script>
    
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
<body class="min-h-screen flex flex-col bg-gray-50">
    
    <?= $this->include('components/navbar') ?>
    
    <!-- Flash Messages -->
    <div class="container mx-auto px-4 mt-4">
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
    
    <!-- Main Content -->
    <main class="flex-1">
        <?= $this->renderSection('content') ?>
    </main>
    
    <?= $this->include('components/footer') ?>
    
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</pre>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const container = document.querySelector('.container.mx-auto');
            const alerts = container ? container.querySelectorAll('[role="alert"]') : [];
            alerts.forEach(el => {
                const cls = el.className || '';
                let icon = 'info';
                let title = '';
                if (/green|success/.test(cls)) { icon = 'success'; title = 'Success'; }
                else if (/red|error/.test(cls)) { icon = 'error'; title = 'Error'; }
                else if (/warning/.test(cls)) { icon = 'warning'; title = 'Warning'; }

                // Prefer list content for errors, otherwise first paragraph text
                let html = '';
                const ul = el.querySelector('ul');
                if (ul) {
                    html = '<ul style="text-align:left">' + Array.from(ul.querySelectorAll('li')).map(li => '<li>' + li.innerHTML + '</li>').join('') + '</ul>';
                } else {
                    const p = el.querySelector('p');
                    html = p ? p.innerHTML : el.innerHTML;
                }

                // Remove original element from DOM
                el.remove();

                // Show SweetAlert2 popup
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
                    // If SweetAlert2 isn't available for some reason, silently fallback to keeping alert markup
                    console.error('SweetAlert2 not available', e);
                }
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
