<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Community Forum</h1>
                <p class="text-gray-600">Connect with fellow farmers and share your experiences</p>
            </div>
            <?php if (session()->get('user_id') && session()->get('user_role') !== 'admin'): ?>
                <a href="/forum/create" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors inline-flex items-center">
                    <i data-lucide="plus" class="w-5 h-5 inline mr-2"></i>
                    New Post
                </a>
            <?php elseif (!session()->has('user_id')): ?>
                <a href="/auth/login" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors inline-flex items-center">
                    <i data-lucide="log-in" class="w-5 h-5 inline mr-2"></i>
                    Login to Post
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Filters and Sorting -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <div class="flex-1 w-full sm:w-auto">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <form method="GET" action="/forum" class="inline-block w-full sm:w-auto">
                        <select name="category" onchange="this.form.submit()" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="all" <?= ($selected_category ?? 'all') === 'all' ? 'selected' : '' ?>>All Categories</option>
                            <?php foreach ($categories ?? [] as $cat): ?>
                                <option value="<?= esc($cat) ?>" <?= ($selected_category ?? 'all') === $cat ? 'selected' : '' ?>>
                                    <?= ucfirst(esc($cat)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($selected_sort)): ?>
                            <input type="hidden" name="sort" value="<?= esc($selected_sort) ?>">
                        <?php endif; ?>
                    </form>
                </div>
                <div class="flex-1 w-full sm:w-auto">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <form method="GET" action="/forum" class="inline-block w-full sm:w-auto">
                        <select name="sort" onchange="this.form.submit()" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="latest" <?= ($selected_sort ?? 'latest') === 'latest' ? 'selected' : '' ?>>Latest First</option>
                            <option value="oldest" <?= ($selected_sort ?? 'latest') === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                        </select>
                        <?php if (isset($selected_category) && $selected_category !== 'all'): ?>
                            <input type="hidden" name="category" value="<?= esc($selected_category) ?>">
                        <?php endif; ?>
                    </form>
                </div>
            </div>
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
            <div class="space-y-4 flex flex-col items-center">
            <?php foreach ($posts as $post): ?>
                    <?php $postData = htmlspecialchars(json_encode([
                        'id' => $post['id'],
                        'title' => $post['title'],
                        'author_name' => $post['author_name'] ?? '',
                        'author_id' => $post['user_id'] ?? null,
                        'created_at' => $post['created_at'] ?? '',
                        'content' => $post['content'] ?? '',
                        'image_url' => $post['image_url'] ?? null,
                        'likes' => $post['likes'] ?? 0,
                        'comment_count' => $post['comment_count'] ?? 0,
                    ]), ENT_QUOTES, 'UTF-8'); ?>

                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:border-gray-300 transition-colors w-full max-w-3xl">
                    <div class="p-4">
                        <!-- Post Header -->
                        <div class="mb-3">
                            <div class="flex items-center text-xs text-gray-500 mb-2">
                                <a href="/users/<?= $post['user_id'] ?>" class="font-medium text-gray-700 hover:underline"><?= esc($post['author_name']) ?></a>
                                <span class="mx-1">•</span>
                                <span><?= date('M d, Y', strtotime($post['created_at'])) ?></span>
                                <?php if (!empty($post['category'])): ?>
                                    <span class="mx-1">•</span>
                                    <span class="inline-block px-2 py-0.5 bg-primary/10 text-primary text-xs font-semibold rounded">
                                        <?= ucfirst(esc($post['category'])) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-2">
                                <a href="/forum/post/<?= $post['id'] ?>" class="hover:text-primary transition-colors">
                                    <?= esc($post['title']) ?>
                                </a>
                            </h2>
                        </div>

                            <!-- Image (if any) -->
                            <?php if (!empty($post['image_url'])): ?>
                                <div class="mb-4">
                                    <img src="<?= esc($post['image_url']) ?>" alt="<?= esc($post['title']) ?>" class="w-full h-64 object-cover rounded-lg">
                                </div>
                            <?php endif; ?>

                            <!-- Post Content Preview -->
                            <?php $plain = trim(strip_tags($post['content'])); ?>
                            <?php if (strlen($plain) >= 50): ?>
                                <div class="text-gray-700 text-sm mb-4">
                                    <?= esc(substr($plain, 0, 50)) ?>... <a href="#" class="text-primary view-more" data-post='<?= $postData ?>'>View more</a>
                                </div>
                            <?php else: ?>
                                <div class="text-gray-700 text-sm mb-4"><?= esc($plain) ?></div>
                            <?php endif; ?>

                        <!-- Reddit-style Action Buttons at Bottom -->
                        <div class="flex items-center gap-4 pt-3 border-t border-gray-100">
                            <!-- Like Button (Leaf Emoji) - Toggleable -->
                            <?php if (session()->get('user_id')): ?>
                                <form action="/forum/post/<?= $post['id'] ?>/like" method="POST" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="flex items-center gap-1.5 px-2 py-1.5 text-sm rounded transition-colors group <?= (isset($post['user_liked']) && $post['user_liked']) ? 'text-green-600 hover:bg-green-50' : 'text-gray-600 hover:bg-gray-100' ?>" title="<?= (isset($post['user_liked']) && $post['user_liked']) ? 'Click to unlike' : 'Click to like' ?>">
                                        <span class="text-base group-hover:scale-110 transition-transform">🍃</span>
                                        <span class="font-medium"><?= $post['likes'] ?? 0 ?></span>
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="flex items-center gap-1.5 px-2 py-1.5 text-sm text-gray-400">
                                    <span class="text-base">🍃</span>
                                    <span class="font-medium"><?= $post['likes'] ?? 0 ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- Comment Button (Cherry Emoji) -->
                            <a href="/forum/post/<?= $post['id'] ?>" class="open-post-modal flex items-center gap-1.5 px-2 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded transition-colors group" data-post='<?= $postData ?>'>
                                <span class="text-base group-hover:scale-110 transition-transform">🍒</span>
                                <span class="font-medium"><?= $post['comment_count'] ?? 0 ?></span>
                                <span class="hidden sm:inline">Comments</span>
                            </a>

                            <!-- Report Button -->
                            <?php if (session()->get('user_id') && session()->get('user_role') !== 'admin'): ?>
                                <button onclick="reportPost(<?= $post['id'] ?>, 'forum_post')" class="flex items-center gap-1.5 px-2 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded transition-colors group ml-auto" title="Report this post">
                                    <i data-lucide="flag" class="w-4 h-4 group-hover:text-red-500"></i>
                                    <span class="hidden sm:inline">Report</span>
                                </button>
                            <?php endif; ?>
                        </div>
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

<!-- Post Detail Modal (improved design: light blur backdrop, focused popup) -->
<div id="postModal" class="fixed inset-0 bg-black/10 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div id="postModalCard" class="bg-white rounded-lg max-w-3xl w-full p-0 overflow-hidden shadow-xl transform transition-all">
            <div class="p-4 border-b flex items-start justify-between">
                <div>
                    <h3 id="postModalTitle" class="text-lg font-semibold"></h3>
                    <div id="postModalMeta" class="text-xs text-gray-500"></div>
                </div>
                <button id="postModalClose" class="text-gray-500 hover:text-gray-800 ml-4 text-2xl leading-none">&times;</button>
            </div>
            <div id="postModalBody" class="p-4 max-h-[70vh] overflow-auto">
                <div id="postModalInner"></div>
            </div>
        </div>
    </div>
</div>

<script>
// small helper to escape text for HTML insertion
function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return String(unsafe)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

async function openPostModalFromData(data){
    try{
        const post = (typeof data === 'string') ? JSON.parse(data) : data;
        const id = post.id;

        const modal = document.getElementById('postModal');
        const card = document.getElementById('postModalCard');
        const inner = document.getElementById('postModalInner');

        // Show modal and animate in
        modal.classList.remove('hidden');
        card.classList.remove('animate__fadeOutUp');
        card.classList.add('animate__animated', 'animate__zoomIn');

        // populate modal header from embedded data so author link is available immediately
        const headerTitleEl = document.getElementById('postModalTitle');
        const headerMetaEl = document.getElementById('postModalMeta');
        if (headerTitleEl) headerTitleEl.innerText = post.title || '';
        if (headerMetaEl) {
            const date = post.created_at ? new Date(post.created_at) : null;
            const authorLink = post.author_id ? `<a href="/users/${post.author_id}" class="text-primary hover:underline">${escapeHtml(post.author_name || '')}</a>` : escapeHtml(post.author_name || '');
            headerMetaEl.innerHTML = authorLink + (date ? ' • ' + date.toLocaleString() : '') + (post.category ? ' • ' + escapeHtml(post.category) : '');
        }

        // Try fetching full post page so we can include comments and exact layout
        try {
            const resp = await fetch('/forum/post/' + encodeURIComponent(id));
            if (!resp.ok) throw new Error('Fetch failed');
            const html = await resp.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');


            // Select the main post container and comments container from the page
            const postContainer = doc.querySelector('.bg-white.rounded-lg.border');
            const commentsNode = doc.getElementById('comments');

            inner.innerHTML = '';
            if (postContainer) {
                // Clone post and remove its internal title/meta to avoid duplicate info
                const clone = postContainer.cloneNode(true);
                const innerTitle = clone.querySelector('h1, h2, h3');
                if (innerTitle) innerTitle.remove();
                const innerMeta = clone.querySelector('.text-xs.text-gray-500');
                if (innerMeta) innerMeta.remove();
                inner.appendChild(clone);
            }
            if (commentsNode) inner.appendChild(commentsNode.cloneNode(true));

            // Re-initialize lucide icons inside modal
            lucide.createIcons();

        } catch (err) {
            // Fallback: render basic data with mentions linked (header already populated above)
            // ensure meta includes linked author (already set from embedded data)
            let raw = post.content || '';
            let safe = escapeHtml(raw);
            safe = safe.replace(/@([a-zA-Z0-9_\-\.]+)/g, function(match, u){ return `<a href="/users/${u}" class="text-primary">@${u}</a>`; });
            inner.innerHTML = `<div class="mb-4 text-gray-800">${safe}</div>`;
            console.warn('Could not fetch full post, using embedded data', err);
        }

        // Wire close button
        document.getElementById('postModalClose').onclick = closePostModal;

    } catch(e) {
        console.error('Invalid post data for modal', e, data);
    }
}

function closePostModal(){
    const modal = document.getElementById('postModal');
    const card = document.getElementById('postModalCard');
    // animate out
    card.classList.remove('animate__zoomIn');
    card.classList.add('animate__animated', 'animate__fadeOutUp');
    setTimeout(() => {
        modal.classList.add('hidden');
        card.classList.remove('animate__fadeOutUp');
    }, 260);
}

document.addEventListener('DOMContentLoaded', function(){
    // Wire view-more links
    document.querySelectorAll('.view-more').forEach(el => {
        el.addEventListener('click', function(e){
            e.preventDefault();
            const data = el.getAttribute('data-post');
            openPostModalFromData(data);
        });
    });

    // Wire comment/open-post buttons
    document.querySelectorAll('.open-post-modal').forEach(el => {
        el.addEventListener('click', function(e){
            e.preventDefault();
            const data = el.getAttribute('data-post');
            openPostModalFromData(data);
        });
    });

    // Close modal when clicking outside card
    document.getElementById('postModal').addEventListener('click', function(e){
        if (e.target.id === 'postModal') closePostModal();
    });
});
</script>

*** End Patch

<?= $this->endSection() ?>