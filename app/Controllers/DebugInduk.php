<?php

namespace App\Controllers;

use CodeIgniter\HTTP\URLRequest;
use App\Controllers\BaseController;

/**
 * ENDPOINT DEBUG SEMENTARA — hapus setelah diagnosis.
 * GET /debug_induk
 *
 * Melaporkan: env key tersedia? cURL outbound jalan? API jawab apa?
 * TIDAK mengembalikan nilai API key (hanya status & panjang).
 */
class DebugInduk extends BaseController
{
    public function index()
    {
        $key    = env('INDUK_API_KEY');
        $base   = env('INDUK_API_BASE', 'https://induk.smkislam1blitar.sch.id');
        $out = [
            'php'            => PHP_VERSION,
            'curl_ext'       => function_exists('curl_init'),
            'env_base'       => $base,
            'env_key_set'     => is_string($key) && $key !== '',
            'env_key_len'     => is_string($key) ? strlen($key) : 0,
            'env_key_sha8'   => is_string($key) ? substr(hash('sha256', $key), 0, 8) : null,
            'file_env'       => [
                'exists'        => is_file(FCPATH . '/../.env'),
                'has_key_line'  => is_file(FCPATH . '/../.env') &&
                                    (bool) preg_grep('/^INDUK_API_KEY/i', file(FCPATH . '/../.env', FILE_IGNORE_NEW_LINES)),
                'has_base_line' => is_file(FCPATH . '/../.env') &&
                                    (bool) preg_grep('/^INDUK_API_BASE/i', file(FCPATH . '/../.env', FILE_IGNORE_NEW_LINES)),
            ],
        ];

        // Uji outbound cURL langsung
        if (function_exists('curl_init')) {
            $ch = curl_init($base . '/apiSiswa/kelas');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_HTTPHEADER     => ['X-API-KEY: ' . $key, 'Accept: application/json'],
                CURLOPT_SSL_VERIFYPEER => true,
            ]);
            $body  = curl_exec($ch);
            $out['api_http']      = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $out['api_error']     = curl_error($ch) ?: null;
            $out['api_body_head'] = is_string($body) ? mb_substr(trim($body), 0, 300) : null;
            curl_close($ch);
        }

        // Juga lewat library (path yang dipakai controller)
        try {
            $kelas = \Config\Services::induk(false)->daftarKelas();
            $out['library_test'] = ['ok' => true, 'jumlah' => count($kelas)];
        } catch (\Throwable $e) {
            $out['library_test'] = ['ok' => false, 'error' => $e->getMessage()];
        }

        return $this->response->setJSON($out);
    }
}
