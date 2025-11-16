<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        //'user_id', 'type', 'phone', 'address', 'date_of_birth', 'emergency_contact', 'emergency_phone', 'created_at', 'updated_at'
        $data = [
            [
                'user_id' => 13,
                'type' => 'regular',
                'phone' => '1234567890',
                'address' => '123 Main St, Cityville',
                'date_of_birth' => '1990-01-01',
                'emergency_contact' => 'Juan Dela Cruz',
                'emergency_phone' => '0987654321',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 14,
                'type' => 'regular',
                'phone' => '2345678901',
                'address' => '456 Oak St, Townsville',
                'date_of_birth' => '1985-05-15',
                'emergency_contact' => 'Maria Santos',
                'emergency_phone' => '0876543210',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 15,
                'type' => 'regular',
                'phone' => '3456789012',
                'address' => '789 Pine St, Villageville',
                'date_of_birth' => '1992-09-30',
                'emergency_contact' => 'Pedro Reyes',
                'emergency_phone' => '0765432109',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 16,
                'type' => 'regular',
                'phone' => '4567890123',
                'address' => '101 Maple St, Hamletville',
                'date_of_birth' => '1988-12-20',
                'emergency_contact' => 'Ana Lopez',
                'emergency_phone' => '0654321098',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 17,
                'type' => 'regular',
                'phone' => '5678901234',
                'address' => '202 Birch St, Boroughville',
                'date_of_birth' => '1995-07-25',
                'emergency_contact' => 'Luis Garcia',
                'emergency_phone' => '0543210987',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 18,
                'type' => 'regular',
                'phone' => '6789012345',
                'address' => '303 Cedar St, Metroville',
                'date_of_birth' => '1991-03-10',
                'emergency_contact' => 'Carmen Diaz',
                'emergency_phone' => '0432109876',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 19,
                'type' => 'regular',
                'phone' => '7890123456',
                'address' => '404 Spruce St, Capitolville',
                'date_of_birth' => '1987-11-05',
                'emergency_contact' => 'Ramon Cruz',
                'emergency_phone' => '0974836512',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 20,
                'type' => 'regular',
                'phone' => '8901234567',
                'address' => '505 Walnut St, Urbantown',
                'date_of_birth' => '1993-06-18',
                'emergency_contact' => 'Isabel Fernandez',
                'emergency_phone' => '0973546892',
                'created_at' => date ('Y-m-d H:i:s'),
                'updated_at' => date ('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 21,
                'type' => 'regular',
                'phone' => '0982345768',
                'address' => '606 Chestnut St, Downtown',
                'date_of_birth' => '1994-02-22',
                'emergency_contact' => 'Victor Ramos',
                'emergency_phone' => '09653478292',
                'created_at' => date ('Y-m-d H:i:s'),
                'updated_at' => date ('Y-m-d H:i:s'),
            ],

            [
                'user_id' => 22,
                'type' => 'regular',
                'phone' => '09432567894',
                'address' => '707 Poplar St, Suburbia',
                'date_of_birth' => '1989-08-14',
                'emergency_contact' => 'Gloria Mendoza',
                'emergency_phone' => '09567483920',
                'created_at' => date ('Y-m-d H:i:s'),
                'updated_at' => date ('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 23,
                'type' => 'regular',
                'phone' => '0912345678',
                'address' => '808 Ash St, Countryside',
                'date_of_birth' => '1996-04-12',
                'emergency_contact' => 'Felipe Navarro',
                'emergency_phone' => '0987654320',
                'created_at' => date ('Y-m-d H:i:s'),
                'updated_at' => date ('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 24,
                'type' => 'regular',
                'phone' => '0923456789',
                'address' => '909 Willow St, Lakeside',
                'date_of_birth' => '1990-10-30',
                'emergency_contact' => 'Sofia Castillo',
                'emergency_phone' => '0976543210',
                'created_at' => date ('Y-m-d H:i:s'),
                'updated_at' => date ('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 25,
                'type' => 'regular',
                'phone' => '0934567890',
                'address' => '1001 Cypress St, Riverside',
                'date_of_birth' => '1986-09-09',
                'emergency_contact' => 'Jorge Silva',
                'emergency_phone' => '0965432109',
                'created_at' => date ('Y-m-d H:i:s'),
                'updated_at' => date ('Y-m-d H:i:s'),
            ],
           

        ];
        $this->db->table('customers')->insertBatch($data);
    }
}
