<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SetupUkkSystem extends Migration
{
    public function up()
    {
        // 1. Tabel Events
        $this->forge->addField([
            'id_event' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nama_event' => ['type' => 'VARCHAR', 'constraint' => 100],
            'tanggal_mulai' => ['type' => 'DATE'],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
        ]);
        $this->forge->addKey('id_event', true);
        $this->forge->createTable('events');

        // 2. Tabel Pos
        $this->forge->addField([
            'id_pos' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nama_pos' => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('id_pos', true);
        $this->forge->createTable('pos_ukk');

        // 3. Tabel Elemen Penilaian
        $this->forge->addField([
            'id_elemen' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'id_event' => ['type' => 'INT'],
            'id_pos' => ['type' => 'INT'],
            'nama_elemen' => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey('id_elemen', true);
        $this->forge->createTable('elemen_penilaian');

        // 4. Tabel Siswa
        $this->forge->addField([
            'id_siswa' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nisn' => ['type' => 'VARCHAR', 'constraint' => 20],
            'nama_siswa' => ['type' => 'VARCHAR', 'constraint' => 100],
            'kelas' => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('id_siswa', true);
        $this->forge->createTable('siswa');

        // 5. Tabel Nilai Praktek (Logika Utama)
        $this->forge->addField([
            'id_nilai' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'id_event' => ['type' => 'INT'],
            'id_siswa' => ['type' => 'INT'],
            'id_elemen' => ['type' => 'INT'],
            'is_kompeten' => ['type' => 'ENUM', 'constraint' => ['0', '1'], 'default' => '0'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_nilai', true);
        $this->forge->createTable('nilai_praktek');

        // 6. Tabel Nilai Teori
        $this->forge->addField([
            'id_teori' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'id_event' => ['type' => 'INT'],
            'id_siswa' => ['type' => 'INT'],
            'nilai_angka' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
        ]);
        $this->forge->addKey('id_teori', true);
        $this->forge->createTable('nilai_teori');
    }

    public function down()
    {
        $this->forge->dropTable('nilai_teori');
        $this->forge->dropTable('nilai_praktek');
        $this->forge->dropTable('siswa');
        $this->forge->dropTable('elemen_penilaian');
        $this->forge->dropTable('pos_ukk');
        $this->forge->dropTable('events');
    }
}
