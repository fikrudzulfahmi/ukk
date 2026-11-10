<?php

namespace App\Libraries;

use \RuntimeException;

/**
 * Klien API Data Induk Siswa (induk.smkislam1blitar.sch.id).
 *
 * Konfigurasi via .env (JANGAN pernah commit key ke repo):
 *   INDUK_API_BASE    = "https://induk.smkislam1blitar.sch.id"
 *   INDUK_API_KEY    = "<kunci penuh dari tim API>"
 *   INDUK_API_TIMEOUT = 15
 *
 * Kunci di-read dari server-side env saja — tidak pernah dikirim ke browser.
 */
class IndukClient
{
    private int $timeout;

    public function __construct()
    {
        $this->timeout = (int) env('INDUK_API_TIMEOUT', 15);
    }

    private function baseUrl(): string
    {
        $base = rtrim((string) env('INDUK_API_BASE', 'https://induk.smkislam1blitar.sch.id'), '/');
        return $base ?: 'https://induk.smkislam1blitar.sch.id';
    }

    private function apiKey(): string
    {
        $key = (string) env('INDUK_API_KEY', '');
        if ($key === '') {
            throw new RuntimeException(
                'INDUK_API_KEY belum diisi di .env server. Hubungi admin API untuk kunci penuh.',
                0
            );
        }
        return $key;
    }

    /**
     * GET /apiSiswa/kelas — daftar kelas + jumlah siswa aktif.
     * @return array<int,array<string,mixed>>
     */
    public function daftarKelas(): array
    {
        $body = $this->httpGet('/apiSiswa/kelas', []);
        return is_array($body['data'] ?? null) ? $body['data'] : [];
    }

    /**
     * GET /apiSiswa/siswa?kelas=X&tingkat=Y&nisn=Z&q=W&page=N&per_page=P
     * @return array{data: array<int,array<string,mixed>>, total: int, total_halaman: int, per_page: int}
     */
    public function siswa(array $params = []): array
    {
        $allowed = [];
        foreach (['kelas', 'tingkat', 'nisn', 'q', 'page', 'per_page', 'status'] as $k) {
            if (isset($params[$k]) && $params[$k] !== '') {
                $allowed[$k] = $params[$k];
            }
        }

        $body = $this->httpGet('/apiSiswa/siswa', $allowed);

        return [
            'data'           => is_array($body['data'] ?? null) ? $body['data'] : [],
            'total'          => (int) ($body['meta']['total'] ?? 0),
            'total_halaman'  => (int) ($body['meta']['total_halaman'] ?? 1),
            'per_page'       => (int) ($body['meta']['per_page'] ?? 0),
        ];
    }

    /**
     * Ambil SATU rombel lengkap (loop pagination, per_page maksimal 500).
     */
    public function rombelSiswa(string $kelas, string $status = 'aktif'): array
    {
        $daftar = [];
        $page   = 1;
        do {
            $res = $this->siswa(['kelas' => $kelas, 'status' => $status, 'page' => $page, 'per_page' => 500]);
            $daftar = array_merge($daftar, $res['data']);
            if ($res['total_halaman'] > 1 && $page < $res['total_halaman']) {
                $page++;
            } else {
                break;
            }
        } while (true);
        return $daftar;
    }

    /**
     * Ambil seluruh rombel pada satu tingkat (10/11/12).
     */
    public function tingkatSiswa(string $tingkat, string $status = 'aktif'): array
    {
        $kelasList = $this->daftarKelas();
        $hasil     = [];
        foreach ($kelasList as $k) {
            if ((string) ($k['tingkat'] ?? '') !== (string) $tingkat) {
                continue;
            }
            $hasil = array_merge($hasil, $this->rombelSiswa((string) $k['kelas'], $status));
        }
        return $hasil;
    }

    /**
     * @throws RuntimeException bila HTTP bukan 200 atau format tak dikenal
     */
    private function httpGet(string $path, array $query = []): array
    {
        $url = $this->baseUrl() . $path;
        if ($query !== []) {
            $url .= '?' . http_build_query($query);
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_HTTPHEADER     => [
                'X-API-KEY: ' . $this->apiKey(),
                'Accept: application/json',
            ],
        ]);

        $raw    = curl_exec($ch);
        $errno  = curl_errno($ch);
        $errStr = curl_error($ch);
        $code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($raw === false) {
            throw new RuntimeException('Gagal terhubung API Induk: ' . ($errStr ?: 'curl error ' . $errno));
        }

        $json = json_decode((string) $raw, true);
        if ($json === null) {
            throw new RuntimeException('Respons API Induk bukan JSON valid (HTTP ' . $code . ').');
        }

        if (!is_array($json) || !in_array($code, [200], true)) {
            $msg = is_array($json) && isset($json['message']) ? (string) $json['message'] : 'HTTP ' . $code;
            throw new RuntimeException('API Induk menolak request: ' . $msg, (int) $code);
        }

        if (($json['status'] ?? '') === 'error') {
            throw new RuntimeException((string) ($json['message'] ?? 'Kesalahan API Induk'), (int) $code);
        }

        return $json;
    }
}
