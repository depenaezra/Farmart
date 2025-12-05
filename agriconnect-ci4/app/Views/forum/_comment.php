<div class="flex gap-3 mb-4">
    <a href="/users/<?= $comment['user_id'] ?>" class="flex-shrink-0">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-green-600 flex items-center justify-center text-white font-semibold">
            <?= strtoupper(substr($comment['author_name'] ?? 'U', 0, 1)) ?>
        </div>
    </a>
    <div class="flex-1">
        <div class="bg-gray-50 rounded-2xl px-4 py-3 border border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <a href="/users/<?= $comment['user_id'] ?>" class="font-semibold text-gray-900 hover:underline">
                    <?= esc($comment['author_name']) ?>
                </a>
                <?php if (!empty($comment['author_role'])): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 bg-primary/10 text-primary text-xs font-semibold rounded-full">
                        <?= ucfirst(esc($comment['author_role'])) ?>
                    </span>
                <?php endif; ?>
                <span class="text-xs text-gray-500 ml-auto">
                    <i data-lucide="clock" class="w-3 h-3 inline mr-1"></i>
                    <?= date('M d, Y H:i', strtotime($comment['created_at'])) ?>
                </span>
            </div>
            <div class="text-gray-700 leading-relaxed">
                <?= nl2br(esc($comment['comment'])) ?>
            </div>
        </div>
    </div>
</div>
