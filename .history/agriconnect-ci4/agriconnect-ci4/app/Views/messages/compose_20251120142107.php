<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="/messages/inbox" class="inline-flex items-center text-primary hover:text-primary-hover transition-colors mr-4">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to Inbox
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Compose Message</h1>
            <p class="text-gray-600">Send a message to another user</p>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8">
            <form action="/messages/compose" method="POST">
                <div class="mb-6">
                    <label for="receiver_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        To <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="receiver_id"
                        name="receiver_id"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                        <option value="">Select recipient...</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= ($receiver && $receiver['id'] == $user['id']) ? 'selected' : '' ?>>
                                <?= esc($user['name']) ?> (<?= ucfirst($user['role']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                        Subject
                    </label>
                    <input
                        type="text"
                        id="subject"
                        name="subject"
                        maxlength="255"
                        placeholder="Enter message subject..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                </div>

                <div class="mb-6">
                    <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="message"
                        name="message"
                        rows="8"
                        required
                        placeholder="Write your message here..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    ></textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-hover font-semibold transition-colors">
                        <i data-lucide="send" class="w-5 h-5 inline mr-2"></i>
                        Send Message
                    </button>
                    <a href="/messages/inbox" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Quick Tips -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Message Tips</h3>
            <ul class="text-blue-800 text-sm space-y-1">
                <li>• Keep your subject clear and concise</li>
                <li>• Be respectful and professional in your communication</li>
                <li>• Include all relevant details in your message</li>
                <li>• Check your sent messages to track responses</li>
            </ul>
        </div>
    </div>
</div>

<?= $this->endSection() ?>