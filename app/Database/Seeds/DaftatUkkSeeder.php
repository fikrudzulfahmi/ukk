<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DaftatUkkSeeder extends Seeder
{
    public function run()
    {
        // Tambahkan di dalam method run()
        $userData = [
            [
                'username'      => 'admin',
                'password'      => password_hash('admin123', PASSWORD_DEFAULT),
                'nama_lengkap'  => 'Administrator UKK',
                'role'          => 'admin'
            ],
            [
                'username'      => 'fikru',
                'password'      => password_hash('penguji123', PASSWORD_DEFAULT),
                'nama_lengkap'  => 'Fikru Dzul Fahmi, S.T. (Internal)',
                'role'          => 'penguji'
            ]
        ];
        $this->db->table('users')->insertBatch($userData);
    }
}
