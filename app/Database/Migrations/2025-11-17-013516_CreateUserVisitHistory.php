<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserVisitHistory extends Migration
{
    public function up()
    {
        $fields = [
            'history_id' => [
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

            // Whether user liked this spot (for Naive Bayes training)
            'liked' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'comment'    => 'TRUE if liked, FALSE if disliked',
            ],

            'last_visited_at' => [
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

        $this->forge->addField($fields);
        $this->forge->addKey('history_id', true);

        // Foreign keys
        $this->forge->addForeignKey('user_id', 'customers', 'customer_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('spot_id', 'tourist_spots', 'spot_id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('user_visit_history');
    }

    public function down()
    {
        $this->forge->dropTable('user_visit_history');
    }
}
