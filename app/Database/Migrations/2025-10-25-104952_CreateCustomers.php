<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomers extends Migration
{
    public function up()
    {
        //Customer	customer_id PK	INT	user_id FK	Type	phone	VARCHAR	address	TEXT	date_of_birth	DATE	nationality	VARCHAR	id_number	VARCHAR	emergency_contact	VARCHAR	emergency_phone	VARCHAR	notes	TEXT	total_bookings	INT	total_spent	DECIMAL	created_at	DATETIME	updated_at	DATETIME
        $data = [
            'customer_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],
            'address' => [
                'type'       => 'TEXT',
            ],
            'date_of_birth' => [
                'type'       => 'DATE',
            ],
            'nationality' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'emergency_contact' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'emergency_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
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
        $this->forge->addKey('customer_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'UserID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('customers');
    }

    public function down()
    {
        //
        $this->forge->dropTable('customers');
    }
}
