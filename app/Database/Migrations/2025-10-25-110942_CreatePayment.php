<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePayment extends Migration
{
    public function up()
    {
        //Payment	payment_id PK	INT	booking_id FK	INT	amount	DECIMAL	payment_method	ENUM	payment_date	DATETIME	transaction_id	VARCHAR	reference_number	VARCHAR	status	ENUM	notes	TEXT	processed_by FK	INT	created_at	DATETIME
        $data = [
            'payment_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'booking_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['Credit Card', 'Debit Card', 'PayPal', 'Bank Transfer', 'Cash'],
            ],
            'payment_date' => [
                'type'       => 'DATETIME',
            ],
            'transaction_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'reference_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Pending', 'Completed', 'Failed', 'Refunded'],
                'default'    => 'Pending',
            ],
            'notes' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'processed_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ];
        $this->forge->addField($data);
        $this->forge->addKey('payment_id', true);
        $this->forge->addForeignKey('booking_id', 'bookings', 'booking_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        //
        $this->forge->dropTable('payments');
    }
}
