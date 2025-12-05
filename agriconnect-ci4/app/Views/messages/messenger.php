<?= $this->extend(session()->get('user_role') === 'admin' ? 'layouts/admin' : 'layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-4 h-[calc(100vh-120px)]">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 h-full flex overflow-hidden">
        <!-- Left Sidebar - Conversations List -->
        <div class="w-1/3 border-r border-gray-200 flex flex-col">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">Messages</h1>
                    <button id="newConversationBtn" class="bg-primary text-white p-2 rounded-lg hover:bg-primary-hover transition-colors">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="relative">
                    <input 
                        type="text" 
                        id="searchConversations" 
                        placeholder="Search conversations..." 
                        class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Conversations List -->
            <div class="flex-1 overflow-y-auto">
                <?php if (empty($conversations)): ?>
                    <div class="p-8 text-center">
                        <i data-lucide="message-circle" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                        <p class="text-gray-600 mb-2">No conversations yet</p>
                        <p class="text-sm text-gray-500">Start a new conversation to get started</p>
                    </div>
                <?php else: ?>
                    <div id="conversationsList">
                        <?php foreach ($conversations as $conv): ?>
                            <a href="/messages/conversation/<?= $conv['other_user_id'] ?>" 
                               class="block p-4 hover:bg-gray-50 border-b border-gray-100 transition-colors conversation-item <?= (isset($selected_user_id) && $selected_user_id == $conv['other_user_id']) ? 'bg-blue-50 border-l-4 border-l-primary' : '' ?>"
                               data-user-id="<?= $conv['other_user_id'] ?>">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center mb-1">
                                            <h3 class="font-semibold text-gray-900 truncate">
                                                <?= esc($conv['other_user_name']) ?>
                                            </h3>
                                            <?php if ($conv['unread_count'] > 0): ?>
                                                <span class="ml-2 bg-primary text-white text-xs font-bold rounded-full px-2 py-0.5">
                                                    <?= $conv['unread_count'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($conv['last_message'])): ?>
                                            <p class="text-sm text-gray-600 truncate">
                                                <?= esc(substr($conv['last_message'], 0, 50)) ?><?= strlen($conv['last_message']) > 50 ? '...' : '' ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-3 text-right">
                                        <?php if (!empty($conv['last_message_time'])): ?>
                                            <span class="text-xs text-gray-500">
                                                <?php
                                                $dt = new DateTime($conv['last_message_time'], new DateTimeZone('UTC'));
                                                $dt->setTimezone(new DateTimeZone('Asia/Manila'));
                                                $today = date('M d');
                                                $msgDate = $dt->format('M d');
                                                echo ($msgDate == $today) ? $dt->format('h:i A') : $msgDate;
                                                ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Side - Chat Area -->
        <div class="flex-1 flex flex-col">
            <?php if (isset($selected_user) && isset($messages)): ?>
                <!-- Chat Header -->
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                <?= strtoupper(substr($selected_user['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <h2 class="font-semibold text-gray-900"><?= esc($selected_user['name']) ?></h2>
                                <p class="text-sm text-gray-500"><?= ucfirst($selected_user['role'] ?? 'user') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="messagesContainer" class="flex-1 overflow-y-auto p-4 bg-gray-50">
                    <div id="messagesList" class="space-y-4">
                        <?php foreach ($messages as $msg): ?>
                            <?php $isSent = $msg['sender_id'] == session()->get('user_id'); ?>
                            <div class="flex <?= $isSent ? 'justify-end' : 'justify-start' ?>">
                                <div class="max-w-xs lg:max-w-md">
                                    <div class="flex items-end gap-2 <?= $isSent ? 'flex-row-reverse' : '' ?>">
                                        <?php if (!$isSent): ?>
                                            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                                                <?= strtoupper(substr($msg['sender_name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="<?= $isSent ? 'bg-primary text-white' : 'bg-white text-gray-900' ?> rounded-2xl px-4 py-2 shadow-sm">
                                            <?php if (!$isSent && !empty($msg['subject'])): ?>
                                                <p class="font-semibold text-sm mb-1 opacity-90">
                                                    <?= esc($msg['subject']) ?>
                                                </p>
                                            <?php endif; ?>
                                            <p class="text-sm whitespace-pre-wrap"><?= nl2br(esc($msg['message'])) ?></p>

                                            <?php if (!empty($msg['attachments']) && is_array($msg['attachments'])): ?>
                                                <div class="mt-2 space-y-2">
                                                    <?php foreach ($msg['attachments'] as $attachment): ?>
                                                        <?php 
                                                        // Ensure attachment has required fields and is an image
                                                        if (isset($attachment['file_path']) && isset($attachment['file_type']) && strpos($attachment['file_type'], 'image/') === 0): 
                                                            $imagePath = '/' . ltrim($attachment['file_path'], '/');
                                                        ?>
                                                            <div class="rounded-lg overflow-hidden border border-gray-200">
                                                                <img src="<?= esc($imagePath) ?>"
                                                                     alt="<?= esc($attachment['file_name'] ?? 'Image') ?>"
                                                                     class="w-full h-auto max-w-full cursor-pointer hover:opacity-90 transition-opacity"
                                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                                                                     onclick="openImageModal('<?= esc($imagePath) ?>', '<?= esc($attachment['file_name'] ?? 'Image') ?>')">
                                                                <div style="display:none;" class="p-2 text-sm text-gray-500 bg-gray-100 rounded">
                                                                    Image not found: <?= esc($attachment['file_name'] ?? 'Unknown') ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>

                                            <p class="text-xs mt-1 opacity-70">
                                                <?php $dt = new DateTime($msg['created_at'], new DateTimeZone('UTC')); $dt->setTimezone(new DateTimeZone('Asia/Manila')); echo $dt->format('h:i A'); ?>
                                            </p>
                                        </div>
                                        <?php if ($isSent): ?>
                                            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                                                <?= strtoupper(substr(session()->get('user_name') ?? 'U', 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="p-4 border-t border-gray-200 bg-white">
                    <form id="messageForm" action="/messages/compose" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="receiver_id" value="<?= $selected_user['id'] ?>">
                        <div class="space-y-3">
                            <!-- File Attachments -->
                            <div class="flex items-center gap-2">
                                <input
                                    type="file"
                                    id="attachmentsInput"
                                    name="attachments[]"
                                    multiple
                                    accept="image/*"
                                    class="hidden"
                                >
                                <button
                                    type="button"
                                    id="attachButton"
                                    class="flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                                >
                                    <i data-lucide="paperclip" class="w-4 h-4"></i>
                                    <span class="text-sm">Attach images</span>
                                </button>
                                <div id="filePreview" class="flex flex-wrap gap-2"></div>
                            </div>

                            <!-- Message Input -->
                            <div class="flex gap-2">
                                <input
                                    type="text"
                                    id="messageInput"
                                    name="message"
                                    placeholder="Type a message or attach images..."
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                    autocomplete="off"
                                >
                                <button
                                    type="submit"
                                    class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors flex items-center"
                                >
                                    <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                                    Send
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center">
                        <i data-lucide="message-circle" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Select a conversation</h3>
                        <p class="text-gray-600 mb-4">Choose a conversation from the list to start messaging</p>
                        <button id="newConversationBtn2" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                            <i data-lucide="plus" class="w-5 h-5 inline mr-2"></i>
                            New Conversation
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center">
    <div class="relative max-w-4xl max-h-screen p-4">
        <button id="closeImageModal" class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
    </div>
</div>

<!-- New Conversation Modal -->
<div id="newConversationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">New Conversation</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form action="/messages/compose" method="GET">
                <div class="mb-4">
                    <label for="modalReceiverId" class="block text-sm font-semibold text-gray-700 mb-2">
                        Select User
                    </label>
                    <select
                        id="modalReceiverId"
                        name="to"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                        <option value="">Select a user...</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>">
                                <?= esc($user['name']) ?> (<?= ucfirst($user['role']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                        Start Conversation
                    </button>
                    <button type="button" id="cancelModal" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const messagesContainer = document.getElementById('messagesContainer');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const newConversationBtn = document.getElementById('newConversationBtn');
    const newConversationBtn2 = document.getElementById('newConversationBtn2');
    const newConversationModal = document.getElementById('newConversationModal');
    const closeModal = document.getElementById('closeModal');
    const cancelModal = document.getElementById('cancelModal');
    const selectedUserId = <?= isset($selected_user_id) ? $selected_user_id : 'null' ?>;

    // Auto-scroll to bottom
    function scrollToBottom() {
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    // Scroll to bottom on load
    scrollToBottom();

    // New conversation modal
    if (newConversationBtn) {
        newConversationBtn.addEventListener('click', () => {
            newConversationModal.classList.remove('hidden');
        });
    }

    if (newConversationBtn2) {
        newConversationBtn2.addEventListener('click', () => {
            newConversationModal.classList.remove('hidden');
        });
    }

    if (closeModal) {
        closeModal.addEventListener('click', () => {
            newConversationModal.classList.add('hidden');
        });
    }

    if (cancelModal) {
        cancelModal.addEventListener('click', () => {
            newConversationModal.classList.add('hidden');
        });
    }

    // Close modal on outside click
    newConversationModal.addEventListener('click', (e) => {
        if (e.target === newConversationModal) {
            newConversationModal.classList.add('hidden');
        }
    });

    // Handle file attachment
    const attachButton = document.getElementById('attachButton');
    const attachmentsInput = document.getElementById('attachmentsInput');
    const filePreview = document.getElementById('filePreview');

    if (attachButton && attachmentsInput) {
        attachButton.addEventListener('click', () => {
            attachmentsInput.click();
        });

        attachmentsInput.addEventListener('change', function(e) {
            filePreview.innerHTML = '';
            const files = Array.from(e.target.files);

            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'relative';
                        previewDiv.innerHTML = `
                            <div class="relative w-16 h-16 rounded-lg overflow-hidden border border-gray-300">
                                <img src="${e.target.result}" class="w-full h-full object-cover">
                                <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600" onclick="removeAttachment(${index})">
                                    ×
                                </button>
                            </div>
                        `;
                        filePreview.appendChild(previewDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    // Remove attachment function
    window.removeAttachment = function(index) {
        const dt = new DataTransfer();
        const files = Array.from(attachmentsInput.files);
        files.splice(index, 1);
        files.forEach(file => dt.items.add(file));
        attachmentsInput.files = dt.files;

        // Update preview
        const event = new Event('change');
        attachmentsInput.dispatchEvent(event);
    };

    // Handle message form submission
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const message = messageInput.value.trim();
            const attachments = attachmentsInput.files;

            if (!message.trim() && attachments.length === 0) {
                alert('Please enter a message or attach images.');
                return;
            }

            // Disable form
            const submitBtn = messageForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 mr-2 animate-spin"></i> Sending...';

            const formData = new FormData(messageForm);

            fetch('/messages/compose', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear inputs
                    messageInput.value = '';
                    attachmentsInput.value = '';
                    filePreview.innerHTML = '';

                    // Reload page to show new message
                    window.location.reload();
                } else {
                        try {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error || 'Failed to send message',
                                showCloseButton: true,
                                showClass: { popup: 'animate__animated animate__shakeX' },
                                hideClass: { popup: 'animate__animated animate__fadeOutUp' }
                            });
                        } catch (e) {
                            alert(data.error || 'Failed to send message');
                        }
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i data-lucide="send" class="w-5 h-5 mr-2"></i> Send';
                    }
            })
            .catch(error => {
                console.error('Error:', error);
                try {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to send message. Please try again.',
                        showCloseButton: true
                    });
                } catch (e) {
                    alert('Failed to send message. Please try again.');
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i data-lucide="send" class="w-5 h-5 mr-2"></i> Send';
            });
        });
    }

    // Auto-refresh messages every 5 seconds if conversation is open
    if (selectedUserId) {
        setInterval(function() {
            fetch(`/messages/conversation`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `other_user_id=${selectedUserId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages) {
                    // Check if we have new messages
                    const currentMessageCount = document.querySelectorAll('#messagesList > div').length;
                    if (data.messages.length > currentMessageCount) {
                        // Reload to show new messages
                        window.location.reload();
                    }
                }
            })
            .catch(error => console.error('Error refreshing messages:', error));
        }, 5000);
    }

    // Search conversations
    const searchInput = document.getElementById('searchConversations');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const conversations = document.querySelectorAll('.conversation-item');
            
            conversations.forEach(conv => {
                const text = conv.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    conv.style.display = 'block';
                } else {
                    conv.style.display = 'none';
                }
            });
        });
    }

    // Image modal functionality
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeImageModal = document.getElementById('closeImageModal');

    window.openImageModal = function(src, alt) {
        modalImage.src = src;
        modalImage.alt = alt;
        imageModal.classList.remove('hidden');
    };

    if (closeImageModal) {
        closeImageModal.addEventListener('click', () => {
            imageModal.classList.add('hidden');
        });
    }

    if (imageModal) {
        imageModal.addEventListener('click', (e) => {
            if (e.target === imageModal) {
                imageModal.classList.add('hidden');
            }
        });
    }

    // Re-initialize icons after dynamic content
    if (typeof lucide !== 'undefined') {
        setInterval(() => lucide.createIcons(), 1000);
    }
});
</script>

<?= $this->endSection() ?>

