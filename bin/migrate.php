<?php

declare(strict_types=1);

use App\Database\Connection;
use App\Support\Env;
use PDO;

require dirname(__DIR__) . '/vendor/autoload.php';

Env::load(dirname(__DIR__) . '/.env');

$root = dirname(__DIR__);
$target = $argv[1] ?? 'all';
$mode = $argv[2] ?? '';

$directories = [
    'app' => $root . '/database/migrations/app',
    'admin' => $root . '/database/migrations/admin',
];

if (!isset($directories[$target]) && $target !== 'all' && $target !== '--status') {
    fwrite(STDERR, "Usage: php bin/migrate.php [all|app|admin] [--status]\n");
    exit(1);
}

if ($target === '--status') {
    $target = 'all';
    $mode = '--status';
}

$pdo = Connection::get();

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS schema_migrations (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL UNIQUE,
        applied_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
);

$selected = $target === 'all' ? $directories : [$target => $directories[$target]];
$migrations = [];

foreach ($selected as $scope => $directory) {
    if (!is_dir($directory)) {
        continue;
    }

    foreach (glob($directory . '/*.sql') ?: [] as $file) {
        $name = $scope . '/' . basename($file);
        $migrations[$name] = $file;
    }
}

uksort($migrations, static function (string $left, string $right): int {
    [$leftScope, $leftName] = explode('/', $left, 2);
    [$rightScope, $rightName] = explode('/', $right, 2);

    $order = strnatcmp($leftName, $rightName);

    return $order !== 0 ? $order : strcmp($leftScope, $rightScope);
});

$applied = $pdo->query('SELECT migration FROM schema_migrations ORDER BY migration')->fetchAll(PDO::FETCH_COLUMN);
$applied = array_fill_keys($applied, true);

$pending = [];

foreach ($migrations as $name => $file) {
    $state = isset($applied[$name]) ? 'APPLIED' : 'PENDING';

    if (!isset($applied[$name])) {
        $pending[$name] = $file;
    }

    printf("[%s] %s\n", $state === 'APPLIED' ? '✓' : ' ', $name);
}

if ($mode === '--status') {
    $missing = array_diff(array_keys($applied), array_keys($migrations));

    foreach ($missing as $name) {
        printf("[!] MISSING %s\n", $name);
    }

    printf("\n%d pending migration(s).\n", count($pending));
    exit(0);
}

if ($pending === []) {
    echo "\nNo pending migrations. Database is up to date.\n";
    exit(0);
}

echo "\nApplying pending migrations...\n";

foreach ($pending as $name => $file) {
    $sql = trim((string) file_get_contents($file));

    if ($sql === '') {
        fwrite(STDERR, "Migration is empty: {$name}\n");
        exit(1);
    }

    try {
        $pdo->exec($sql);

        $statement = $pdo->prepare('INSERT INTO schema_migrations (migration, applied_at) VALUES (:migration, UTC_TIMESTAMP())');
        $statement->execute(['migration' => $name]);

        printf("[✓] Applied %s\n", $name);
    } catch (Throwable $exception) {
        fwrite(STDERR, "[✗] Failed {$name}: {$exception->getMessage()}\n");
        exit(1);
    }
}

echo "\nMigration complete.\n";
