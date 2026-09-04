<?php
declare(strict_types=1);
namespace App\Repository;
use App\Database\Connection;
use App\Support\Date;
final class RateLimitRepository {
    public function hit(string $scope, string $identifier, int $windowSeconds): array {
        $pdo = Connection::get();
        $now = Date::now(); $nowValue = $now->format('Y-m-d H:i:s');
        $expiresValue = $now->modify(sprintf('+%d seconds', $windowSeconds))->format('Y-m-d H:i:s');
        $pdo->prepare('DELETE FROM rate_limits WHERE expires_at <= :now')->execute(['now' => $nowValue]);
        $statement = $pdo->prepare('INSERT INTO rate_limits (scope_key, identifier, attempts, expires_at, created_at, updated_at) VALUES (:scope, :identifier, 1, :expires_at, :created_at, :updated_at) ON DUPLICATE KEY UPDATE attempts = IF(expires_at <= :now, 1, attempts + 1), expires_at = IF(expires_at <= :now, :expires_at_update, expires_at), updated_at = :updated_at_update');
        $statement->execute(['scope'=>$scope,'identifier'=>$identifier,'expires_at'=>$expiresValue,'created_at'=>$nowValue,'updated_at'=>$nowValue,'now'=>$nowValue,'expires_at_update'=>$expiresValue,'updated_at_update'=>$nowValue]);
        $read=$pdo->prepare('SELECT attempts, expires_at FROM rate_limits WHERE scope_key = :scope AND identifier = :identifier LIMIT 1'); $read->execute(['scope'=>$scope,'identifier'=>$identifier]); $row=$read->fetch();
        return ['attempts'=>(int)$row['attempts'],'expires_at'=>(string)$row['expires_at']];
    }
}