<?php

namespace App\Controllers;

/**
 * Controller Cetak
 * Menyediakan placeholder aman untuk modul pelaporan/cetak tambahan
 */
class Cetak extends BaseController
{
    public function index()
    {
        return redirect()->to(base_url('laporan'));
    }
}
