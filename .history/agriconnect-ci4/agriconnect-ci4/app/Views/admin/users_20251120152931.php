
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">User Management</h1>
        <p class="text-gray-600">Manage all users in the system</p>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
        <form method="get" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
