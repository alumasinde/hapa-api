<?php
declare(strict_types=1);

use App\Database\Connection;
use App\Support\Env;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
Env::load($root . '/.env');

$required = ['APP_ENV', 'APP_DEBUG', 'APP_URL', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'JWT_SECRET'];
$missing = [];

foreach ($required as $key) {
    if (trim((string) Env::get($key, '')) === '') {
        $missing[] = $key;
    }
}

if ($missing !== []) {
    fwrite(STDERR, 'Missing required environment values: ' . implode(', ', $missing) . PHP_EOL);
    exit(1);
}

$env = strtolower((string) Env::get('APP_ENV'));
$debug = filter_var(Env::get('APP_DEBUG', false), FILTER_VALIDATE_BOOL);
$secret = (string) Env::get('JWT_SECRET');

if ($env === 'production' && $debug) {
    fwrite(STDERR, 'APP_DEBUG must be false in production.' . PHP_EOL);
    exit(1);
}

if (strlen($secret) < 32 || str_contains(strtolower($secret), 'change-me')) {
    fwrite(STDERR, 'JWT_SECRET must be a non-placeholder secret of at least 32 characters.' . PHP_EOL);
    exit(1);
}

if ($env === 'production' && !str_starts_with((string) Env::get('APP_URL'), 'https://')) {
    fwrite(STDERR, 'APP_URL must use HTTPS in production.' . PHP_EOL);
    exit(1);
}

Connection::get()->query('SELECT 1')->fetchColumn();

echo 'Production environment verification passed.' . PHP_EOL;
echo 'Environment: ' . $env . PHP_EOL;
