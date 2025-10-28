<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpotGallery extends Migration
{
    public function up()
    {
        //SpotImage	image_id PK	INT	spot_id FK	INT	image	BLOB
        $data = [
            'image_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'spot_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'image' => [
                'type'       => 'BLOB',
            ],
        ];
        $this->forge->addField($data);
        $this->forge->addKey('image_id', true);
        $this->forge->addForeignKey('spot_id', 'tourist_spots', 'spot_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('spot_gallery');

    }

    public function down()
    {
        //
        $this->forge->dropTable('spot_gallery');
    }
}
