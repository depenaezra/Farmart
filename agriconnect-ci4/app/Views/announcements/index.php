<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Announcements</h1>
        <p class="text-gray-600">Stay updated with the latest news and announcements from Farmart</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Category Filter -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
                <div class="flex flex-wrap gap-2">
                    <a href="/announcements" class="px-4 py-2 rounded-lg font-semibold <?= empty($current_category) ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        All
                    </a>
                    <a href="/announcements?category=general" class="px-4 py-2 rounded-lg font-semibold <?= $current_category === 'general' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        General
                    </a>
                    <a href="/announcements?category=weather" class="px-4 py-2 rounded-lg font-semibold <?= $current_category === 'weather' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        Weather
                    </a>
                    <a href="/announcements?category=market" class="px-4 py-2 rounded-lg font-semibold <?= $current_category === 'market' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        Market
                    </a>
                    <a href="/announcements?category=policy" class="px-4 py-2 rounded-lg font-semibold <?= $current_category === 'policy' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        Policy
                    </a>
                </div>
            </div>

            <!-- Announcements List -->
            <?php if (empty($announcements)): ?>
                <div class="text-center py-12">
                    <i data-lucide="megaphone-off" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <p class="text-xl text-gray-600">No announcements found</p>
                    <p class="text-gray-500 mt-2">Check back later for updates</p>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <div class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded mb-2">
                                            <?= ucfirst(esc($announcement['category'] ?? 'general')) ?>
                                        </div>
                                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                            <a href="/announcements/<?= $announcement['id'] ?>" class="hover:text-primary transition-colors">
                                                <?= esc($announcement['title']) ?>
                                            </a>
                                        </h2>
                                        <div class="flex items-center text-sm text-gray-600 mb-3">
                                            <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                                            <span class="mr-4"><?= esc($announcement['creator_name']) ?></span>
                                            <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                                            <span class="mr-4"><?= date('M d, Y', strtotime($announcement['created_at'])) ?></span>
                                            <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                                            <span><?= date('H:i', strtotime($announcement['created_at'])) ?></span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <a href="/announcements/<?= $announcement['id'] ?>" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                                            <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                            Read More
                                        </a>
                                    </div>
                                </div>

                                <div class="text-gray-700 line-clamp-3">
                                    <?= esc(substr($announcement['content'], 0, 300)) ?>...
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Analytics Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Real-time News Articles -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <div class="bg-primary text-white px-4 py-3 flex items-center">
                    <i data-lucide="newspaper" class="w-5 h-5 mr-2"></i>
                    <h3 class="font-bold">Latest Agriculture News</h3>
                </div>
                <div class="p-4 space-y-4" id="news-articles">
                    <?php
                    // API Integration Guide - Replace with actual API calls to these credible sources:
                    // 1. Philippine News Agency (PNA): https://www.pna.gov.ph/
                    // 2. Department of Agriculture RSS/API: https://www.da.gov.ph/
                    // 3. Manila Bulletin Agriculture: https://mb.com.ph/category/agriculture/
                    // 4. BusinessWorld Agriculture: https://www.bworldonline.com/category/economy/agriculture/
                    
                    // Example implementation:
                    // $news_articles = $this->newsService->fetchFromSources([
                    //     'https://www.pna.gov.ph/rss/agriculture',
                    //     'https://www.da.gov.ph/feed/',
                    // ]);
                    
                    $news_articles = [
                        [
                            'title' => 'DA Launches Rice Resiliency Program',
                            'source' => 'Philippine News Agency',
                            'time' => '2 hours ago',
                            'url' => 'https://www.pna.gov.ph/'
                        ],
                        [
                            'title' => 'PhilRice Develops Climate-Resistant Rice Varieties',
                            'source' => 'Manila Bulletin',
                            'time' => '5 hours ago',
                            'url' => 'https://mb.com.ph/category/agriculture/'
                        ],
                        [
                            'title' => 'Coconut Farmers to Receive Production Support',
                            'source' => 'BusinessWorld',
                            'time' => '8 hours ago',
                            'url' => 'https://www.bworldonline.com/category/economy/agriculture/'
                        ],
                        [
                            'title' => 'BFAR Reports Increase in Aquaculture Production',
                            'source' => 'Philippine News Agency',
                            'time' => '1 day ago',
                            'url' => 'https://www.pna.gov.ph/'
                        ]
                    ];
                    ?>
                    <?php foreach ($news_articles as $news): ?>
                        <div class="border-b border-gray-200 pb-3 last:border-b-0 last:pb-0">
                            <a href="<?= esc($news['url']) ?>" target="_blank" class="block hover:text-primary transition-colors">
                                <h4 class="font-semibold text-gray-900 mb-1 text-sm"><?= esc($news['title']) ?></h4>
                                <div class="flex items-center justify-between text-xs text-gray-600">
                                    <span class="flex items-center">
                                        <i data-lucide="rss" class="w-3 h-3 mr-1"></i>
                                        <?= esc($news['source']) ?>
                                    </span>
                                    <span><?= esc($news['time']) ?></span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                    <button onclick="refreshNews()" class="text-sm text-primary hover:text-primary-hover font-semibold flex items-center w-full justify-center">
                        <i data-lucide="refresh-cw" class="w-4 h-4 mr-1"></i>
                        Refresh News
                    </button>
                </div>
            </div>

            <!-- Agricultural Organizations -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <div class="bg-primary text-white px-4 py-3 flex items-center">
                    <i data-lucide="building-2" class="w-5 h-5 mr-2"></i>
                    <h3 class="font-bold">Agricultural Organizations</h3>
                </div>
                <div class="p-4 space-y-3">
                    <?php
                    // Official Philippine Agricultural Organizations
                    // Database query example: $organizations = $this->orgService->getRecommendedOrgs();
                    $organizations = [
                        [
                            'name' => 'Department of Agriculture',
                            'type' => 'Government',
                            'contact' => 'da.gov.ph',
                            'url' => 'https://www.da.gov.ph/'
                        ],
                        [
                            'name' => 'Philippine Rice Research Institute',
                            'type' => 'Government',
                            'contact' => 'philrice.gov.ph',
                            'url' => 'https://www.philrice.gov.ph/'
                        ],
                        [
                            'name' => 'Agricultural Training Institute',
                            'type' => 'Education',
                            'contact' => 'ati.da.gov.ph',
                            'url' => 'https://www.ati.da.gov.ph/'
                        ],
                        [
                            'name' => 'Bureau of Plant Industry',
                            'type' => 'Government',
                            'contact' => 'bpi.da.gov.ph',
                            'url' => 'https://bpi.da.gov.ph/'
                        ],
                        [
                            'name' => 'Bureau of Fisheries & Aquatic Resources',
                            'type' => 'Government',
                            'contact' => 'bfar.da.gov.ph',
                            'url' => 'https://www.bfar.da.gov.ph/'
                        ],
                        [
                            'name' => 'Philippine Carabao Center',
                            'type' => 'Government',
                            'contact' => 'pcc.gov.ph',
                            'url' => 'https://pcc.gov.ph/'
                        ]
                    ];
                    ?>
                    <?php foreach ($organizations as $org): ?>
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-primary transition-colors">
                            <a href="<?= esc($org['url']) ?>" target="_blank" class="block">
                                <h4 class="font-semibold text-gray-900 mb-1 text-sm"><?= esc($org['name']) ?></h4>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="inline-block px-2 py-1 bg-primary/10 text-primary rounded">
                                        <?= esc($org['type']) ?>
                                    </span>
                                    <span class="text-gray-600 flex items-center">
                                        <i data-lucide="external-link" class="w-3 h-3 mr-1"></i>
                                        <?= esc($org['contact']) ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Government Policy Updates -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <div class="bg-primary text-white px-4 py-3 flex items-center">
                    <i data-lucide="scroll-text" class="w-5 h-5 mr-2"></i>
                    <h3 class="font-bold">Policy Updates</h3>
                </div>
                <div class="p-4 space-y-4" id="policy-updates">
                    <?php
                    // Philippine Government Policy Integration
                    // Fetch from: DA Official Gazette, Department of Agriculture Memorandums
                    // Example: $policies = $this->policyService->fetchLatestPolicies();
                    
                    $policies = [
                        [
                            'title' => 'Rice Competitiveness Enhancement Fund (RCEF)',
                            'agency' => 'DA-PhilRice',
                            'status' => 'Active',
                            'date' => '2025-12-01',
                            'url' => 'https://www.da.gov.ph/'
                        ],
                        [
                            'title' => 'Expanded SURE Aid and Recovery Program',
                            'agency' => 'DA',
                            'status' => 'Active',
                            'date' => '2025-11-28',
                            'url' => 'https://www.da.gov.ph/'
                        ],
                        [
                            'title' => 'Plant Quarantine Service Guidelines Update',
                            'agency' => 'DA-BPI',
                            'status' => 'Active',
                            'date' => '2025-11-25',
                            'url' => 'https://bpi.da.gov.ph/'
                        ],
                        [
                            'title' => 'Livestock Registration System Enhancement',
                            'agency' => 'DA-BAI',
                            'status' => 'Pending',
                            'date' => '2025-11-20',
                            'url' => 'https://bai.da.gov.ph/'
                        ],
                        [
                            'title' => 'Organic Agriculture Program Implementation',
                            'agency' => 'DA-ATI',
                            'status' => 'Active',
                            'date' => '2025-11-15',
                            'url' => 'https://www.ati.da.gov.ph/'
                        ]
                    ];
                    ?>
                    <?php foreach ($policies as $policy): ?>
                        <div class="border-b border-gray-200 pb-3 last:border-b-0 last:pb-0">
                            <a href="<?= esc($policy['url']) ?>" target="_blank" class="block hover:text-primary transition-colors">
                                <div class="flex items-start justify-between mb-1">
                                    <h4 class="font-semibold text-gray-900 text-sm flex-1"><?= esc($policy['title']) ?></h4>
                                    <span class="inline-block px-2 py-0.5 text-xs rounded ml-2 <?= $policy['status'] === 'Active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                        <?= esc($policy['status']) ?>
                                    </span>
                                </div>
                                <div class="flex items-center text-xs text-gray-600">
                                    <span class="mr-3 flex items-center">
                                        <i data-lucide="building" class="w-3 h-3 mr-1"></i>
                                        <?= esc($policy['agency']) ?>
                                    </span>
                                    <span class="flex items-center">
                                        <i data-lucide="calendar-days" class="w-3 h-3 mr-1"></i>
                                        <?= date('M d, Y', strtotime($policy['date'])) ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                    <button onclick="refreshPolicies()" class="text-sm text-primary hover:text-primary-hover font-semibold flex items-center w-full justify-center">
                        <i data-lucide="refresh-cw" class="w-4 h-4 mr-1"></i>
                        Refresh Policies
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Real-time news refresh function
async function refreshNews() {
    const newsContainer = document.getElementById('news-articles');
    const button = event.target.closest('button');
    const icon = button.querySelector('[data-lucide="refresh-cw"]');
    
    // Add spinning animation
    icon.style.animation = 'spin 1s linear infinite';
    
    try {
        // Replace with actual API endpoint
        // const response = await fetch('/api/news/latest');
        // const data = await response.json();
        
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // Mock updated news
        console.log('News refreshed at:', new Date().toLocaleTimeString());
        
        // In production, update the DOM with new data
        // newsContainer.innerHTML = renderNewsArticles(data);
        
    } catch (error) {
        console.error('Error fetching news:', error);
    } finally {
        icon.style.animation = '';
    }
}

// Real-time policy updates refresh
async function refreshPolicies() {
    const policyContainer = document.getElementById('policy-updates');
    const button = event.target.closest('button');
    const icon = button.querySelector('[data-lucide="refresh-cw"]');
    
    // Add spinning animation
    icon.style.animation = 'spin 1s linear infinite';
    
    try {
        // Replace with actual API endpoint
        // const response = await fetch('/api/policies/latest');
        // const data = await response.json();
        
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // Mock updated policies
        console.log('Policies refreshed at:', new Date().toLocaleTimeString());
        
        // In production, update the DOM with new data
        // policyContainer.innerHTML = renderPolicyUpdates(data);
        
    } catch (error) {
        console.error('Error fetching policies:', error);
    } finally {
        icon.style.animation = '';
    }
}

// Auto-refresh every 5 minutes
setInterval(() => {
    refreshNews();
    refreshPolicies();
}, 300000);
</script>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<?= $this->endSection() ?>