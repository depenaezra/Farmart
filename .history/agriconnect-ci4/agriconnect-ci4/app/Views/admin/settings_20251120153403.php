<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">System Settings</h1>
        <p class="text-gray-600">Configure system-wide settings and preferences</p>
    </div>

    <!-- Settings Tabs -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="flex">
                <button class="settings-tab active px-6 py-4 text-sm font-medium text-gray-900 border-b-2 border-primary"
                        data-tab="general">
                    General
                </button>
                <button class="settings-tab px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent"
                        data-tab="security">
                    Security
                </button>
                <button class="settings-tab px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent"
                        data-tab="notifications">
                    Notifications
                </button>
                <button class="settings-tab px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent"
                        data-tab="maintenance">
                    Maintenance
                </button>
            </nav>
        </div>

        <!-- General Settings -->
        <div id="general-tab" class="settings-content p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">General Settings</h3>

            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                        <input type="text" id="site_name" name="site_name" value="AgriConnect"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500">The name displayed throughout the application</p>
                    </div>

                    <div>
                        <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                        <input type="text" id="site_description" name="site_description" value="Connecting Farmers and Buyers"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500">Brief description of the platform</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                        <input type="email" id="contact_email" name="contact_email" value="admin@agriconnect.com"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500">Primary contact email for support</p>
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                        <input type="tel" id="contact_phone" name="contact_phone" value="+63 123 456 7890"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500">Primary contact phone number</p>
                    </div>
                </div>

                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                    <select id="timezone" name="timezone"
                            class="w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="Asia/Manila" selected>Asia/Manila (GMT+8)</option>
                        <option value="Asia/Singapore">Asia/Singapore (GMT+8)</option>
                        <option value="UTC">UTC (GMT+0)</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">System timezone for date/time display</p>
                </div>

                <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                        <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Security Settings -->
        <div id="security-tab" class="settings-content p-6 hidden">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Security Settings</h3>

            <form class="space-y-6">
                <div>
                    <label for="password_min_length" class="block text-sm font-medium text-gray-700 mb-2">Minimum Password Length</label>
                    <select id="password_min_length" name="password_min_length"
                            class="w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="6">6 characters</option>
                        <option value="8" selected>8 characters</option>
                        <option value="10">10 characters</option>
                        <option value="12">12 characters</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Minimum length required for user passwords</p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="password_complexity" name="password_complexity" checked
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="password_complexity" class="ml-2 block text-sm text-gray-900">
                            Require password complexity (uppercase, lowercase, numbers, symbols)
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="two_factor_auth" name="two_factor_auth"
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="two_factor_auth" class="ml-2 block text-sm text-gray-900">
                            Enable two-factor authentication for admins
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="session_timeout" name="session_timeout" checked
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="session_timeout" class="ml-2 block text-sm text-gray-900">
                            Auto-logout inactive users after 30 minutes
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                        <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Notification Settings -->
        <div id="notifications-tab" class="settings-content p-6 hidden">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Notification Settings</h3>

            <form class="space-y-6">
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900">Email Notifications</h4>

                    <div class="flex items-center">
                        <input type="checkbox" id="email_new_user" name="email_new_user" checked
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="email_new_user" class="ml-2 block text-sm text-gray-900">
                            Notify admins when new users register
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="email_new_order" name="email_new_order" checked
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="email_new_order" class="ml-2 block text-sm text-gray-900">
                            Notify farmers when new orders are placed
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="email_product_approval" name="email_product_approval" checked
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="email_product_approval" class="ml-2 block text-sm text-gray-900">
                            Notify farmers when products are approved/rejected
                        </label>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900">System Notifications</h4>

                    <div class="flex items-center">
                        <input type="checkbox" id="system_maintenance" name="system_maintenance" checked
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="system_maintenance" class="ml-2 block text-sm text-gray-900">
                            Show maintenance notifications to users
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="system_updates" name="system_updates"
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="system_updates" class="ml-2 block text-sm text-gray-900">
                            Notify users about system updates
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                        <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Maintenance Settings -->
        <div id="maintenance-tab" class="settings-content p-6 hidden">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Maintenance Settings</h3>

            <div class="space-y-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-400 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-medium text-yellow-800">Maintenance Mode</h4>
                            <p class="text-sm text-yellow-700 mt-1">
                                When enabled, the site will be temporarily unavailable to regular users.
                                Only administrators can access the system.
                            </p>
                        </div>
                    </div>
                </div>

                <form class="space-y-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="maintenance_mode" name="maintenance_mode"
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="maintenance_mode" class="ml-2 block text-sm text-gray-900">
                            Enable maintenance mode
                        </label>
                    </div>

                    <div>
                        <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-2">Maintenance Message</label>
                        <textarea id="maintenance_message" name="maintenance_message" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Message to display to users during maintenance...">The system is currently under maintenance. Please check back later.</textarea>
                        <p class="mt-1 text-sm text-gray-500">Message shown to users when maintenance mode is active</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="backup_frequency" class="block text-sm font-medium text-gray-700 mb-2">Database Backup Frequency</label>
                            <select id="backup_frequency" name="backup_frequency"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="daily" selected>Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>

                        <div>
                            <label for="log_retention" class="block text-sm font-medium text-gray-700 mb-2">Log Retention (days)</label>
                            <select id="log_retention" name="log_retention"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="30" selected>30 days</option>
                                <option value="60">60 days</option>
                                <option value="90">90 days</option>
                                <option value="365">1 year</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                        <button type="button" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors mr-4">
                            <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>
                            Backup Now
                        </button>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                            <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.settings-tab');
    const tabContents = document.querySelectorAll('.settings-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'text-gray-900', 'border-primary');
                btn.classList.add('text-gray-500');
            });

            // Hide all tab contents
            tabContents.forEach(content => content.classList.add('hidden'));

            // Add active class to clicked button
            button.classList.add('active', 'text-gray-900', 'border-primary');
            button.classList.remove('text-gray-500');

            // Show corresponding tab content
            const tabId = button.getAttribute('data-tab') + '-tab';
            document.getElementById(tabId).classList.remove('hidden');
        });
    });
});
</script>

<?= $this->endSection() ?>