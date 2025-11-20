<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SpotViewLogs extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'log_id' => [
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
            'viewed_at' => [
                'type'       => 'DATETIME',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('log_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'UserID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('spot_id', 'tourist_spots', 'spot_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('spot_view_logs');
    }

    public function down()
    {
        //
        $this->forge->dropTable('spot_view_logs');
    }
}
