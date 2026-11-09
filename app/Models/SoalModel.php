<?php

namespace App\Models;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table            = 'soal_teori';
    protected $primaryKey       = 'id_soal';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_event',
        'pertanyaan',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'jawaban_benar'
    ];
}
