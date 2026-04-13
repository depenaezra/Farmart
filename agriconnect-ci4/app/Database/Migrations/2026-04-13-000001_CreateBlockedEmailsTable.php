<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBlockedEmailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
            ],
            'blocked_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'blocked_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('blocked_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('blocked_emails');
    }

    public function down()
    {
        $this->forge->dropTable('blocked_emails');
    }
}