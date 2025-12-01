<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveGroupDiscountFromTouristSpots extends Migration
{
    public function up()
    {
        // Drop group_discount_percent column if it exists
        if ($this->db->fieldExists('group_discount_percent', 'tourist_spots')) {
            $this->forge->dropColumn('tourist_spots', 'group_discount_percent');
        }
    }

    public function down()
    {
        // Re-add group_discount_percent column (DECIMAL 5,2) if needed
        if (!$this->db->fieldExists('group_discount_percent', 'tourist_spots')) {
            $fields = [
                'group_discount_percent' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '5,2',
                    'null'       => true,
                ],
            ];
            $this->forge->addColumn('tourist_spots', $fields);
        }
    }
}
