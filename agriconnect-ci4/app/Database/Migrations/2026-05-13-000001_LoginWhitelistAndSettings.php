<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LoginWhitelistAndSettings extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('application_settings')) {
            return;
        }

        $this->forge->addField([
            'setting_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('setting_key', true);
        $this->forge->createTable('application_settings');

        $this->db->table('application_settings')->insert([
            'setting_key'   => 'login_whitelist_enabled',
            'setting_value' => '0',
        ]);

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'unique'     => true,
            ],
            'created_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('login_whitelist_emails');
    }

    public function down()
    {
        $this->forge->dropTable('login_whitelist_emails', true);
        $this->forge->dropTable('application_settings', true);
    }
}
