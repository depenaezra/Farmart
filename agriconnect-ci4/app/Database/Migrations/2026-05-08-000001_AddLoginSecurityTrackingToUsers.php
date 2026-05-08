<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLoginSecurityTrackingToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'failed_login_attempts' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => false,
                'after' => 'login_suspended_until',
            ],
            'total_failed_login_attempts' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => false,
                'after' => 'failed_login_attempts',
            ],
            'last_failed_login' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'total_failed_login_attempts',
            ],
            'lockout_until' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'last_failed_login',
            ],
            'security_email_sent' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => false,
                'after' => 'lockout_until',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', [
            'failed_login_attempts',
            'total_failed_login_attempts',
            'last_failed_login',
            'lockout_until',
            'security_email_sent',
        ]);
    }
}
