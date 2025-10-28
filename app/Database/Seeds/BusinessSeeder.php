<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run()
    {
        // 'user_id', 'business_name', 'contact_email', 'contact_phone', 'business_address', 'tax_id', 'logo_url', 'status', 'created_at', 'updated_at'
        $data = [
            [
                'user_id' => 2,
                'business_name' => 'Sunset Tours',
                'contact_email' => 'businessEmail@gmail.com',
                'contact_phone' => '09171234567',
                'business_address' => '456 Beach Rd, Seaside City',
                'logo_url' => 'https://example.com/logos/sunset_tours.png',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 3,
                'business_name' => 'Sunset Tours',
                'contact_email' => 'businessEmail@gmail.com',
                'contact_phone' => '09171234567',
                'business_address' => '456 Beach Rd, Seaside City',
                'logo_url' => 'https://example.com/logos/sunset_tours.png',
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('businesses')->insertBatch($data);
    }
}
