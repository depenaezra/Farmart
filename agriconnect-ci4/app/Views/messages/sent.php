<?= $this->extend(session()->get('user_role') === 'admin' ? 'layouts/admin' : 'layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Sent Messages</h1>
                <p class="text-gray-600">Messages you've sent</p>
            </div>
            <a href="/messages/compose" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                <i data-lucide="plus" class="w-5 h-5 inline mr-2"></i>
                Compose
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
        <div class="flex gap-4">
            <a href="/messages/inbox" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold">
                Inbox
            </a>
            <a href="/messages/sent" class="px-4 py-2 bg-primary text-white rounded-lg font-semibold">
                Sent
            </a>
        </div>
    </div>

    <!-- Messages List -->
    <?php if (empty($messages)): ?>
        <div class="text-center py-20">
            <i data-lucide="send" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <p class="text-xl text-gray-600">No sent messages</p>
            <p class="text-gray-500 mt-2">You haven't sent any messages yet</p>
            <a href="/messages/compose" class="inline-block mt-4 bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                Send First Message
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-40">
            <div class="divide-y divide-gray-200">
                <?php foreach ($messages as $message): ?>
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">
                                        <a href="/messages/view/<?= $message['id'] ?>" class="hover:text-primary">
                                            <?= esc($message['subject'] ?? 'No Subject') ?>
                                        </a>
                                    </h3>
                                </div>

                                <div class="flex items-center text-sm text-gray-600 mb-2">
                                    <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                                    <span class="mr-4">To: <?= esc($message['receiver_name']) ?></span>
                                    <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                                    <span><?php $dt = new DateTime($message['created_at'], new DateTimeZone('UTC')); $dt->setTimezone(new DateTimeZone('Asia/Manila')); echo $dt->format('M d, Y h:i A'); ?></span>
                                </div>

                                <p class="text-gray-700 line-clamp-2">
                                    <?= esc(substr($message['message'], 0, 150)) ?>...
                                </p>
                            </div>

                            <div class="ml-4 flex gap-2">
                                <a href="/messages/view/<?= $message['id'] ?>" class="inline-flex items-center px-3 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    View
                                </a>
                                <form action="/messages/delete/<?= $message['id'] ?>" method="POST" class="inline swal-confirm-form" data-confirm="Are you sure you want to delete this message?">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>