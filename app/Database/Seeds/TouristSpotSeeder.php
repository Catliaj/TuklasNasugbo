<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TouristSpotSeeder extends Seeder
{
    public function run()
    {
        //'business_id', 'spot_name', 'description', 'category', 'location', 'latitude', 'longitude', 'capacity', 'opening_time', 'closing_time', 'operating_days', 'status', 'price_per_person', 'child_price', 'senior_price', 'group_discount_percent', 'primary_image', 'average_rating', 'total_reviews', 'popularity_score', 'created_at', 'updated_at'

        $data = [
            [
                'business_id' => 1,
                'spot_name' => 'Ancient Ruins Park',
                'description' => 'Explore the remnants of an ancient civilization with guided tours and interactive exhibits.',
                'category' => 'Historical',
                'location' => '123 Heritage St, Nasugbo City',
                'capacity' => 200,
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'operating_days' => 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'status' => 'pending',
                'price_per_person' => 300.00,
                'child_price' => 150.00,
                'senior_price' => 200.00,
                'group_discount_percent' => 10.00,
                'primary_image' => 'ancient_ruins.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'business_id' => 1,
                'spot_name' => 'Sunset Beach',
                'description' => 'Relax on the golden sands and enjoy breathtaking sunsets at our pristine beach.',
                'category' => 'Nature',
                'location' => '456 Ocean Ave, Nasugbo City',
                'capacity' => 500,
                'opening_time' => '06:00:00',
                'closing_time' => '20:00:00',
                'operating_days' => 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'status' => 'approved',
                'price_per_person' => 0.00,
                'child_price' => 0.00,
                'senior_price' => 0.00,
                'group_discount_percent' => 0.00,
                'primary_image' => 'sunset_beach.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
        ];
        $this->db->table('tourist_spots')->insertBatch($data);

    }
}
