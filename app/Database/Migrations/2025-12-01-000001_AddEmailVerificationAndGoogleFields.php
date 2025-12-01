<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailVerificationAndGoogleFields extends Migration
{
    public function up()
    {
        // Add columns to users table
        $fields = [
            'email_verified' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'password'
            ],
            'google_id' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
                'after' => 'email_verified'
            ],
        ];
        $this->forge->addColumn('users', $fields);

        // Create email_verifications table
        $verification = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'otp_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'attempts' => [
                'type' => 'TINYINT',
                'constraint' => 2,
                'default' => 0,
            ],
            'verified_at' => [
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
        ];
        $this->forge->addField($verification);
        $this->forge->addKey('id', true);
        $this->forge->addKey('email');
        $this->forge->createTable('email_verifications');
    }

    public function down()
    {
        // Drop added columns & table
        $this->forge->dropColumn('users', 'email_verified');
        $this->forge->dropColumn('users', 'google_id');
        $this->forge->dropTable('email_verifications');
    }
}
