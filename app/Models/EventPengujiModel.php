<?php

namespace App\Models;

use CodeIgniter\Model;

class EventPengujiModel extends Model
{
    protected $table            = 'event_penguji';
    protected $primaryKey       = 'id_event_penguji';
    protected $allowedFields    = ['id_event', 'id_user', 'id_pos', 'tanggal'];
}
