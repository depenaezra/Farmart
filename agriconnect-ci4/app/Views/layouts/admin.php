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

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex bg-mint-light">

    <?= $this->include('components/admin_sidebar') ?>

    <div class="flex-1 ml-64">
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
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>