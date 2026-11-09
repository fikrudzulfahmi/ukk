<?php

namespace App\Models;

use CodeIgniter\Model;

class CatatanPenilaianModel extends Model
{
    protected $table            = 'catatan_penilaian';
    protected $primaryKey       = 'id_catatan';
    protected $allowedFields    = ['id_event', 'id_pos', 'id_siswa', 'catatan'];
    protected $useTimestamps    = true;
}
