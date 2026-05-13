<div class="border-l-4 border-gray-200 pl-6">
    <div class="flex items-start justify-between mb-2">
        <div class="flex items-center">
            <a href="/users/<?= $comment['user_id'] ?>" class="font-semibold text-gray-900 mr-2 hover:underline"><?= esc($comment['author_name']) ?></a>
            <?php if (!empty($comment['author_role'])): ?>
                <span class="inline-block px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded">
                    <?= ucfirst(esc($comment['author_role'])) ?>
                </span>
            <?php endif; ?>
        </div>
        <span class="text-sm text-gray-500">
            <?= date('M d, Y H:i', strtotime($comment['created_at'])) ?>
        </span>
    </div>
    <div class="text-gray-700 whitespace-pre-line">
        <?= nl2br(esc($comment['comment'])) ?>
    </div>
</div>
