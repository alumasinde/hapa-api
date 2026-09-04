<?php
declare(strict_types=1);
namespace App\Repository;

use App\Database\Connection;

final class FlashIntelligenceRepository
{
    public function upsert(int $flashId, float $confidence, float $trust, float $confirmation, float $penalty, string $state): void
    {
        $sql = 'INSERT INTO flash_intelligence (flash_id, confidence_score, trust_score, confirmation_score, report_penalty, state, last_evaluated_at)
                VALUES (:flash_id, :confidence, :trust, :confirmation, :penalty, :state, UTC_TIMESTAMP())
                ON DUPLICATE KEY UPDATE confidence_score = VALUES(confidence_score), trust_score = VALUES(trust_score), confirmation_score = VALUES(confirmation_score), report_penalty = VALUES(report_penalty), state = VALUES(state), last_evaluated_at = UTC_TIMESTAMP()';
        Connection::get()->prepare($sql)->execute([
            'flash_id' => $flashId, 'confidence' => $confidence, 'trust' => $trust,
            'confirmation' => $confirmation, 'penalty' => $penalty, 'state' => $state,
        ]);
    }

    public function forFlash(int $flashId): array
    {
        $s = Connection::get()->prepare('SELECT confidence_score, trust_score, confirmation_score, report_penalty, state, last_evaluated_at FROM flash_intelligence WHERE flash_id = :id LIMIT 1');
        $s->execute(['id' => $flashId]);
        return $s->fetch() ?: ['confidence_score' => 0, 'trust_score' => 0, 'confirmation_score' => 0, 'report_penalty' => 0, 'state' => 'new', 'last_evaluated_at' => null];
    }
}