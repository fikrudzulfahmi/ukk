<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\EventModel;

class Auth extends BaseController
{
    public function index()
    {
        // Jika sudah login, arahkan sesuai role agar tidak nyasar ke admin dashboard
        if (session()->get('logged_in')) {
            if (session()->get('role') == 'penguji') {
                return redirect()->to('/penilaian');
            }
            return redirect()->to('/dashboard');
        }
        $logo_smki = base_url('assets/img/logosmki.png');
        return view('auth/login', ['logo_smki' => $logo_smki]);
    }

    public function login()
    {
        $userModel = new UserModel();
        $eventModel = new EventModel(); // Inisialisasi EventModel

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {

            // CARI EVENT YANG SEDANG AKTIF
            // Asumsi di tabel event ada kolom 'status' (1 untuk aktif, 0 untuk tidak)
            $eventAktif = $eventModel->where('is_active', 1)->first();

            $sessionData = [
                'id_user'         => $user['id_user'],
                'username'        => $user['username'],
                'nama_lengkap'    => $user['nama_lengkap'],
                'role'            => $user['role'],
                'logged_in'       => true,
                'active_event_id' => $eventAktif ? $eventAktif['id_event'] : null, // Simpan ID Event ke session
                'nama_event'      => $eventAktif ? $eventAktif['nama_event'] : 'Tidak Ada Event Aktif',
                'active_event_capacity'  => $eventAktif ? $eventAktif['kapasitas_sesi'] : 0, // Simpan kapasitas sesi ke session
                'tahun_pelajaran' => $eventAktif ? $eventAktif['tahun_pelajaran'] : 'N/A', // Simpan tahun pelajaran ke session
            ];
            session()->set($sessionData);

            // Jika tidak ada event aktif, beri peringatan (opsional)
            if (!$eventAktif && $user['role'] == 'admin') {
                return redirect()->to('/event')->with('error', 'Login sukses, tapi belum ada Event yang diaktifkan!');
            }

            // REDIRECT BERDASARKAN ROLE
            if ($user['role'] == 'penguji') {
                return redirect()->to('/penilaian');
            } else {
                return redirect()->to('/dashboard');
            }
        }

        return redirect()->back()->with('error', 'Username atau Password salah!');
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/'); // Arahkan ke rute login yang sudah kita buat tadi
    }
}
