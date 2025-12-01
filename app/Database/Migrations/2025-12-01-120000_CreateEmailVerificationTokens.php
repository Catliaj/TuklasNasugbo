<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailVerificationTokens extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
            ],
            'payload' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('token', false, true); // unique
        $this->forge->addKey('email');

        $this->forge->createTable('email_verification_tokens', true);
    }

    public function down()
    {
        $this->forge->dropTable('email_verification_tokens', true);
    }
}
