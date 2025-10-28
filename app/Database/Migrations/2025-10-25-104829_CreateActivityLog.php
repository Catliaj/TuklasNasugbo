<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityLog extends Migration
{
    public function up()
    {
        //ActivityLog	log_id PK	INT	user_id FK	INT	activity_type	VARCHAR	entity_type	VARCHAR	entity_id	INT	description	TEXT	ip_address	VARCHAR	user_agent	TEXT	created_at	DATETIME
        $data = [
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
            'activity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'entity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'entity_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'description' => [
                'type'       => 'TEXT',
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
            ],
            'user_agent' => [
                'type'       => 'TEXT',
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ];
        $this->forge->addField($data);
        $this->forge->addKey('log_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'UserID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('activity_log');
    }

    public function down()
    {
        //
        $this->forge->dropTable('activity_log');
    }
}
