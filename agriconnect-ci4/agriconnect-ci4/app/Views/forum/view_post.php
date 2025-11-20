<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="/forum" class="inline-flex items-center text-primary hover:text-primary-hover transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Forum
            </a>
        </div>

        <!-- Post Content -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-8">
            <div class="p-8">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            <?= esc($post['title']) ?>
                        </h1>

                        <div class="flex items-center text-sm text-gray-600 mb-4">
                            <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                            <span class="mr-4 font-semibold"><?= esc($post['author_name']) ?></span>
                            <?php if (!empty($post['author_role'])): ?>
                                <span class="inline-block px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded mr-4">
                                    <?= ucfirst(esc($post['author_role'])) ?>
                                </span>
                            <?php endif; ?>
                            <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                            <span class="mr-4"><?= date('M d, Y', strtotime($post['created_at'])) ?></span>
                            <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                            <span class="mr-4"><?= date('H:i', strtotime($post['created_at'])) ?></span>
                            <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                            <span><?= $post['likes'] ?? 0 ?> likes</span>
                        </div>
                    </div>

                    <?php if (session()->get('user_id')): ?>
                        <div class="flex gap-2">
                            <form action="/forum/post/<?= $post['id'] ?>/like" method="POST" class="inline">
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                                    <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                                    Like
                                </button>
                            </form>

                            <?php if (session()->get('user_role') !== 'admin'): ?>
                            <button onclick="reportPost(<?= $post['id'] ?>, 'forum_post')" class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors" title="Report this post">
                                <i data-lucide="flag" class="w-4 h-4 mr-1"></i>
                                Report
                            </button>
                            <?php endif; ?>

                            <?php if (session()->get('user_id') == $post['user_id'] || session()->get('user_role') == 'admin'): ?>
                                <form action="/forum/post/<?= $post['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?')">
                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                        Delete
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($post['category'])): ?>
                    <div class="mb-4">
                        <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded">
                            <?= ucfirst(esc($post['category'])) ?>
                        </span>
                    </div>
                <?php endif; ?>

                <div class="prose prose-lg max-w-none">
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                        <?= nl2br(esc($post['content'])) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
            <div class="p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">
                    Comments (<?= count($comments) ?>)
                </h3>

                <?php if (empty($comments)): ?>
                    <div class="text-center py-8">
                        <i data-lucide="message-square-off" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-600">No comments yet. Be the first to comment!</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-6 mb-8">
                        <?php foreach ($comments as $comment): ?>
                            <div class="border-l-4 border-gray-200 pl-6">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center">
                                        <span class="font-semibold text-gray-900 mr-2"><?= esc($comment['author_name']) ?></span>
                                        <?php if (!empty($comment['author_role'])): ?>
                                            <span class="inline-block px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded">
                                                <?= ucfirst(esc($comment['author_role'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        <?= date('M d, Y H:i', strtotime($comment['created_at'])) ?>
                                    </span>
                                </div>
                                <div class="text-gray-700 whitespace-pre-line">
                                    <?= nl2br(esc($comment['comment'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Add Comment Form -->
                <?php if (session()->get('user_id')): ?>
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Add a Comment</h4>
                        <form action="/forum/post/<?= $post['id'] ?>/comment" method="POST">
                            <div class="mb-4">
                                <textarea
                                    name="comment"
                                    rows="4"
                                    placeholder="Share your thoughts..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                    required
                                ></textarea>
                            </div>
                            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                                <i data-lucide="send" class="w-4 h-4 inline mr-2"></i>
                                Post Comment
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="border-t border-gray-200 pt-6 text-center">
                        <p class="text-gray-600 mb-4">Login to join the discussion</p>
                        <a href="/auth/login" class="inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                            <i data-lucide="log-in" class="w-4 h-4 inline mr-2"></i>
                            Login
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
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