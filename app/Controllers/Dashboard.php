<?php

namespace App\Controllers;


class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $id_event = session()->get('active_event_id');

        $event_aktif = $db->table('events')
            ->where('id_event', $id_event)
            ->get()->getRowArray();

        $data = [
            'title'       => 'Dashboard Utama',
            'event_aktif' => $event_aktif,

            // 1. Statistik Peserta
            'total_siswa' => $db->table('siswa')->where('id_event', $id_event)->countAllResults(),

            // 2. Statistik Penguji (Asumsi tabel penguji/users)
            'total_penguji' => $db->table('users')->where('role', 'penguji')->countAllResults(),

            // 3. Statistik Kriteria/Pos
            'total_pos' => $db->table('pos_ukk')->where('id_event', $id_event)->countAllResults(),

            // 4. Progress Teori (Untuk Progress Bar)
            'sudah_teori' => $db->table('nilai_teori')->where('id_event', $id_event)->countAllResults(),

            // 5. Total Event
            'total_event' => $db->table('events')->countAllResults(),
        ];

        return view('admin/dashboard_v2', $data);
    }
}
