<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalSiswaModel extends Model
{
    protected $table            = 'penjadwalan';
    protected $primaryKey       = 'id_jadwal';
    protected $allowedFields    = [
        'id_event',
        'id_siswa',
        'id_pos',
        'tanggal_ujian',
        'urutan',
        'jam_mulai',
        'jam_selesai'
    ];
}
