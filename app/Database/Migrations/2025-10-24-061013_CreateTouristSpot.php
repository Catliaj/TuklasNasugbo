<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTouristSpot extends Migration
{
    public function up()
    {
        //TouristSpot	spot_id PK	INT	business_id FK	INT	gallery_id	INT	spot_name	VARCHAR	description	TEXT	category	ENUM	location	VARCHAR	latitude	DECIMAL	longitude	DECIMAL	capacity	INT	opening_time	TIME	closing_time	TIME	operating_days	VARCHAR	status	ENUM	price_per_person	DECIMAL	child_price	DECIMAL	senior_price	DECIMAL	group_discount_percent	DECIMAL	primary_image	TEXT	average_rating	DECIMAL	total_reviews	INT	popularity_score	INT	created_at	DATETIME	updated_at	DATETIME
        $data = [
            'spot_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'business_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'gallery_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'spot_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'description' => [
                'type'       => 'TEXT',
            ],
            'category' => [
                'type'       => 'ENUM',
                'constraint' => ['Historical', 'Cultural', 'Natural', 'Recreational', 'Religious', 'Adventure', 'Ecotourism', 'Urban'],
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,7',
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,7',
            ],
            'capacity' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'opening_time' => [
                'type'       => 'TIME',
            ],
            'closing_time' => [
                'type'       => 'TIME',
            ],
            'operating_days' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Open', 'Closed', 'Under Maintenance'],
                'default'    => 'Open',
            ],
            'price_per_person' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'child_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'senior_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'group_discount_percent' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
            ],
            'primary_image' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'average_rating' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,2',
                'default'    => 0.00,
            ],
            'total_reviews' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'popularity_score' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
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
        $this->forge->addKey('spot_id', true);
        $this->forge->addForeignKey('business_id', 'businesses', 'business_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tourist_spots');
    }

    public function down()
    {
        //
        $this->forge->dropTable('tourist_spots');
    }
}
