<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOtpTokenTable extends Migration
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
            'userID' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('userID');
        $this->forge->addKey('token');
        $this->forge->addKey('expires_at');
        $this->forge->addForeignKey('userID', 'users', 'id', 'CASCADE', 'CASCADE', 'otp_token_user_fk');

        $this->forge->createTable('otp_token', true);
    }

    public function down()
    {
        $this->forge->dropTable('otp_token', true);
    }
}