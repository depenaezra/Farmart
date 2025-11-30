<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Facebook-style Newsfeed Layout -->
<div class="bg-mint-light min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">
            
            <!-- Page Header - Compact -->
            <div class="mb-4">
                <h1 class="text-2xl font-bold text-gray-900">Community Forum</h1>
            </div>

            <!-- Create Post Box (Facebook-style "What's on your mind?") -->
            <?php if (session()->get('user_id') && session()->get('user_role') !== 'admin'): ?>
                <div class="bg-white rounded-lg shadow mb-4 p-4">
                    <a href="/forum/create" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="user" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div class="flex-1 bg-gray-100 rounded-full px-4 py-2.5 text-gray-500 cursor-pointer hover:bg-gray-200 transition-colors">
                            What's on your mind, <?= esc(session()->get('user_name') ?? 'there') ?>?
                        </div>
                    </a>
                    <div class="border-t border-gray-200 mt-3 pt-3 flex items-center justify-around">
                        <a href="/forum/create" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors flex-1 justify-center">
                            <i data-lucide="image" class="w-5 h-5 text-green-600"></i>
                            <span class="text-sm font-medium text-gray-700">Photo</span>
                        </a>
                        <a href="/forum/create" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors flex-1 justify-center">
                            <i data-lucide="message-square" class="w-5 h-5 text-blue-600"></i>
                            <span class="text-sm font-medium text-gray-700">Post</span>
                        </a>
                    </div>
                </div>
            <?php elseif (!session()->has('user_id')): ?>
                <div class="bg-white rounded-lg shadow mb-4 p-4 text-center">
                    <p class="text-gray-600 mb-3">Join the conversation!</p>
                    <a href="/auth/login" class="inline-flex items-center bg-primary text-white px-6 py-2.5 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                        <i data-lucide="log-in" class="w-4 h-4 mr-2"></i>
                        Login to Post
                    </a>
                </div>
            <?php endif; ?>

            <!-- Filters - Compact Facebook-style -->
            <div class="bg-white rounded-lg shadow mb-4 p-3">
                <div class="flex items-center gap-3 overflow-x-auto">
                    <form method="GET" action="/forum" class="flex items-center gap-2">
                        <select name="category" onchange="this.form.submit()" class="text-sm px-3 py-1.5 border border-gray-300 rounded-full focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-50">
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
                    <div class="h-6 w-px bg-gray-300"></div>
                    <form method="GET" action="/forum" class="flex items-center gap-2">
                        <select name="sort" onchange="this.form.submit()" class="text-sm px-3 py-1.5 border border-gray-300 rounded-full focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-50">
                            <option value="latest" <?= ($selected_sort ?? 'latest') === 'latest' ? 'selected' : '' ?>>Latest</option>
                            <option value="oldest" <?= ($selected_sort ?? 'latest') === 'oldest' ? 'selected' : '' ?>>Oldest</option>
                        </select>
                        <?php if (isset($selected_category) && $selected_category !== 'all'): ?>
                            <input type="hidden" name="category" value="<?= esc($selected_category) ?>">
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Posts Feed -->
            <?php if (empty($posts)): ?>
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <i data-lucide="message-square-off" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <p class="text-xl text-gray-600 mb-2">No posts yet</p>
                    <p class="text-gray-500 mb-4">Be the first to start a discussion!</p>
                    <?php if (session()->get('user_id')): ?>
                        <a href="/forum/create" class="inline-flex items-center bg-primary text-white px-6 py-2.5 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            Create First Post
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="space-y-4">
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

                        <!-- Facebook-style Post Card -->
                        <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                            <!-- Post Header -->
                            <div class="p-4 pb-3">
                                <div class="flex items-center gap-3">
                                    <!-- User Avatar -->
                                    <a href="/users/<?= $post['user_id'] ?>" class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-green-600 flex items-center justify-center text-white font-semibold">
                                            <?= strtoupper(substr($post['author_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                    </a>
                                    <!-- User Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <a href="/users/<?= $post['user_id'] ?>" class="font-semibold text-gray-900 hover:underline truncate">
                                                <?= esc($post['author_name']) ?>
                                            </a>
                                            <?php if (!empty($post['category'])): ?>
                                                <span class="inline-flex items-center px-2 py-0.5 bg-primary/10 text-primary text-xs font-medium rounded-full">
                                                    <?= ucfirst(esc($post['category'])) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?php
                                            $time = strtotime($post['created_at']);
                                            $diff = time() - $time;
                                            if ($diff < 60) echo 'Just now';
                                            elseif ($diff < 3600) echo floor($diff / 60) . 'm';
                                            elseif ($diff < 86400) echo floor($diff / 3600) . 'h';
                                            elseif ($diff < 604800) echo floor($diff / 86400) . 'd';
                                            else echo date('M d', $time);
                                            ?>
                                        </div>
                                    </div>
                                    <!-- More Options -->
                                    <?php if (session()->get('user_id') && session()->get('user_role') !== 'admin'): ?>
                                        <button onclick="reportPost(<?= $post['id'] ?>, 'forum_post')" class="p-2 hover:bg-gray-100 rounded-full transition-colors" title="Report">
                                            <i data-lucide="more-horizontal" class="w-5 h-5 text-gray-500"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Post Title & Content -->
                            <div class="px-4 pb-3">
                                <h2 class="text-base font-semibold text-gray-900 mb-2">
                                    <?= esc($post['title']) ?>
                                </h2>
                                <?php $plain = trim(strip_tags($post['content'])); ?>
                                <?php if (strlen($plain) > 200): ?>
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        <?= esc(substr($plain, 0, 200)) ?>...
                                        <a href="#" class="text-primary font-medium view-more" data-post='<?= $postData ?>'>See more</a>
                                    </p>
                                <?php else: ?>
                                    <p class="text-gray-700 text-sm leading-relaxed"><?= esc($plain) ?></p>
                                <?php endif; ?>
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
                                <div class="cursor-pointer open-post-modal" data-post='<?= $postData ?>'>
                                    <?php if (count($images) === 1): ?>
                                        <img src="/<?= esc($images[0]) ?>" alt="<?= esc($post['title']) ?>" class="w-full max-h-96 object-cover">
                                    <?php else: ?>
                                        <div class="grid grid-cols-2 gap-1">
                                            <?php foreach (array_slice($images, 0, 4) as $index => $image): ?>
                                                <div class="relative <?= count($images) > 4 && $index === 3 ? 'overlay-container' : '' ?>">
                                                    <img src="/<?= esc($image) ?>" alt="<?= esc($post['title']) ?> - Image <?= $index + 1 ?>" class="w-full h-32 object-cover rounded">
                                                    <?php if (count($images) > 4 && $index === 3): ?>
                                                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded">
                                                            <span class="text-white font-semibold">+<?= count($images) - 4 ?> more</span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Engagement Stats -->
                            <div class="px-4 py-2 flex items-center justify-between text-sm text-gray-500 border-b border-gray-200">
                                <div class="flex items-center gap-1">
                                    <?php if (($post['likes'] ?? 0) > 0): ?>
                                        <span class="text-base">🍃</span>
                                        <span><?= $post['likes'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-3">
                                    <?php if (($post['comment_count'] ?? 0) > 0): ?>
                                        <span><?= $post['comment_count'] ?> comment<?= $post['comment_count'] != 1 ? 's' : '' ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Action Buttons (Facebook-style) -->
                            <div class="px-2 py-1 flex items-center justify-around">
                                <!-- Like Button -->
                                <?php if (session()->get('user_id')): ?>
                                    <form action="/forum/post/<?= $post['id'] ?>/like" method="POST" class="flex-1">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 hover:bg-gray-100 rounded-lg transition-colors group <?= (isset($post['user_liked']) && $post['user_liked']) ? 'text-green-600' : 'text-gray-600' ?>">
                                            <span class="text-lg group-hover:scale-110 transition-transform">🍃</span>
                                            <span class="text-sm font-medium">Like</span>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 text-gray-400">
                                        <span class="text-lg">🍃</span>
                                        <span class="text-sm font-medium">Like</span>
                                    </div>
                                <?php endif; ?>

                                <!-- Comment Button -->
                                <a href="/forum/post/<?= $post['id'] ?>" class="open-post-modal flex-1 flex items-center justify-center gap-2 px-4 py-2.5 hover:bg-gray-100 rounded-lg transition-colors text-gray-600 group" data-post='<?= $postData ?>'>
                                    <i data-lucide="message-circle" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-sm font-medium">Comment</span>
                                </a>

                                <!-- Share Button -->
                                <button class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 hover:bg-gray-100 rounded-lg transition-colors text-gray-600 group">
                                    <i data-lucide="share-2" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-sm font-medium">Share</span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Load More / Pagination Placeholder -->
            <?php if (!empty($posts) && count($posts) >= 10): ?>
                <div class="mt-6 text-center">
                    <button class="bg-white text-gray-700 px-6 py-2.5 rounded-lg shadow hover:shadow-md transition-all font-medium">
                        Load More Posts
                    </button>
                </div>
            <?php endif; ?>

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

<?= $this->endSection() ?>