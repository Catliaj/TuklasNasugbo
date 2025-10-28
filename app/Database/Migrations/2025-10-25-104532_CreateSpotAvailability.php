<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpotAvailability extends Migration
{
    public function up()
    {
        //SpotAvailability	availability_id PK	INT	spot_id FK	INT	available_date	DATE	total_capacity	INT	booked_capacity	INT	available_capacity	INT	is_available	BOOLEAN	reason_unavailable	VARCHAR	special_price	DECIMAL	created_at	DATETIME	updated_at	DATETIME
        $data = [
            'availability_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'spot_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'available_date' => [
                'type'       => 'DATE',
            ],
            'total_capacity' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'booked_capacity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'available_capacity' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'is_available' => [
                'type'       => 'BOOLEAN',
                'default'    => true,
            ],
            'reason_unavailable' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'special_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ];
        $this->forge->addField($data);
        $this->forge->addKey('availability_id', true);
        $this->forge->addForeignKey('spot_id', 'tourist_spots', 'spot_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('spot_availability');
    }

    public function down()
    {
        //
        $this->forge->dropTable('spot_availability');
    }
}
