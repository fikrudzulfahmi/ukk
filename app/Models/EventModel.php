<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table            = 'events';
    protected $primaryKey       = 'id_event';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['nama_event', 'ketua', 'tanggal_mulai', 'is_active', 'tahun_pelajaran', 'kapasitas_sesi', 'is_teori',];
}
