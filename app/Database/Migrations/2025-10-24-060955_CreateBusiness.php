<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBusiness extends Migration
{
    public function up()
    {
        //
        $data = [
            'business_id' => [
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
            'business_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'contact_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'contact_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
            ],
            'business_address' => [
                'type'       => 'TEXT',
            ],
            'logo_url' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Pending', 'Approved', 'Rejected'],
                'default'    => 'Pending',
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ];
        $this->forge->addField($data);
        $this->forge->addKey('business_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'UserID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('businesses');
    }

    public function down()
    {
        //
        $this->forge->dropTable('businesses');
    }
}
