<?php
declare(strict_types=1);
namespace App\Repository;

use App\Database\Connection;

final class UserTrustRepository
{
    public function score(int $userId): float
    {
        $s = Connection::get()->prepare('SELECT trust_score FROM user_trust_profiles WHERE user_id = :id LIMIT 1');
        $s->execute(['id' => $userId]);
        $value = $s->fetchColumn();
        return $value === false ? 50.0 : (float) $value;
    }

    public function adjust(int $userId, int $successful = 0, int $harmful = 0, int $abuse = 0): void
    {
        $sql = 'INSERT INTO user_trust_profiles (user_id, trust_score, successful_reports, harmful_reports, abuse_events, last_evaluated_at, created_at, updated_at)
                VALUES (:id, GREATEST(0, LEAST(100, 50 + (:successful * 2) - (:harmful * 4) - (:abuse * 6))), :successful, :harmful, :abuse, UTC_TIMESTAMP(), UTC_TIMESTAMP(), UTC_TIMESTAMP())
                ON DUPLICATE KEY UPDATE successful_reports = successful_reports + VALUES(successful_reports), harmful_reports = harmful_reports + VALUES(harmful_reports), abuse_events = abuse_events + VALUES(abuse_events), trust_score = GREATEST(0, LEAST(100, trust_score + (:successful2 * 2) - (:harmful2 * 4) - (:abuse2 * 6))), last_evaluated_at = UTC_TIMESTAMP(), updated_at = UTC_TIMESTAMP()';
        Connection::get()->prepare($sql)->execute([
            'id'=>$userId,'successful'=>$successful,'harmful'=>$harmful,'abuse'=>$abuse,
            'successful2'=>$successful,'harmful2'=>$harmful,'abuse2'=>$abuse,
        ]);
    }
}