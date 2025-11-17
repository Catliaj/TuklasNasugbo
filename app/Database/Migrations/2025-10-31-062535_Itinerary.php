<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Itinerary extends Migration
{
    public function up()
    {
        /*
         * itinerary
         * itinerary_id PK INT
         * preference_id FK INT (optional) -> user_preferences
         * spot_id FK INT (optional) -> tourist_spots
         * day INT
         * budget DECIMAL (group or trip budget)
         * adults INT, children INT, seniors INT
         * trip_title VARCHAR, start_date DATE, end_date DATE
         * created_at DATETIME, updated_at DATETIME
         */
        $this->forge->addField([
            'itinerary_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'preference_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
            ],
            'spot_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
            ],
            'day' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => false,
                'default'    => 1,

            ],
            'budget' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
                'default'    => null,
            ],
            'adults' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => false,
                'default'    => 1,
            ],
            'children' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => false,
                'default'    => 0,
            ],
            'seniors' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => false,
                'default'    => 0,
            ],
            'trip_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('itinerary_id', true);
        $this->forge->addKey('preference_id');
        $this->forge->addKey('spot_id');

        // foreign keys (preference_id optional)
        $this->forge->addForeignKey('preference_id', 'user_preferences', 'preference_id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('spot_id', 'tourist_spots', 'spot_id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('itinerary');
    }

    public function down()
    {
        $this->forge->dropTable('itinerary');
    }
}