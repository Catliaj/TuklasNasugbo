<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserPreference extends Migration
{
    public function up()
    {
        //UserPreferences	preference_id PK	INT	user_id FK	INT	category	ENUM	weight	DECIMAL	created_at	DATETIME	updated_at	DATETIME
        $data = [
            'preference_id' => [
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
            'category' => [
                'type'       => 'ENUM',
                'constraint' => ['Nature', 'History', 'Adventure', 'Culture', 'Relaxation', 'Food'],
            ],
            'weight' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 1.00,
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
        $this->forge->addKey('preference_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'UserID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_preferences');
    }

    public function down()
    {
        //
        $this->forge->dropTable('user_preferences');
    }
}
