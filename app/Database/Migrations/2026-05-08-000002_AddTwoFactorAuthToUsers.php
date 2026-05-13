<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTwoFactorAuthToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'twofa_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => false,
                'after' => 'status',
            ],
            'twofa_secret' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'twofa_enabled',
            ],
            'twofa_backup_codes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'twofa_secret',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', [
            'twofa_enabled',
            'twofa_secret',
            'twofa_backup_codes',
        ]);
    }
}