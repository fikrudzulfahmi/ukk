<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiPraktekModel extends Model
{
    protected $table            = 'nilai_praktek';
    protected $primaryKey       = 'id_nilai';
    protected $allowedFields    = ['id_event', 'id_siswa', 'id_elemen', 'is_kompeten', 'created_at', 'updated_at'];
    protected $useTimestamps    = true; // Karena ada kolom updated_at
    protected $updatedField     = 'updated_at';
}
