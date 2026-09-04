<?php
declare(strict_types=1);

use App\Database\Connection;
use App\Support\Env;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
Env::load($root . '/.env');

$requiredEnv = ['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'JWT_SECRET'];
$missingEnv = [];

foreach ($requiredEnv as $key) {
    $value = Env::get($key);
    if ($value === null || $value === '') {
        $missingEnv[] = $key;
    }
}

if ($missingEnv !== []) {
    fwrite(STDERR, 'Missing required environment values: ' . implode(', ', $missingEnv) . PHP_EOL);
    exit(1);
}

$requiredFiles = [
    'app/Services/FlashIntelligenceService.php',
    'app/Services/FlashLifecycleService.php',
    'app/Repository/FlashIntelligenceRepository.php',
    'app/Repository/UserTrustRepository.php',
    'app/Repository/AbuseRepository.php',
    'app/Controllers/SystemController.php',
    'bin/worker.php',
    'docs/openapi.yaml',
    'tests/bruno/07-production-intelligence/01-health.bru',
];

$missingFiles = array_values(array_filter(
    $requiredFiles,
    static fn (string $path): bool => !is_file($root . DIRECTORY_SEPARATOR . $path),
));

if ($missingFiles !== []) {
    fwrite(STDERR, 'Missing Phase 6 files:' . PHP_EOL . implode(PHP_EOL, $missingFiles) . PHP_EOL);
    exit(1);
}

$pdo = Connection::get();

$tables = [
    'flash_intelligence',
    'user_trust_profiles',
    'abuse_events',
    'jobs',
    'flashes',
    'flash_reports',
    'flash_observations',
];

$placeholders = implode(',', array_fill(0, count($tables), '?'));
$statement = $pdo->prepare(
    "SELECT table_name
     FROM information_schema.tables
     WHERE table_schema = DATABASE()
       AND table_name IN ({$placeholders})"
);
$statement->execute($tables);

$present = array_fill_keys($statement->fetchAll(PDO::FETCH_COLUMN), true);
$missingTables = array_values(array_filter($tables, static fn (string $table): bool => !isset($present[$table])));

if ($missingTables !== []) {
    fwrite(STDERR, 'Missing Phase 6 database tables: ' . implode(', ', $missingTables) . PHP_EOL);
    exit(1);
}

$pdo->query('SELECT 1')->fetchColumn();

echo "Phase 6 verification passed." . PHP_EOL;
echo "Database: " . Env::get('DB_DATABASE') . PHP_EOL;
echo "Required tables: OK" . PHP_EOL;
echo "Required runtime files: OK" . PHP_EOL;
