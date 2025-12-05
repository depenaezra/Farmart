<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <a href="/forum" class="inline-flex items-center gap-2 text-primary hover:text-primary-hover mb-4 font-medium transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                <span>Back to Forum</span>
            </a>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Create New Post</h1>
            <p class="text-gray-600 flex items-center gap-2">
                <i data-lucide="message-square-plus" class="w-5 h-5"></i>
                Share your thoughts and experiences with the community
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Main Form -->
            <div class="flex-1 lg:max-w-2xl">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                    <form action="/forum/create" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <i data-lucide="type" class="w-4 h-4"></i>
                                    Post Title *
                                </label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    value="<?= old('title') ?>"
                                    required
                                    minlength="5"
                                    maxlength="255"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-shadow"
                                    placeholder="Enter an engaging title for your post"
                                >
                                <?php if (isset($errors['title'])): ?>
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                        <?= $errors['title'] ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <i data-lucide="folder" class="w-4 h-4"></i>
                                    Category
                                </label>
                                <select
                                    id="category"
                                    name="category"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-shadow"
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
                                <label for="content" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                    Content *
                                </label>
                                <textarea
                                    id="content"
                                    name="content"
                                    rows="10"
                                    required
                                    minlength="20"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-shadow"
                                    placeholder="Share your thoughts, experiences, or ask questions..."
                                ><?= old('content') ?></textarea>
                                <?php if (isset($errors['content'])): ?>
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                        <?= $errors['content'] ?>
                                    </p>
                                <?php endif; ?>
                                <p class="text-sm text-gray-500 mt-2 flex items-center gap-1">
                                    <i data-lucide="info" class="w-4 h-4"></i>
                                    Minimum 20 characters. Be respectful and helpful to fellow farmers.
                                </p>
                            </div>

                            <!-- Tag Users -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <i data-lucide="at-sign" class="w-4 h-4"></i>
                                    Tag Users (optional)
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" id="tagUsersBtn" class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-2.5 rounded-xl hover:bg-blue-100 font-medium transition-colors border border-blue-200">
                                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                                        Tag Users
                                    </button>
                                    <div id="taggedUsers" class="flex flex-wrap gap-2">
                                        <!-- Tagged users will appear here -->
                                    </div>
                                </div>
                                <input type="hidden" id="taggedUserIds" name="tagged_users" value="">
                                <p class="text-sm text-gray-500 mt-2 flex items-center gap-1">
                                    <i data-lucide="bell" class="w-4 h-4"></i>
                                    Tag other users to notify them about your post.
                                </p>
                            </div>

                            <!-- Image Upload -->
                            <div>
                                <label for="images" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                                    <i data-lucide="image-plus" class="w-4 h-4"></i>
                                    Attach Photos (optional)
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-primary transition-colors">
                                    <input type="file" id="images" name="images[]" accept="image/*" multiple class="w-full" onchange="previewImages(event)">
                                </div>
                                <p class="text-sm text-gray-500 mt-2 flex items-center gap-1">
                                    <i data-lucide="info" class="w-4 h-4"></i>
                                    Optional: add up to 5 photos to illustrate your post (jpg, png, max 5MB each).
                                </p>
                                <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4 hidden">
                                    <!-- Images will be added here dynamically -->
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex gap-4">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 bg-primary text-white px-8 py-3.5 rounded-xl hover:bg-primary-hover font-semibold transition-all shadow-md hover:shadow-lg">
                                <i data-lucide="send" class="w-5 h-5"></i>
                                Create Post
                            </button>
                            <a href="/forum" class="inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-700 px-8 py-3.5 rounded-xl hover:bg-gray-200 font-semibold transition-colors">
                                <i data-lucide="x" class="w-5 h-5"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Guidelines Sidebar -->
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="lg:sticky lg:top-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 rounded-2xl p-6 shadow-md">
                        <div class="flex items-center gap-2 mb-4">
                            <i data-lucide="shield-check" class="w-6 h-6 text-blue-700"></i>
                            <h3 class="font-bold text-blue-900">Posting Guidelines</h3>
                        </div>
                        <ul class="text-sm text-blue-800 space-y-3">
                            <li class="flex items-start gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                                <span>Be respectful and constructive in your posts</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                                <span>Share accurate information and personal experiences</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                                <span>Ask questions that help the farming community</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                                <span>Avoid spam, advertisements, or inappropriate content</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                                <span>Use clear, descriptive titles for your posts</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tag Users Modal -->
<div id="tagUsersModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="flex items-center gap-2 font-bold text-gray-900">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    Tag Users
                </h3>
                <button id="closeTagModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <div class="mb-4">
                <div class="relative">
                    <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-3"></i>
                    <input
                        type="text"
                        id="userSearch"
                        placeholder="Search users..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                </div>
            </div>

            <div id="userList" class="max-h-64 overflow-y-auto mb-6 space-y-2">
                <!-- User list will be populated here -->
            </div>

            <div class="flex justify-end gap-3">
                <button id="cancelTag" class="px-6 py-2.5 text-gray-600 hover:text-gray-800 font-medium transition-colors rounded-xl hover:bg-gray-100">Cancel</button>
                <button id="doneTag" class="px-6 py-2.5 bg-primary text-white rounded-xl hover:bg-primary-hover font-medium transition-colors shadow-md">Done</button>
            </div>
        </div>
    </div>
</div>

<script>
function previewImages(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';

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
                    imageDiv.className = 'relative group';
                    imageDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Image Preview ${index + 1}" class="w-full h-32 object-cover rounded-xl border-2 border-gray-200">
                        <button type="button" onclick="removeImage(${index})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg opacity-0 group-hover:opacity-100">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    `;
                    previewContainer.appendChild(imageDiv);
                    lucide.createIcons();
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

    const dt = new DataTransfer();
    files.forEach(file => dt.items.add(file));
    input.files = dt.files;

    previewImages({target: input});
}

let taggedUsers = [];
let allUsers = [];

document.addEventListener('DOMContentLoaded', function() {
    loadAllUsers();
    document.getElementById('tagUsersBtn').addEventListener('click', openTagModal);
    document.getElementById('closeTagModal').addEventListener('click', closeTagModal);
    document.getElementById('cancelTag').addEventListener('click', closeTagModal);
    document.getElementById('doneTag').addEventListener('click', doneTagging);
    document.getElementById('userSearch').addEventListener('input', filterUsers);
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

    userList.innerHTML = '';

    if (allUsers.length === 0) {
        userList.innerHTML = '<div class="text-center py-8 text-gray-500"><i data-lucide="users-2" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i><p>No users found</p></div>';
        lucide.createIcons();
    } else {
        allUsers.forEach(user => {
            const isTagged = taggedUsers.some(tagged => tagged.id === user.id);
            const userDiv = document.createElement('div');
            userDiv.className = `flex items-center justify-between p-3 rounded-xl cursor-pointer transition-all ${isTagged ? 'bg-blue-50 border-2 border-blue-200' : 'hover:bg-gray-50 border-2 border-transparent'}`;
            userDiv.setAttribute('data-user-id', user.id);
            userDiv.setAttribute('data-user-name', user.name);

            userDiv.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-green-600 flex items-center justify-center text-white font-semibold shadow-md">
                        ${user.name.charAt(0).toUpperCase()}
                    </div>
                    <span class="font-medium text-gray-900">${user.name}</span>
                </div>
                <div class="w-6 h-6 rounded-md border-2 ${isTagged ? 'bg-primary border-primary' : 'border-gray-300'} flex items-center justify-center transition-all">
                    ${isTagged ? '<i data-lucide="check" class="w-4 h-4 text-white"></i>' : ''}
                </div>
            `;

            userDiv.addEventListener('click', function() {
                toggleUserTag(user.id, user.name);
            });

            userList.appendChild(userDiv);
        });
        lucide.createIcons();
    }

    modal.classList.remove('hidden');
}

function closeTagModal() {
    document.getElementById('tagUsersModal').classList.add('hidden');
    document.getElementById('userSearch').value = '';
    filterUsers();
}

function toggleUserTag(userId, userName) {
    const index = taggedUsers.findIndex(user => user.id === userId);

    if (index > -1) {
        taggedUsers.splice(index, 1);
    } else {
        taggedUsers.push({id: userId, name: userName});
    }

    updateTaggedUsersDisplay();
    updateUserListSelection();
}

function updateTaggedUsersDisplay() {
    const container = document.getElementById('taggedUsers');
    container.innerHTML = '';

    taggedUsers.forEach(user => {
        const tag = document.createElement('div');
        tag.className = 'inline-flex items-center gap-2 bg-blue-50 text-blue-800 px-3 py-2 rounded-full border border-blue-200 font-medium';
        tag.innerHTML = `
            <span>${user.name}</span>
            <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors" onclick="removeUserTag(${user.id})">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        `;
        container.appendChild(tag);
    });

    document.getElementById('taggedUserIds').value = taggedUsers.map(user => user.id).join(',');
    lucide.createIcons();
}

function updateUserListSelection() {
    const userItems = document.querySelectorAll('#userList > div');
    userItems.forEach(item => {
        const userId = parseInt(item.getAttribute('data-user-id'));
        const isTagged = taggedUsers.some(user => user.id === userId);

        item.className = `flex items-center justify-between p-3 rounded-xl cursor-pointer transition-all ${isTagged ? 'bg-blue-50 border-2 border-blue-200' : 'hover:bg-gray-50 border-2 border-transparent'}`;
        const checkbox = item.querySelector('.w-6');
        checkbox.className = `w-6 h-6 rounded-md border-2 ${isTagged ? 'bg-primary border-primary' : 'border-gray-300'} flex items-center justify-center transition-all`;
        checkbox.innerHTML = isTagged ? '<i data-lucide="check" class="w-4 h-4 text-white"></i>' : '';
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
