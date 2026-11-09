<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Handlers\DatabaseHandler; // Ubah ini

class Session extends BaseConfig
{
    // 1. Ubah driver ke DatabaseHandler
    public string $driver = DatabaseHandler::class;

    public string $cookieName = 'ci_session';

    // 2. Ubah durasi jadi 8 jam (28800 detik)
    public int $expiration = 28800;

    // 3. Ubah savePath menjadi NAMA TABEL database Anda
    public string $savePath = 'ci_sessions';

    public bool $matchIP = false;

    public int $timeToUpdate = 300;

    public bool $regenerateDestroy = false;

    public ?string $DBGroup = 'default'; // Pastikan ini 'default' atau null

    public int $lockRetryInterval = 100_000;

    public int $lockMaxRetries = 300;
}
