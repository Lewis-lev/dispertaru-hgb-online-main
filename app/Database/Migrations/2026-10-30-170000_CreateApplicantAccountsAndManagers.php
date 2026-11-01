<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApplicantAccountsAndManagers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('email', false, true);
        $this->forge->createTable('pemohon_accounts', true);

        $this->forge->addColumn('pemohon', [
            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
                'default'  => null,
                'after'    => 'id',
            ],
        ]);
        $this->db->query('CREATE INDEX pemohon_user_id_idx ON pemohon (user_id)');

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('username', false, true);
        $this->forge->createTable('manager_accounts', true);

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'application_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'adminid' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
            ],
            'from_status' => [
                'type' => 'TINYINT',
                'null' => true,
            ],
            'to_status' => [
                'type' => 'TINYINT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['application_id', 'created_at']);
        $this->forge->addKey(['adminid', 'created_at']);
        $this->forge->createTable('admin_work_log', true);

        $this->db->query(
            'INSERT INTO admin_work_log (application_id, adminid, action, from_status, to_status, created_at)
             SELECT form_p3.id, verifikasi.adminid, ?, NULL, form_p3.status, COALESCE(verifikasi.tanggal, form_p3.tgl_masuk)
             FROM form_p3
             INNER JOIN verifikasi ON verifikasi.pemohon_id = form_p3.pemohon_id',
            ['baseline_imported']
        );
    }

    public function down()
    {
        $this->forge->dropTable('admin_work_log', true);
        $this->forge->dropTable('manager_accounts', true);
        $this->db->query('DROP INDEX pemohon_user_id_idx ON pemohon');
        $this->forge->dropColumn('pemohon', 'user_id');
        $this->forge->dropTable('pemohon_accounts', true);
    }
}
