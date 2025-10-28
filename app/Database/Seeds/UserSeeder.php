<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        //`UserID`, `FirstName`, `MiddleName`, `LastName`, `email`, `password`, `role`, `LastLogin`, `created_at`, `updated_at`

        $data = [
            [
                'FirstName' => 'Admin',
                'MiddleName' => 'A',
                'LastName' => 'User',
                'email' => 'admin@gmail.com',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'role' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'FirstName' => 'Spot',
                'MiddleName' => 'O',
                'LastName' => 'Owner',
                'email' => 'spot@gmail.com',
                'password' => password_hash('spot123', PASSWORD_BCRYPT),
                'role' => 'Spot Owner',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'FirstName' => 'Spot',
                'MiddleName' => 'O',
                'LastName' => 'Owner',
                'email' => 'spot1@gmail.com',
                'password' => password_hash('spot123', PASSWORD_BCRYPT),
                'role' => 'Spot Owner',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
         $this->db->table('users')->insertBatch($data);


    }
}
