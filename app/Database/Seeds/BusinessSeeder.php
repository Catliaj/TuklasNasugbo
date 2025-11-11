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
                'status' => 'Pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
                'user_id' => 3,
                'business_name' => 'Sunset Cove Resort',
                'contact_email' => 'sunsetcove@gmail.com',
                'contact_phone' => '09456374891',
                'business_address' => 'Calayo Beach, Nasugbu, Batangas',
                'logo_url' => 'https://example.com/logos/sunset_cove.png',
                'status' => 'Pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
                'user_id' => 4,
                'business_name' => 'Ocean Breeze Inn',
                'contact_email' => 'oceanbreeze@gmail.com',
                'contact_phone' => '09356479201',
                'business_address' => 'Wawa, Nasugbu, Batangas',
                'logo_url' => 'https://example.com/logos/ocean_breeze.png',
                'status' => 'Pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
             [
                'user_id' => 5,
                'business_name' => 'Highlands Viewpoint Café',
                'contact_email' => 'highlandscafe@gmail.com',
                'contact_phone' => '09171230034',
                'business_address' => 'Tagaytay-Nasugbu Highway, Nasugbu, Batangas',
                'logo_url' => 'https://example.com/logos/highlands_viewpoint.png',
                'status' => 'Pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 6,
                'business_name' => 'Fortune Island Adventures',
                'contact_email' => 'fortuneisland@gmail.com',
                'contact_phone' => '09659372931',
                'business_address' => 'Fortune Island, Nasugbu, Batangas',
                'logo_url' => 'https://example.com/logos/fortune_island.png',
                'status' => 'Pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 7,
                'business_name' => 'Punta Fuego Getaway',
                'contact_email' => 'puntafuego@gmail.com',
                'contact_phone' => '09376451982',
                'business_address' => 'Punta Fuego, Nasugbu, Batangas',
                'logo_url' => 'https://example.com/logos/punta_fuego.png',
                'status' => 'Pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 8,
                'business_name' => 'Hamilo Coast Staycation',
                'contact_email' => 'hamilocoast@gmail.com',
                'contact_phone' => '09456783245',
                'business_address' => 'Hamilo Coast, Pico de Loro Cove, Nasugbu, Batangas',
                'logo_url' => 'https://example.com/logos/hamilo_coast.png',
                'status' => 'Pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
          ],
          [
                'user_id' => 9,
                'business_name' => 'Mountain Peak Eco Park',
                'contact_email' => 'mountainpeak@gmail.com',
                'contact_phone' => '09171230007',
                'business_address' => 'Barangay Banilad, Nasugbu, Batangas',
                'logo_url' => 'https://example.com/logos/mountain_peak.png',
                'status' => 'Pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 10,
                'business_name' => 'Nasugbu Dive Center',
                'contact_email' => 'nasugbudive@gmail.com',
                'contact_phone' => '096574923140',
                'business_address' => 'Apacible Blvd, Nasugbu, Batangas',
                'logo_url' => 'https://example.com/logos/nasugbu_dive.png',
                'status' => 'Pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

          
        ];
        $this->db->table('businesses')->insertBatch($data);
    }
}
