<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeVerificationAdminOptional extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('verifikasi', [
            'adminid' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => null,
            ],
        ]);
    }

    public function down()
    {
        $this->db->query(
            'UPDATE verifikasi
             SET adminid = (SELECT adminid FROM admin ORDER BY adminid ASC LIMIT 1)
             WHERE adminid IS NULL'
        );

        $this->forge->modifyColumn('verifikasi', [
            'adminid' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => 1,
            ],
        ]);
    }
}
