<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Itinerary extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'itinerary_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'preference_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'spot_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'day' => [
                'type' => 'INT'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        $this->forge->addKey('itinerary_id', true);
        $this->forge->addForeignKey('preference_id', 'user_preferences', 'preference_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('spot_id', 'tourist_spots', 'spot_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('itinerary');
    }

    public function down()
    {
        //
        $this->forge->dropTable('itinerary');
    }
}
