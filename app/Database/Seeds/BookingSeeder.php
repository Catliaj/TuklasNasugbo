<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run()
    {
        // 'spot_id', 'customer_id', 'booking_date', 'visit_date', 'visit_time', 'num_adults', 'num_children', 'num_seniors', 'total_guests', 'price_per_person', 'subtotal', 'discount_amount', 'tax_amount', 'total_price', 'booking_status', 'payment_status', 'special_requests', 'cancellation_reason', 'internal_notes', 'created_at', 'updated_at', 'confirmed_at', 'cancelled_at', 'completed_at'
        $data = [
            [
                'spot_id' => 1,
                'customer_id' => 1,
                'booking_date' => date('Y-m-d H:i:s'),
                'visit_date' => '2024-12-15',
                'visit_time' => '10:00:00',
                'num_adults' => 2,
                'num_children' => 1,
                'num_seniors' => 0,
                'total_guests' => 3,
                'price_per_person' => 500.00,
                'subtotal' => 1500.00,
                'discount_amount' => 0.00,
                'tax_amount' => 150.00,
                'total_price' => 1650.00,
                'booking_status' => 'confirmed',
                'payment_status' => 'paid',
                'special_requests' => 'Need wheelchair access',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'spot_id' => 1,
                'customer_id' => 1,
                'booking_date' => date('Y-m-d H:i:s'),
                'visit_date' => '2025-09-16',
                'visit_time' => '14:00:00',
                'num_adults' => 1,
                'num_children' => 0,
                'num_seniors' => 1,
                'total_guests' => 2,
                'price_per_person' => 500.00,
                'subtotal' => 1000.00,
                'discount_amount' => 50.00,
                'tax_amount' => 95.00,
                'total_price' => 1045.00,
                'booking_status' => 'pending',
                'payment_status' => 'unpaid',
                'special_requests' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

        ];
        $this->db->table('bookings')->insertBatch($data);
    }
}
