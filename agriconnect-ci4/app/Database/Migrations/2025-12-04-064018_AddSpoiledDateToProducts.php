<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSpoiledDateToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'spoiled_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'shelf_life_days'
            ],
            'original_price_when_spoiled' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'after' => 'spoiled_date'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', ['spoiled_date', 'original_price_when_spoiled']);
    }
}
