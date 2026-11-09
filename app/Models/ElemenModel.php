<?php

namespace App\Models;

use CodeIgniter\Model;

class ElemenModel extends Model
{
    protected $table            = 'elemen_penilaian';
    protected $primaryKey       = 'id_elemen';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['id_event', 'id_pos', 'nama_elemen'];
}
