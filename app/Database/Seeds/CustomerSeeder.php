<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        //'user_id', 'type', 'phone', 'address', 'date_of_birth', 'nationality', 'id_number', 'emergency_contact', 'emergency_phone', 'notes', 'total_bookings', 'total_spent', 'created_at', 'updated_at'
        $data = [
            [
                'user_id' => 1,
                'type' => 'regular',
                'phone' => '1234567890',
                'address' => '123 Main St, Cityville',
                'date_of_birth' => '1990-01-01',
                'nationality' => 'Filipino',
                'emergency_contact' => 'Juan Dela Cruz',
                'emergency_phone' => '0987654321',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('customers')->insertBatch($data);
    }
}
