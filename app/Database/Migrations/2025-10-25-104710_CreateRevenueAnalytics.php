<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRevenueAnalytics extends Migration
{
    public function up()
    {
        //RevenueAnalytics	analytics_id PK	INT	business_id FK	INT	spot_id FK	INT	by_Date	DATE	total_bookings	INT	confirmed_bookings	INT	cancelled_bookings	INT	total_visitors	INT	gross_revenue	DECIMAL	discounts	DECIMAL	refunds	DECIMAL	net_revenue	DECIMAL	avg_booking_value	DECIMAL	avg_party_size	DECIMAL	created_at	DATETIME
        $data = [
            'analytics_id' => [
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
            'spot_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'by_Date' => [
                'type'       => 'DATE',
            ],
            'total_bookings' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'confirmed_bookings' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'cancelled_bookings' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'total_visitors' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'gross_revenue' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'discounts' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'refunds' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'net_revenue' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'avg_booking_value' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'avg_party_size' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ];
        $this->forge->addField($data);
        $this->forge->addKey('analytics_id', true);
        $this->forge->addForeignKey('business_id', 'businesses', 'business_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('spot_id', 'tourist_spots', 'spot_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('revenue_analytics');

    }

    public function down()
    {
        //
        $this->forge->dropTable('revenue_analytics');
    }
}
