<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Analytics & Reports</h1>
        <p class="text-gray-600">System performance metrics and insights</p>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">₱0.00</p>
                    <p class="text-xs text-gray-500">All time</p>
                </div>
            </div>
        </div>

        <!-- Monthly Growth -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i data-lucide="trending-up" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Monthly Growth</p>
                    <p class="text-2xl font-bold text-gray-900">+0%</p>
                    <p class="text-xs text-gray-500">vs last month</p>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i data-lucide="users" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-2xl font-bold text-gray-900">0</p>
                    <p class="text-xs text-gray-500">This month</p>
                </div>
            </div>
        </div>

        <!-- Conversion Rate -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i data-lucide="target" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Conversion Rate</p>
                    <p class="text-2xl font-bold text-gray-900">0%</p>
                    <p class="text-xs text-gray-500">Orders per user</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trends</h3>
            <div class="h-64 flex items-center justify-center text-gray-500">
                <div class="text-center">
                    <i data-lucide="bar-chart-3" class="w-12 h-12 mx-auto mb-4"></i>
                    <p>Revenue chart will be displayed here</p>
                    <p class="text-sm">Integration with charting library needed</p>
                </div>
            </div>
        </div>

        <!-- User Registration Chart -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Registrations</h3>
            <div class="h-64 flex items-center justify-center text-gray-500">
                <div class="text-center">
                    <i data-lucide="user-plus" class="w-12 h-12 mx-auto mb-4"></i>
                    <p>User registration chart will be displayed here</p>
                    <p class="text-sm">Integration with charting library needed</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Detailed Reports</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Top Products -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Top Products</h4>
                <div class="space-y-3">
                    <div class="text-center py-8 text-gray-500">
                        <i data-lucide="package" class="w-8 h-8 mx-auto mb-2"></i>
                        <p class="text-sm">No data available</p>
                    </div>
                </div>
            </div>

            <!-- Top Farmers -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Top Farmers</h4>
                <div class="space-y-3">
                    <div class="text-center py-8 text-gray-500">
                        <i data-lucide="user" class="w-8 h-8 mx-auto mb-2"></i>
                        <p class="text-sm">No data available</p>
                    </div>
                </div>
            </div>

            <!-- Top Buyers -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Top Buyers</h4>
                <div class="space-y-3">
                    <div class="text-center py-8 text-gray-500">
                        <i data-lucide="shopping-cart" class="w-8 h-8 mx-auto mb-2"></i>
                        <p class="text-sm">No data available</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Options -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h4 class="font-semibold text-gray-900 mb-4">Export Reports</h4>
            <div class="flex flex-wrap gap-4">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>
                    Export PDF
                </button>
                <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>
                    Export Excel
                </button>
                <button class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>
                    Export CSV
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>