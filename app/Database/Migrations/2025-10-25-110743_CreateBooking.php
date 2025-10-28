<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBooking extends Migration
{
    public function up()
    {
        //Booking	booking_id PK	INT	spot_id FK	INT	customer_id PK	INT	booking_date	DATETIME	visit_date	DATE	visit_time	TIME	num_adults	INT	num_children	INT	num_seniors	INT	total_guests	INT	price_per_person	DECIMAL	subtotal	DECIMAL	discount_amount	DECIMAL	tax_amount	DECIMAL	total_price	DECIMAL	booking_status	ENUM	payment_status	ENUM	special_requests	TEXT	cancellation_reason	TEXT	internal_notes	TEXT	created_at	DATETIME	updated_at	DATETIME	confirmed_at	DATETIME	cancelled_at	DATETIME	completed_at	DATETIME
        $data = [
            'booking_id' => [
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
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'booking_date' => [
                'type'       => 'DATETIME',
            ],
            'visit_date' => [
                'type'       => 'DATE',
            ],
            'visit_time' => [
                'type'       => 'TIME',
            ],
            'num_adults' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'num_children' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'num_seniors' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'total_guests' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'price_per_person' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'discount_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'tax_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'booking_status' => [
                'type'       => 'ENUM',
                'constraint' => ['Pending', 'Confirmed', 'Cancelled', 'Completed'],
            ],
            'payment_status' => [
                'type'       => 'ENUM',
                'constraint' => ['Unpaid', 'Paid', 'Refunded'],
            ],
            'special_requests' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'cancellation_reason' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'internal_notes' => [
                'type'       => 'TEXT',
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
            'confirmed_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'cancelled_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'completed_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ];
        $this->forge->addField($data);
        $this->forge->addKey('booking_id', true);
        $this->forge->addForeignKey('spot_id', 'tourist_spots', 'spot_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('customer_id', 'customers', 'customer_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bookings');

    }

    public function down()
    {
        //
        $this->forge->dropTable('bookings');
    }
}
