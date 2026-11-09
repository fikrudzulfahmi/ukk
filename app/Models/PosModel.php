<?php

namespace App\Models;

use CodeIgniter\Model;

class PosModel extends Model
{
    protected $table            = 'pos_ukk';
    protected $primaryKey       = 'id_pos';
    protected $allowedFields    = ['id_event', 'nama_pos']; // Hanya butuh nama pos (Contoh: CVT, Engine)
}
