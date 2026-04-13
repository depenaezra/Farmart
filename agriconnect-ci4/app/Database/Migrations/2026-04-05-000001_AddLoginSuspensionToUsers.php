<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLoginSuspensionToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'login_suspended_until' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'status',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'login_suspended_until');
    }
}
