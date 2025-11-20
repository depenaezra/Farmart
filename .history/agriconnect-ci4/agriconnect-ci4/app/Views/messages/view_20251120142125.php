<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="/messages/inbox" class="inline-flex items-center text-primary hover:text-primary-hover transition-colors mr-4">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Inbox
            </a>
            <a href="/messages/compose?to=<?= $message['sender_id'] == session()->get('user_id') ? $message['receiver_id'] : $message['sender_id'] ?>" class="inline-flex items-center bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition-colors">
                <i data-lucide="reply" class="w-4 h-4 mr-2"></i>
                Reply
            </a>
        </div>

        <!-- Message Content -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
            <div class="p-8">
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">
                        <?= esc($message['subject'] ?? 'No Subject') ?>
                    </h1>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-600">
                            <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                            <span class="mr-4">
                                From: <?= esc($message['sender_name']) ?>
                                <?php if (!empty($message['sender_role'])): ?>
                                    <span class="inline-block px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded ml-2">
                                        <?= ucfirst(esc($message['sender_role'])) ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                            <span class="mr-4"><?= date('M d, Y H:i', strtotime($message['created_at'])) ?></span>
                            <i data-lucide="user-check" class="w-4 h-4 mr-1"></i>
                            <span>To: <?= esc($message['receiver_name']) ?></span>
                        </div>

                        <form action="/messages/delete/<?= $message['id'] ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this message?')">
                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div class="prose prose-lg max-w-none">
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                        <?= nl2br(esc($message['message'])) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reply Form -->
        <div class="mt-8 bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
            <div class="p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Reply to Message</h3>
                <form action="/messages/reply/<?= $message['id'] ?>" method="POST">
                    <div class="mb-6">
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                            Your Reply <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="message"
                            name="message"
                            rows="6"
                            required
                            placeholder="Write your reply..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        ></textarea>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                            <i data-lucide="send" class="w-5 h-5 inline mr-2"></i>
                            Send Reply
                        </button>
                        <a href="/messages/inbox" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


