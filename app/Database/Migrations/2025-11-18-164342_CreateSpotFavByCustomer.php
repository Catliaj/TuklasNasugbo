<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpotFavByCustomer extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'fav_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'spot_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'favorited_at' => [
                'type'       => 'DATETIME',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('fav_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'UserID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('spot_id', 'tourist_spots', 'spot_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('spot_fav_by_customer');

    }

    public function down()
    {
        //
        $this->forge->dropTable('spot_fav_by_customer');
    }
}
