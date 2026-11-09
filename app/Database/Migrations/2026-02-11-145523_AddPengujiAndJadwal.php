<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPengujiAndJadwal extends Migration
{
    public function up()
    {
        // 1. Tabel Mapping Penguji ke Pos (Per Event)
        $this->forge->addField([
            'id_event_penguji' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_event'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false],
            'id_user'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_pos'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false],
        ]);
        $this->forge->addKey('id_event_penguji', true);
        $this->forge->addForeignKey('id_event', 'events', 'id_event', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_pos', 'pos_ukk', 'id_pos', 'CASCADE', 'CASCADE');
        $this->forge->createTable('event_penguji');

        // 2. Tabel Penjadwalan Peserta (Per Event & Per Pos)
        $this->forge->addField([
            'id_jadwal'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_event'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false],
            'id_siswa'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false],
            'id_pos'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false],
            'tanggal_ujian'  => ['type' => 'DATE'],
            'jam_mulai'      => ['type' => 'TIME'],
            'jam_selesai'    => ['type' => 'TIME'],
        ]);
        $this->forge->addKey('id_jadwal', true);
        $this->forge->addForeignKey('id_event', 'events', 'id_event', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_siswa', 'siswa', 'id_siswa', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_pos', 'pos_ukk', 'id_pos', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penjadwalan');
    }

    public function down()
    {
        $this->forge->dropTable('penjadwalan');
        $this->forge->dropTable('event_penguji');
    }
}
