<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVisitorCheckin extends Migration
{
    public function up()
    {
        //VisitorCheckIn	checkin_id PK	INT	customer_id FK	INT	booking_id FK	INT	checkin_time	DATETIME	checkout_time	DATETIME	actual_visitors	INT	is_walkin	BOOLEAN	notes	TEXT
        $data = [
            'checkin_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'booking_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'checkin_time' => [
                'type'       => 'DATETIME',
            ],
            'checkout_time' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'actual_visitors' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'is_walkin' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
            ],
            'notes' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
        ];
        $this->forge->addField($data);
        $this->forge->addKey('checkin_id', true);
        $this->forge->addForeignKey('customer_id', 'customers', 'customer_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('booking_id', 'bookings', 'booking_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('visitor_checkins');
    }

    public function down()
    {
        //
        $this->forge->dropTable('visitor_checkins');
    }
}
