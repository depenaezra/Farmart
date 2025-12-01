<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <a href="/forum" class="inline-flex items-center text-primary hover:text-primary-hover mb-4">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Forum
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Create New Post</h1>
            <p class="text-gray-600 mt-2">Share your thoughts and experiences with the community</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Main Form -->
            <div class="flex-1 lg:max-w-2xl">
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <form action="/forum/create" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Post Title *</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="<?= old('title') ?>"
                            required
                            minlength="5"
                            maxlength="255"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Enter an engaging title for your post"
                        >
                        <?php if (isset($errors['title'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['title'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                        <select
                            id="category"
                            name="category"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                            <option value="general" <?= old('category', 'general') === 'general' ? 'selected' : '' ?>>General Discussion</option>
                            <option value="farming tips" <?= old('category') === 'farming tips' ? 'selected' : '' ?>>Farming Tips</option>
                            <option value="market prices" <?= old('category') === 'market prices' ? 'selected' : '' ?>>Market Prices</option>
                            <option value="weather" <?= old('category') === 'weather' ? 'selected' : '' ?>>Weather Updates</option>
                            <option value="equipment" <?= old('category') === 'equipment' ? 'selected' : '' ?>>Equipment & Tools</option>
                        </select>
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">Content *</label>
                        <textarea
                            id="content"
                            name="content"
                            rows="10"
                            required
                            minlength="20"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Share your thoughts, experiences, or ask questions..."
                        ><?= old('content') ?></textarea>
                        <?php if (isset($errors['content'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= $errors['content'] ?></p>
                        <?php endif; ?>
                        <p class="text-sm text-gray-500 mt-1">Minimum 20 characters. Be respectful and helpful to fellow farmers.</p>
                    </div>

                    <!-- Tag Users -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tag Users (optional)</label>
                        <div class="flex gap-2">
                            <button type="button" id="tagUsersBtn" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 font-medium transition-colors flex items-center gap-2">
                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                                Tag Users
                            </button>
                            <div id="taggedUsers" class="flex flex-wrap gap-2">
                                <!-- Tagged users will appear here -->
                            </div>
                        </div>
                        <input type="hidden" id="taggedUserIds" name="tagged_users" value="">
                        <p class="text-sm text-gray-500 mt-1">Tag other users to notify them about your post.</p>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label for="images" class="block text-sm font-semibold text-gray-700 mb-2">Attach Photos (optional)</label>
                        <input type="file" id="images" name="images[]" accept="image/*" multiple class="w-full" onchange="previewImages(event)">
                        <p class="text-sm text-gray-500 mt-1">Optional: add up to 5 photos to illustrate your post (jpg, png, max 5MB each).</p>
                        <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4 hidden">
                            <!-- Images will be added here dynamically -->
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex gap-4">
                    <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                        <i data-lucide="send" class="w-5 h-5 inline mr-2"></i>
                        Create Post
                    </button>
                    <a href="/forum" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                        Cancel
                    </a>
                    </div>
                </form>
            </div>
        </div>
        <!-- Guidelines Sidebar -->
            <div class="w-full lg:w-80 flex-shrink-0 mt-8 lg:mt-0 lg:ml-6">
                <div class="lg:sticky lg:top-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3">Posting Guidelines</h3>
                        <ul class="text-sm text-blue-800 space-y-2">
                            <li>• Be respectful and constructive in your posts</li>
                            <li>• Share accurate information and personal experiences</li>
                            <li>• Ask questions that help the farming community</li>
                            <li>• Avoid spam, advertisements, or inappropriate content</li>
                            <li>• Use clear, descriptive titles for your posts</li>
                        </ul>
                    </div>
                </div>
            </div>
    </div>
</div>

<!-- Tag Users Modal -->
<div id="tagUsersModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tag Users</h3>
                <button id="closeTagModal" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <div class="mb-4">
                <input
                    type="text"
                    id="userSearch"
                    placeholder="Search users..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                >
            </div>

            <div id="userList" class="max-h-64 overflow-y-auto mb-4">
                <!-- User list will be populated here -->
            </div>

            <div class="flex justify-end gap-2">
                <button id="cancelTag" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button id="doneTag" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover">Done</button>
            </div>
        </div>
    </div>
</div>

<script>
function previewImages(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = ''; // Clear previous previews

    if (files.length > 5) {
        alert('You can only upload up to 5 images.');
        event.target.value = '';
        return;
    }

    if (files.length > 0) {
        previewContainer.classList.remove('hidden');

        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'relative';
                    imageDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Image Preview ${index + 1}" class="w-full h-32 object-cover rounded-lg border border-gray-300">
                        <button type="button" onclick="removeImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">×</button>
                    `;
                    previewContainer.appendChild(imageDiv);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        previewContainer.classList.add('hidden');
    }
}

function removeImage(index) {
    const input = document.getElementById('images');
    const files = Array.from(input.files);
    files.splice(index, 1);

    // Create new FileList
    const dt = new DataTransfer();
    files.forEach(file => dt.items.add(file));
    input.files = dt.files;

    // Re-trigger preview
    previewImages({target: input});
}

// Tagging functionality
let taggedUsers = [];
let allUsers = [];

document.addEventListener('DOMContentLoaded', function() {
    // Load all users for tagging
    loadAllUsers();

    // Tag users button
    document.getElementById('tagUsersBtn').addEventListener('click', openTagModal);

    // Modal controls
    document.getElementById('closeTagModal').addEventListener('click', closeTagModal);
    document.getElementById('cancelTag').addEventListener('click', closeTagModal);
    document.getElementById('doneTag').addEventListener('click', doneTagging);

    // User search
    document.getElementById('userSearch').addEventListener('input', filterUsers);

    // Close modal when clicking outside
    document.getElementById('tagUsersModal').addEventListener('click', function(e) {
        if (e.target.id === 'tagUsersModal') {
            closeTagModal();
        }
    });
});

function loadAllUsers() {
    fetch('/forum/mentions?q=')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allUsers = data.users;
            } else {
                allUsers = [];
                console.warn('Failed to load users:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading users:', error);
            allUsers = [];
        });
}

function openTagModal() {
    const modal = document.getElementById('tagUsersModal');
    const userList = document.getElementById('userList');

    // Populate user list
    userList.innerHTML = '';

    if (allUsers.length === 0) {
        userList.innerHTML = '<div class="text-center py-4 text-gray-500">No users found</div>';
    } else {
        allUsers.forEach(user => {
            const isTagged = taggedUsers.some(tagged => tagged.id === user.id);
            const userDiv = document.createElement('div');
            userDiv.className = `flex items-center justify-between p-3 rounded-lg cursor-pointer hover:bg-gray-50 ${isTagged ? 'bg-blue-50' : ''}`;
            userDiv.setAttribute('data-user-id', user.id);
            userDiv.setAttribute('data-user-name', user.name);

            userDiv.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-green-600 flex items-center justify-center text-white font-semibold text-sm">
                        ${user.name.charAt(0).toUpperCase()}
                    </div>
                    <span class="text-sm font-medium text-gray-900">${user.name}</span>
                </div>
                <div class="w-5 h-5 rounded border-2 ${isTagged ? 'bg-primary border-primary' : 'border-gray-300'} flex items-center justify-center">
                    ${isTagged ? '<i data-lucide="check" class="w-3 h-3 text-white"></i>' : ''}
                </div>
            `;

            userDiv.addEventListener('click', function() {
                toggleUserTag(user.id, user.name);
            });

            userList.appendChild(userDiv);
        });
    }

    modal.classList.remove('hidden');
    lucide.createIcons();
}

function closeTagModal() {
    document.getElementById('tagUsersModal').classList.add('hidden');
    document.getElementById('userSearch').value = '';
    filterUsers(); // Reset filter
}

function toggleUserTag(userId, userName) {
    const index = taggedUsers.findIndex(user => user.id === userId);

    if (index > -1) {
        // Remove from tagged users
        taggedUsers.splice(index, 1);
    } else {
        // Add to tagged users
        taggedUsers.push({id: userId, name: userName});
    }

    // Update UI
    updateTaggedUsersDisplay();
    updateUserListSelection();
}

function updateTaggedUsersDisplay() {
    const container = document.getElementById('taggedUsers');
    container.innerHTML = '';

    taggedUsers.forEach(user => {
        const tag = document.createElement('div');
        tag.className = 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm flex items-center gap-2';
        tag.innerHTML = `
            <span>${user.name}</span>
            <button type="button" class="text-blue-600 hover:text-blue-800" onclick="removeUserTag(${user.id})">
                <i data-lucide="x" class="w-3 h-3"></i>
            </button>
        `;
        container.appendChild(tag);
    });

    // Update hidden input
    document.getElementById('taggedUserIds').value = taggedUsers.map(user => user.id).join(',');

    lucide.createIcons();
}

function updateUserListSelection() {
    const userItems = document.querySelectorAll('#userList > div');
    userItems.forEach(item => {
        const userId = parseInt(item.getAttribute('data-user-id'));
        const isTagged = taggedUsers.some(user => user.id === userId);

        item.classList.toggle('bg-blue-50', isTagged);
        const checkbox = item.querySelector('.w-5');
        checkbox.className = `w-5 h-5 rounded border-2 ${isTagged ? 'bg-primary border-primary' : 'border-gray-300'} flex items-center justify-center`;
        checkbox.innerHTML = isTagged ? '<i data-lucide="check" class="w-3 h-3 text-white"></i>' : '';
    });

    lucide.createIcons();
}

function removeUserTag(userId) {
    const index = taggedUsers.findIndex(user => user.id === userId);
    if (index > -1) {
        taggedUsers.splice(index, 1);
        updateTaggedUsersDisplay();
    }
}

function filterUsers() {
    const searchTerm = document.getElementById('userSearch').value.toLowerCase();
    const userItems = document.querySelectorAll('#userList > div');

    userItems.forEach(item => {
        const userName = item.getAttribute('data-user-name').toLowerCase();
        const shouldShow = userName.includes(searchTerm);
        item.style.display = shouldShow ? 'flex' : 'none';
    });
}

function doneTagging() {
    closeTagModal();
}
</script>

<?= $this->endSection() ?>
