<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminManagementFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('admin', [
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 1,
                'null'       => false,
            ],
            'last_seen_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('admin', ['is_active', 'last_seen_at']);
    }
}
