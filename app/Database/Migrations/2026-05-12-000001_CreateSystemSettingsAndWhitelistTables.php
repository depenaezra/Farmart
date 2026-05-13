<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSystemSettingsAndWhitelistTables extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        if (!$db->tableExists('system_settings')) {
            $forge->addField([
                'key' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'value' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('key', true);
            $forge->createTable('system_settings');
        }

        if (!$db->tableExists('whitelisted_emails')) {
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'created_by' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->addKey('email', false, true); // unique
            $forge->createTable('whitelisted_emails');

            // Best-effort FK (safe to ignore if unsupported).
            try {
                $db->query('ALTER TABLE whitelisted_emails ADD CONSTRAINT whitelisted_emails_created_by_fk FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL');
            } catch (\Exception $e) {
                // ignore
            }
        }

        // Seed default setting (off).
        try {
            $exists = $db->table('system_settings')->where('key', 'email_whitelist_enabled')->countAllResults() > 0;
            if (!$exists) {
                $db->table('system_settings')->insert([
                    'key' => 'email_whitelist_enabled',
                    'value' => '0',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        } catch (\Exception $e) {
            // ignore
        }
    }

    public function down()
    {
        $forge = \Config\Database::forge();
        $forge->dropTable('whitelisted_emails', true);
        $forge->dropTable('system_settings', true);
    }
}

