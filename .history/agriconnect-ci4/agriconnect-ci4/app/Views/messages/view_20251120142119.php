
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
