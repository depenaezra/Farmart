<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForumImageMentions extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        // Add image_url column to forum_posts if it doesn't exist
        $fields = [];
        try {
            $existing = $db->getFieldNames('forum_posts');
        } catch (\Exception $e) {
            $existing = [];
        }

        if (!in_array('image_url', $existing)) {
            $forge->addColumn('forum_posts', [
                'image_url' => [
                    'type' => 'VARCHAR',
                    'constraint' => 500,
                    'null' => true,
                ],
            ]);
        }

        // Create forum_mentions table
        if (!$db->tableExists('forum_mentions')) {
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'post_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'mentioned_user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                ],
            ]);
            $forge->addKey('id', true);
            $forge->addKey('post_id');
            $forge->addKey('mentioned_user_id');
            $forge->createTable('forum_mentions');

            // Try to add foreign keys if supported
            try {
                $db->query('ALTER TABLE forum_mentions ADD CONSTRAINT forum_mentions_post_fk FOREIGN KEY (post_id) REFERENCES forum_posts(id) ON DELETE CASCADE');
                $db->query('ALTER TABLE forum_mentions ADD CONSTRAINT forum_mentions_user_fk FOREIGN KEY (mentioned_user_id) REFERENCES users(id) ON DELETE CASCADE');
            } catch (\Exception $e) {
                // Ignore if DB doesn't support adding constraints here
            }
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        if ($db->tableExists('forum_mentions')) {
            $forge->dropTable('forum_mentions', true);
        }

        // Drop image_url column if present
        try {
            $existing = $db->getFieldNames('forum_posts');
        } catch (\Exception $e) {
            $existing = [];
        }

        if (in_array('image_url', $existing)) {
            // Some DB drivers need raw SQL
            try {
                $forge->dropColumn('forum_posts', 'image_url');
            } catch (\Exception $e) {
                // fallback
                $db->query('ALTER TABLE forum_posts DROP COLUMN image_url');
            }
        }
    }
}
