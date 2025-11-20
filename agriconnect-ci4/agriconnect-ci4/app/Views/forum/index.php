<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Community Forum</h1>
                <p class="text-gray-600">Connect with fellow farmers and share your experiences</p>
            </div>
            <?php if (session()->get('user_id') && session()->get('user_role') !== 'admin'): ?>
                <a href="/forum/create" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                    <i data-lucide="plus" class="w-5 h-5 inline mr-2"></i>
                    New Post
                </a>
            <?php elseif (!session()->has('user_id')): ?>
                <a href="/auth/login" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                    <i data-lucide="log-in" class="w-5 h-5 inline mr-2"></i>
                    Login to Post
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Forum Stats -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-primary mb-1"><?= count($posts) ?></div>
                <div class="text-sm text-gray-600">Total Posts</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-primary mb-1">
                    <?php
                    $totalComments = 0;
                    foreach ($posts as $post) {
                        $totalComments += $post['comment_count'] ?? 0;
                    }
                    echo $totalComments;
                    ?>
                </div>
                <div class="text-sm text-gray-600">Total Comments</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-primary mb-1">
                    <?php
                    $activeUsers = count(array_unique(array_column($posts, 'user_id')));
                    echo $activeUsers;
                    ?>
                </div>
                <div class="text-sm text-gray-600">Active Members</div>
            </div>
        </div>
    </div>

    <!-- Posts List -->
    <?php if (empty($posts)): ?>
        <div class="text-center py-12">
            <i data-lucide="message-square-off" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <p class="text-xl text-gray-600">No posts yet</p>
            <p class="text-gray-500 mt-2">Be the first to start a discussion!</p>
            <?php if (session()->get('user_id')): ?>
                <a href="/forum/create" class="inline-block mt-4 bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                    Create First Post
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($posts as $post): ?>
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-gray-900 mb-2">
                                    <a href="/forum/post/<?= $post['id'] ?>" class="hover:text-primary transition-colors">
                                        <?= esc($post['title']) ?>
                                    </a>
                                </h2>

                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                                    <span class="mr-4"><?= esc($post['author_name']) ?></span>
                                    <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                                    <span class="mr-4"><?= date('M d, Y', strtotime($post['created_at'])) ?></span>
                                    <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                                    <span class="mr-4"><?= date('H:i', strtotime($post['created_at'])) ?></span>
                                    <i data-lucide="message-circle" class="w-4 h-4 mr-1"></i>
                                    <span class="mr-4"><?= $post['comment_count'] ?? 0 ?> comments</span>
                                    <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                                    <span><?= $post['likes'] ?? 0 ?> likes</span>
                                </div>
                            </div>

                            <div class="ml-4 flex gap-2">
                                <a href="/forum/post/<?= $post['id'] ?>" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                    View Post
                                </a>
                                <?php if (session()->get('user_id') && session()->get('user_role') !== 'admin'): ?>
                                <button onclick="reportPost(<?= $post['id'] ?>, 'forum_post')" class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors" title="Report this post">
                                    <i data-lucide="flag" class="w-4 h-4"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="text-gray-700 line-clamp-3">
                            <?= esc(substr($post['content'], 0, 300)) ?>...
                        </div>

                        <?php if (!empty($post['category'])): ?>
                            <div class="mt-3">
                                <span class="inline-block px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded">
                                    <?= ucfirst(esc($post['category'])) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Report Modal -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold mb-4">Report Content</h3>
            <form id="reportForm">
                <input type="hidden" id="reportedType" name="reported_type">
                <input type="hidden" id="reportedId" name="reported_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                    <select id="reportReason" name="reason" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                        <option value="">Select a reason</option>
                        <option value="spam">Spam</option>
                        <option value="inappropriate">Inappropriate content</option>
                        <option value="harassment">Harassment</option>
                        <option value="false_information">False information</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (optional)</label>
                    <textarea id="reportDescription" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Provide more details..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeReportModal()" class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function reportPost(id, type) {
    document.getElementById('reportedId').value = id;
    document.getElementById('reportedType').value = type;
    document.getElementById('reportModal').classList.remove('hidden');
}

function closeReportModal() {
    document.getElementById('reportModal').classList.add('hidden');
    document.getElementById('reportForm').reset();
}

document.getElementById('reportForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/report', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeReportModal();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('An error occurred. Please try again.');
    });
});
</script>

<?= $this->endSection() ?>