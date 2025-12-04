<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSpoilageFieldsToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'harvest_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'location'
            ],
            'shelf_life_days' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'harvest_date'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', ['harvest_date', 'shelf_life_days']);
    }
}
