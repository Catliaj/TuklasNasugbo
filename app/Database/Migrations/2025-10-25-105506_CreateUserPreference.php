<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserPreference extends Migration
{
    public function up()
    {
        // user_preferences
        // preference_id PK INT user_id FK INT category VARCHAR weight DECIMAL created_at DATETIME updated_at DATETIME
        $fields = [
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
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
                'default'    => null,
                'comment'    => 'Comma-separated categories or single category (example: "Natural,Cultural")',
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

        $this->forge->addField($fields);
        $this->forge->addKey('preference_id', true);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'UserID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_preferences');
    }

    public function down()
    {
        $this->forge->dropTable('user_preferences');
    }
}