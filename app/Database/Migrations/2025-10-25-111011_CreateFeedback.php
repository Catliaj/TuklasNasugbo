<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFeedback extends Migration
{
    public function up()
    {
        //ReviewFeedback	review_id PK	INT	booking_id FK	INT	spot_id FK	INT	customer_id FK	INT	business_id FK	INT	rating	INT	title	VARCHAR	comment	TEXT	cleanliness_rating	INT	staff_rating	INT	value_rating	INT	location_rating	INT	status	ENUM	is_verified_visit	BOOLEAN	owner_response	TEXT	response_date	DATETIME	created_at	DATETIME	updated_at	DATETIME
        $data = [
            'review_id' => [
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
            'business_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'rating' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'comment' => [
                'type'       => 'TEXT',
            ],
            'cleanliness_rating' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'staff_rating' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'value_rating' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'location_rating' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Pending', 'Approved', 'Rejected'],
                'default'    => 'Pending',
            ],
            'is_verified_visit' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
            ],
            'owner_response' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'response_date' => [
                'type'       => 'DATETIME',
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
        $this->forge->addKey('review_id', true);
        $this->forge->addForeignKey('booking_id', 'bookings', 'booking_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('spot_id', 'tourist_spots', 'spot_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('customer_id', 'customers', 'customer_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('business_id', 'businesses', 'business_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('review_feedback');
    }

    public function down()
    {
        //
        $this->forge->dropTable('review_feedback');
    }
}
