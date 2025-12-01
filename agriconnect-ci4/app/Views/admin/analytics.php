<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Analytics & Reports</h1>
        <p class="text-gray-600">System performance metrics and insights</p>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Monthly Growth -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i data-lucide="trending-up" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Monthly Growth</p>
                    <p class="text-2xl font-bold text-gray-900">+<?= $metrics['user_growth'] ?>%</p>
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
                    <p class="text-2xl font-bold text-gray-900"><?= $metrics['active_users'] ?></p>
                    <p class="text-xs text-gray-500">Last 30 days</p>
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
                    <p class="text-2xl font-bold text-gray-900"><?= $metrics['conversion_rate'] ?>%</p>
                    <p class="text-xs text-gray-500">Orders per user</p>
                </div>
            </div>
        </div>

        <!-- Monthly Orders -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i data-lucide="shopping-bag" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Monthly Orders</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $metrics['monthly_orders'] ?></p>
                    <p class="text-xs text-gray-500">Completed this month</p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-lg">
                    <i data-lucide="user-check" class="w-6 h-6 text-indigo-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $metrics['total_users'] ?></p>
                    <p class="text-xs text-gray-500">All registered</p>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i data-lucide="package" class="w-6 h-6 text-orange-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $metrics['total_products'] ?></p>
                    <p class="text-xs text-gray-500">Listed items</p>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-teal-100 rounded-lg">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-teal-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $metrics['total_orders'] ?></p>
                    <p class="text-xs text-gray-500">All time</p>
                </div>
            </div>
        </div>

        <!-- System Activity -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-emerald-100 rounded-lg">
                    <i data-lucide="activity" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">System Activity</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $metrics['active_users'] ?></p>
                    <p class="text-xs text-gray-500">Active users this month</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js and Export Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- Detailed Reports -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Detailed Reports</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Top Products -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Top Products by Orders</h4>
                <div class="space-y-3">
                    <?php if (!empty($top_data['products'])): ?>
                        <?php foreach ($top_data['products'] as $product): ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm"><?= esc($product['name']) ?></p>
                                    <p class="text-xs text-gray-500 capitalize"><?= esc($product['category']) ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900"><?= $product['order_count'] ?? 0 ?></p>
                                    <p class="text-xs text-gray-500">orders</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8 text-gray-500">
                            <i data-lucide="package" class="w-8 h-8 mx-auto mb-2"></i>
                            <p class="text-sm">No data available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Top Sellers -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Top Sellers by Orders</h4>
                <div class="space-y-3">
                    <?php if (!empty($top_data['farmers'])): ?>
                        <?php foreach ($top_data['farmers'] as $farmer): ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm"><?= esc($farmer['name']) ?></p>
                                    <p class="text-xs text-gray-500"><?= esc($farmer['location']) ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900"><?= $farmer['order_count'] ?? 0 ?></p>
                                    <p class="text-xs text-gray-500">orders</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8 text-gray-500">
                            <i data-lucide="user" class="w-8 h-8 mx-auto mb-2"></i>
                            <p class="text-sm">No data available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <!-- Export Options -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h4 class="font-semibold text-gray-900 mb-4">Export Reports</h4>
            <div class="flex flex-wrap gap-4">
                <button onclick="exportPDF()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>
                    Export PDF
                </button>
                <button onclick="exportExcel()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>
                    Export Excel
                </button>
                <button onclick="exportCSV()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>
                    Export CSV
                </button>
            </div>
        </div>
    </div>

    <!-- Export Scripts -->
    <script>
        function exportCSV() {
            const metrics = <?= json_encode($metrics) ?>;
            const charts = <?= json_encode($charts) ?>;
            const topData = <?= json_encode($top_data) ?>;

            let csv = 'Analytics Report\n\n';
            csv += 'Key Metrics\n';
            csv += 'Metric,Value\n';
            csv += `Monthly Growth,${metrics.user_growth}%\n`;
            csv += `Active Users,${metrics.active_users}\n`;
            csv += `Conversion Rate,${metrics.conversion_rate}%\n`;
            csv += `Monthly Orders,${metrics.monthly_orders}\n`;
            csv += `Total Users,${metrics.total_users}\n`;
            csv += `Total Products,${metrics.total_products}\n`;
            csv += `Total Orders,${metrics.total_orders}\n\n`;

            csv += 'User Registrations (Last 12 Months)\n';
            csv += 'Month,Count\n';
            charts.user_registrations.forEach(item => {
                csv += `${item.month},${item.count}\n`;
            });
            csv += '\n';

            csv += 'Orders (Last 12 Months)\n';
            csv += 'Month,Count\n';
            charts.orders.forEach(item => {
                csv += `${item.month},${item.count}\n`;
            });
            csv += '\n';

            csv += 'Top Products\n';
            csv += 'Product,Category,Orders\n';
            topData.products.forEach(product => {
                csv += `"${product.name}","${product.category}",${product.order_count || 0}\n`;
            });
            csv += '\n';

            csv += 'Top Sellers\n';
            csv += 'Seller,Location,Orders\n';
            topData.farmers.forEach(farmer => {
                csv += `"${farmer.name}","${farmer.location}",${farmer.order_count || 0}\n`;
            });
            csv += '\n';

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'analytics_report.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function exportPDF() {
            try {
                // Check if jsPDF is loaded
                if (typeof window.jspdf === 'undefined') {
                    alert('PDF library not loaded. Please refresh the page and try again.');
                    return;
                }

                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF();

                // Simple test PDF first
                pdf.text('Farmart Analytics Report', 20, 30);
                pdf.text('Generated on: ' + new Date().toLocaleDateString(), 20, 40);

                const metrics = <?= json_encode($metrics) ?>;
                let y = 60;
                pdf.text('Key Metrics:', 20, y);
                y += 10;
                pdf.text(`Total Users: ${metrics.total_users}`, 20, y);
                y += 10;
                pdf.text(`Total Products: ${metrics.total_products}`, 20, y);
                y += 10;
                pdf.text(`Total Orders: ${metrics.total_orders}`, 20, y);

                pdf.save('farmart_analytics_report.pdf');
            } catch (error) {
                console.error('PDF Export Error:', error);
                alert('PDF export failed. Error: ' + error.message);
            }
        }

        function exportExcel() {
            const workbook = XLSX.utils.book_new();

            // Dashboard sheet with logo and summary
            const dashboardData = [
                ['FARMART ANALYTICS DASHBOARD'],
                ['Generated on:', new Date().toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })],
                [''],
                ['KEY PERFORMANCE METRICS'],
                ['Metric', 'Value', 'Status'],
            ];

            const metrics = <?= json_encode($metrics) ?>;
            dashboardData.push(
                ['Monthly Growth', `${metrics.user_growth}%`, metrics.user_growth >= 0 ? '📈 Growing' : '📉 Declining'],
                ['Active Users', metrics.active_users, metrics.active_users > 10 ? '🔥 High Activity' : '📊 Moderate'],
                ['Conversion Rate', `${metrics.conversion_rate}%`, metrics.conversion_rate > 5 ? '💪 Strong' : '📈 Improving'],
                ['Monthly Orders', metrics.monthly_orders, metrics.monthly_orders > 20 ? '🚀 Excellent' : '📊 Good'],
                ['Total Users', metrics.total_users, '👥 Community'],
                ['Total Products', metrics.total_products, '🥕 Marketplace'],
                ['Total Orders', metrics.total_orders, '📦 Transactions']
            );

            const dashboardSheet = XLSX.utils.aoa_to_sheet(dashboardData);

            // Add styling to dashboard sheet
            if (!dashboardSheet['!cols']) dashboardSheet['!cols'] = [];
            dashboardSheet['!cols'][0] = { width: 25 };
            dashboardSheet['!cols'][1] = { width: 15 };
            dashboardSheet['!cols'][2] = { width: 20 };

            // Style the header
            dashboardSheet['A1'] = { v: 'FARMART ANALYTICS DASHBOARD', t: 's', s: { font: { bold: true, sz: 16, color: { rgb: '22C55E' } } } };
            dashboardSheet['A4'] = { v: 'KEY PERFORMANCE METRICS', t: 's', s: { font: { bold: true, sz: 12, color: { rgb: '16A34A' } } } };

            XLSX.utils.book_append_sheet(workbook, dashboardSheet, 'Dashboard');

            // User Registrations sheet
            const charts = <?= json_encode($charts) ?>;
            const userRegData = [
                ['FARMART - User Registration Trends'],
                ['Last 12 Months'],
                [''],
                ['Month', 'New Users', 'Growth %']
            ];

            charts.user_registrations.forEach((item, index) => {
                const prevCount = index > 0 ? charts.user_registrations[index - 1].count : item.count;
                const growth = index > 0 ? (((item.count - prevCount) / prevCount) * 100).toFixed(1) : 0;
                userRegData.push([item.month, item.count, `${growth}%`]);
            });

            const userRegSheet = XLSX.utils.aoa_to_sheet(userRegData);
            if (!userRegSheet['!cols']) userRegSheet['!cols'] = [];
            userRegSheet['!cols'][0] = { width: 15 };
            userRegSheet['!cols'][1] = { width: 12 };
            userRegSheet['!cols'][2] = { width: 12 };

            // Style header
            userRegSheet['A1'] = { v: 'FARMART - User Registration Trends', t: 's', s: { font: { bold: true, sz: 14, color: { rgb: '22C55E' } } } };

            XLSX.utils.book_append_sheet(workbook, userRegSheet, 'User Registrations');

            // Orders sheet
            const ordersData = [
                ['FARMART - Order Volume Trends'],
                ['Last 12 Months'],
                [''],
                ['Month', 'Orders', 'Trend']
            ];

            charts.orders.forEach((item, index) => {
                const prevCount = index > 0 ? charts.orders[index - 1].count : item.count;
                const trend = index > 0 ?
                    (item.count > prevCount ? '📈 Up' : item.count < prevCount ? '📉 Down' : '➡️ Stable') :
                    '📊 Starting';
                ordersData.push([item.month, item.count, trend]);
            });

            const ordersSheet = XLSX.utils.aoa_to_sheet(ordersData);
            if (!ordersSheet['!cols']) ordersSheet['!cols'] = [];
            ordersSheet['!cols'][0] = { width: 15 };
            ordersSheet['!cols'][1] = { width: 12 };
            ordersSheet['!cols'][2] = { width: 12 };

            // Style header
            ordersSheet['A1'] = { v: 'FARMART - Order Volume Trends', t: 's', s: { font: { bold: true, sz: 14, color: { rgb: '22C55E' } } } };

            XLSX.utils.book_append_sheet(workbook, ordersSheet, 'Orders');

            // Top Products sheet
            const topData = <?= json_encode($top_data) ?>;
            const productsData = [
                ['FARMART - Top Products by Orders'],
                ['Ranked by order volume'],
                [''],
                ['Rank', 'Product Name', 'Category', 'Orders', 'Performance']
            ];

            topData.products.forEach((product, index) => {
                const performance = product.order_count > 50 ? '⭐ Excellent' :
                                   product.order_count > 20 ? '✅ Good' :
                                   product.order_count > 5 ? '📈 Moderate' : '📊 Developing';
                productsData.push([index + 1, product.name, product.category, product.order_count, performance]);
            });

            const productsSheet = XLSX.utils.aoa_to_sheet(productsData);
            if (!productsSheet['!cols']) productsSheet['!cols'] = [];
            productsSheet['!cols'][0] = { width: 8 };
            productsSheet['!cols'][1] = { width: 30 };
            productsSheet['!cols'][2] = { width: 15 };
            productsSheet['!cols'][3] = { width: 10 };
            productsSheet['!cols'][4] = { width: 15 };

            // Style header
            productsSheet['A1'] = { v: 'FARMART - Top Products by Orders', t: 's', s: { font: { bold: true, sz: 14, color: { rgb: '22C55E' } } } };

            XLSX.utils.book_append_sheet(workbook, productsSheet, 'Top Products');

            // Top Sellers sheet
            const sellersData = [
                ['FARMART - Top Sellers by Orders'],
                ['Most active marketplace sellers'],
                [''],
                ['Rank', 'Seller Name', 'Location', 'Orders', 'Status']
            ];

            topData.farmers.forEach((seller, index) => {
                const status = seller.order_count > 100 ? '🏆 Top Seller' :
                              seller.order_count > 50 ? '🥈 High Performer' :
                              seller.order_count > 20 ? '🥉 Active Seller' : '📈 Rising Star';
                sellersData.push([index + 1, seller.name, seller.location, seller.order_count, status]);
            });

            const sellersSheet = XLSX.utils.aoa_to_sheet(sellersData);
            if (!sellersSheet['!cols']) sellersSheet['!cols'] = [];
            sellersSheet['!cols'][0] = { width: 8 };
            sellersSheet['!cols'][1] = { width: 25 };
            sellersSheet['!cols'][2] = { width: 20 };
            sellersSheet['!cols'][3] = { width: 10 };
            sellersSheet['!cols'][4] = { width: 15 };

            // Style header
            sellersSheet['A1'] = { v: 'FARMART - Top Sellers by Orders', t: 's', s: { font: { bold: true, sz: 14, color: { rgb: '22C55E' } } } };

            XLSX.utils.book_append_sheet(workbook, sellersSheet, 'Top Sellers');

            // Save the Excel file
            XLSX.writeFile(workbook, 'farmart_analytics_report.xlsx');
        }
    </script>
    </div>
</div>

<?= $this->endSection() ?>