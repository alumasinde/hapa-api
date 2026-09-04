<?php
declare(strict_types=1);

use App\Database\Connection;
use App\Services\FlashLifecycleService;
use App\Support\Env;
use App\Support\Logger;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
Env::load($root . '/.env');

$once = in_array('--once', $argv, true);

do {
    $expired = (new FlashLifecycleService())->expireDue(500);
    Logger::info('worker.lifecycle', ['expired' => $expired]);

    $pdo = Connection::get();
    $job = null;

    try {
        $pdo->beginTransaction();

        // Claim atomically. This avoids a long-running SELECT lock and keeps the
        // worker compatible with the MariaDB versions supported by Hapa.
        $statement = $pdo->prepare(
            "SELECT id, job_type, payload
             FROM jobs
             WHERE status = 'pending'
               AND available_at <= UTC_TIMESTAMP()
             ORDER BY id
             LIMIT 1
             FOR UPDATE"
        );
        $statement->execute();
        $job = $statement->fetch();

        if ($job) {
            $claim = $pdo->prepare(
                "UPDATE jobs
                 SET status = 'processing',
                     attempts = attempts + 1,
                     reserved_at = UTC_TIMESTAMP(),
                     updated_at = UTC_TIMESTAMP()
                 WHERE id = :id AND status = 'pending'"
            );
            $claim->execute(['id' => $job['id']]);

            if ($claim->rowCount() !== 1) {
                $job = null;
            }
        }

        $pdo->commit();
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        Logger::error('worker.job.claim_failed', ['message' => $exception->getMessage()]);
    }

    if ($job) {
        try {
            // Phase 6 has no executable domain job types yet. Claiming and
            // completing unknown jobs would silently lose work, so leave them
            // pending until a handler is registered.
            Logger::info('worker.job.unhandled', [
                'job_id' => (int) $job['id'],
                'job_type' => (string) $job['job_type'],
            ]);

            $pdo->prepare(
                "UPDATE jobs
                 SET status = 'pending',
                     reserved_at = NULL,
                     updated_at = UTC_TIMESTAMP()
                 WHERE id = :id AND status = 'processing'"
            )->execute(['id' => $job['id']]);
        } catch (Throwable $exception) {
            Logger::error('worker.job.failed', [
                'job_id' => (int) $job['id'],
                'message' => $exception->getMessage(),
            ]);

            $pdo->prepare(
                "UPDATE jobs
                 SET status = 'failed',
                     last_error = :error,
                     updated_at = UTC_TIMESTAMP()
                 WHERE id = :id"
            )->execute([
                'id' => $job['id'],
                'error' => mb_substr($exception->getMessage(), 0, 65535),
            ]);
        }
    }

    if ($once) {
        break;
    }

    sleep(5);
} while (true);
