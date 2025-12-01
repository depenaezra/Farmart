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
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden mb-8">
            <div class="p-6">
                <!-- Post Header -->
                <div class="mb-4">
                    <div class="flex items-center text-xs text-gray-500 mb-3">
                        <a href="/users/<?= $post['user_id'] ?>" class="font-medium text-gray-700 hover:underline"><?= esc($post['author_name']) ?></a>
                        <?php if (!empty($post['author_role'])): ?>
                            <span class="ml-2 inline-block px-2 py-0.5 bg-primary/10 text-primary text-xs font-semibold rounded">
                                <?= ucfirst(esc($post['author_role'])) ?>
                            </span>
                        <?php endif; ?>
                        <span class="mx-1">•</span>
                        <span><?= date('M d, Y', strtotime($post['created_at'])) ?></span>
                        <span class="mx-1">•</span>
                        <span><?= date('H:i', strtotime($post['created_at'])) ?></span>
                        <?php if (!empty($post['category'])): ?>
                            <span class="mx-1">•</span>
                            <span class="inline-block px-2 py-0.5 bg-primary/10 text-primary text-xs font-semibold rounded">
                                <?= ucfirst(esc($post['category'])) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">
                        <?= esc($post['title']) ?>
                    </h1>
                </div>

                <!-- Post Content -->
                <div class="text-gray-700 leading-relaxed whitespace-pre-line mb-4">
                    <?= nl2br(esc($post['content'])) ?>
                </div>

                <!-- Post Images -->
                <?php
                $images = [];
                if (!empty($post['image_url'])) {
                    $decoded = json_decode($post['image_url'], true);
                    if (is_array($decoded)) {
                        $images = $decoded;
                    } else {
                        // Backward compatibility for single image
                        $images = [$post['image_url']];
                    }
                }
                ?>
                <?php if (!empty($images)): ?>
                    <div class="mb-4">
                        <?php if (count($images) === 1): ?>
                            <img src="/<?= esc($images[0]) ?>" alt="<?= esc($post['title']) ?>" class="w-full max-w-2xl mx-auto rounded-lg shadow-md">
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <?php foreach ($images as $index => $image): ?>
                                    <img src="/<?= esc($image) ?>" alt="<?= esc($post['title']) ?> - Image <?= $index + 1 ?>" class="w-full h-48 object-cover rounded-lg shadow-md">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Reddit-style Action Buttons at Bottom -->
                <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
                    <!-- Like Button (Leaf Emoji) - Toggleable -->
                    <?php if (session()->get('user_id')): ?>
                        <form action="/forum/post/<?= $post['id'] ?>/like" method="POST" class="inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded transition-colors group <?= (isset($post['user_liked']) && $post['user_liked']) ? 'text-green-600 hover:bg-green-50' : 'text-gray-700 hover:bg-gray-100' ?>" title="<?= (isset($post['user_liked']) && $post['user_liked']) ? 'Click to unlike' : 'Click to like' ?>">
                                <span class="text-lg group-hover:scale-110 transition-transform">🍃</span>
                                <span><?= $post['likes'] ?? 0 ?></span>
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-400">
                            <span class="text-lg">🍃</span>
                            <span><?= $post['likes'] ?? 0 ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Comment Button (Cherry Emoji) -->
                    <a href="#comments" class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded transition-colors group">
                        <span class="text-lg group-hover:scale-110 transition-transform">🍒</span>
                        <span><?= count($comments) ?></span>
                        <span>Comments</span>
                    </a>

                    <!-- Report Button -->
                    <?php if (session()->get('user_id') && session()->get('user_role') !== 'admin'): ?>
                        <button onclick="reportPost(<?= $post['id'] ?>, 'forum_post')" class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded transition-colors group ml-auto" title="Report this post">
                            <i data-lucide="flag" class="w-5 h-5 group-hover:text-red-500"></i>
                            <span>Report</span>
                        </button>
                    <?php endif; ?>

                    <!-- Delete Button (for post author/admin) -->
                    <?php if (session()->get('user_id') && (session()->get('user_id') == $post['user_id'] || session()->get('user_role') == 'admin')): ?>
                        <form action="/forum/post/<?= $post['id'] ?>/delete" method="POST" class="inline ml-auto swal-confirm-form" data-confirm="Are you sure you want to delete this post?">
                            <?= csrf_field() ?>
                            <button type="submit" class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded transition-colors">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                <span>Delete</span>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div id="comments" class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">
                    Comments (<?= count($comments) ?>)
                </h3>

                <div id="commentsList" class="space-y-6 mb-8">
                    <?php if (empty($comments)): ?>
                        <div class="text-center py-8">
                            <i data-lucide="message-square-off" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                            <p class="text-gray-600">No comments yet. Be the first to comment!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <?= view('forum/_comment', ['comment' => $comment]) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Add Comment Form -->
                <?php if (session()->get('user_id')): ?>
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Add a Comment</h4>
                        <form id="commentForm" action="/forum/post/<?= $post['id'] ?>/comment" method="POST">
                            <?= csrf_field() ?>
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
            try {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message || 'Report submitted',
                    showCloseButton: true,
                    showClass: { popup: 'animate__animated animate__bounceIn' },
                    hideClass: { popup: 'animate__animated animate__fadeOutUp' },
                    timer: 2000
                });
            } catch (e) {
                alert(data.message);
            }
            closeReportModal();
        } else {
            try {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'An error occurred',
                    showCloseButton: true
                });
            } catch (e) {
                alert('Error: ' + data.message);
            }
        }
    })
    .catch(error => {
        try {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred. Please try again.',
                showCloseButton: true
            });
        } catch (e) {
            alert('An error occurred. Please try again.');
        }
    });
});
</script>

<script>
// AJAX comment submission
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('commentForm');
    if (!commentForm) return;

    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const url = form.action;
        const fd = new FormData(form);

        fetch(url, {
            method: 'POST',
            body: fd,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data && data.success && data.comment) {
                const commentsList = document.getElementById('commentsList');
                if (commentsList && data.comment) {
                    // Create comment HTML
                    const commentDiv = document.createElement('div');
                    commentDiv.className = 'border-l-4 border-gray-200 pl-6';
                    commentDiv.innerHTML = `
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center">
                                <a href="/users/${data.comment.user_id}" class="font-semibold text-gray-900 mr-2 hover:underline">${data.comment.author_name}</a>
                                ${data.comment.author_role ? `<span class="inline-block px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded">${data.comment.author_role.charAt(0).toUpperCase() + data.comment.author_role.slice(1)}</span>` : ''}
                            </div>
                            <span class="text-sm text-gray-500">${new Date(data.comment.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' })}</span>
                        </div>
                        <div class="text-gray-700 whitespace-pre-line">
                            ${data.comment.comment.replace(/\n/g, '<br>')}
                        </div>
                    `;
                    commentsList.appendChild(commentDiv);
                }

                // Update comments count
                const heading = document.querySelector('#comments h3');
                if (heading && data.comment_count !== undefined) {
                    heading.textContent = 'Comments (' + data.comment_count + ')';
                }

                // Clear textarea
                form.reset();

                // Show success message briefly
                const button = form.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                button.innerHTML = '<i data-lucide="check" class="w-4 h-4 inline mr-2"></i>Posted!';
                button.disabled = true;
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 1500);
            } else {
                // Silently handle error - comment was still saved to database
                console.log('Comment submission error:', data ? data.message : 'Unknown error');
            }
        })
        .catch(err => {
            console.log('Comment submission network error:', err);
        });
    });
});
</script>

<?= $this->endSection() ?>