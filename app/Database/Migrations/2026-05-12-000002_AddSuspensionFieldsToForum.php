<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSuspensionFieldsToForum extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        // forum_posts fields
        try {
            $postFields = $db->getFieldNames('forum_posts');
        } catch (\Exception $e) {
            $postFields = [];
        }

        if (!in_array('is_suspended', $postFields)) {
            $forge->addColumn('forum_posts', [
                'is_suspended' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                ],
            ]);
        }
        if (!in_array('suspended_at', $postFields)) {
            $forge->addColumn('forum_posts', [
                'suspended_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
        }
        if (!in_array('suspended_reason', $postFields)) {
            $forge->addColumn('forum_posts', [
                'suspended_reason' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
            ]);
        }
        if (!in_array('suspended_by', $postFields)) {
            $forge->addColumn('forum_posts', [
                'suspended_by' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
            ]);
        }

        // forum_comments fields (optional; may not exist in some DBs)
        try {
            $commentFields = $db->getFieldNames('forum_comments');
        } catch (\Exception $e) {
            $commentFields = [];
        }

        if (!empty($commentFields)) {
            if (!in_array('is_suspended', $commentFields)) {
                $forge->addColumn('forum_comments', [
                    'is_suspended' => [
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'default' => 0,
                    ],
                ]);
            }
            if (!in_array('suspended_at', $commentFields)) {
                $forge->addColumn('forum_comments', [
                    'suspended_at' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                ]);
            }
            if (!in_array('suspended_reason', $commentFields)) {
                $forge->addColumn('forum_comments', [
                    'suspended_reason' => [
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'null' => true,
                    ],
                ]);
            }
            if (!in_array('suspended_by', $commentFields)) {
                $forge->addColumn('forum_comments', [
                    'suspended_by' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => true,
                        'null' => true,
                    ],
                ]);
            }
        }
    }

    public function down()
    {
        $forge = \Config\Database::forge();

        try {
            $forge->dropColumn('forum_posts', 'is_suspended');
        } catch (\Exception $e) {
        }
        try {
            $forge->dropColumn('forum_posts', 'suspended_at');
        } catch (\Exception $e) {
        }
        try {
            $forge->dropColumn('forum_posts', 'suspended_reason');
        } catch (\Exception $e) {
        }
        try {
            $forge->dropColumn('forum_posts', 'suspended_by');
        } catch (\Exception $e) {
        }

        try {
            $forge->dropColumn('forum_comments', 'is_suspended');
            $forge->dropColumn('forum_comments', 'suspended_at');
            $forge->dropColumn('forum_comments', 'suspended_reason');
            $forge->dropColumn('forum_comments', 'suspended_by');
        } catch (\Exception $e) {
        }
    }
}

