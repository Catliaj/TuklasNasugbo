<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        //
        $this->call('UserSeeder');
        $this->call('CustomerSeeder');
        $this->call('BusinessSeeder');
        $this->call('TouristSpotSeeder');
        $this->call('BookingSeeder');
        echo "✅ All seeders executed successfully!\n";
    }
}
