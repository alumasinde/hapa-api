<?php
declare(strict_types=1);
namespace App\Repository;
use App\Database\Connection;
use App\Support\Date;
use PDOException;
final class FlashReportRepository {
    public function exists(int $flashId,int $userId): bool {
        $s=Connection::get()->prepare('SELECT 1 FROM flash_reports WHERE flash_id = :flash_id AND reporter_user_id = :user_id LIMIT 1');
        $s->execute(['flash_id'=>$flashId,'user_id'=>$userId]); return (bool)$s->fetchColumn();
    }
    public function openCount(int $flashId): int {
        $s=Connection::get()->prepare('SELECT COUNT(*) FROM flash_reports WHERE flash_id = :flash_id AND status = \'open\''); $s->execute(['flash_id'=>$flashId]); return (int)$s->fetchColumn();
    }
    public function create(int $flashId,int $userId,string $reason,?string $description): array {
        $pdo=Connection::get(); $now=Date::now()->format('Y-m-d H:i:s');
        try {$s=$pdo->prepare('INSERT INTO flash_reports (flash_id, reporter_user_id, reason, description, status, created_at, updated_at) VALUES (:flash_id, :user_id, :reason, :description, \'open\', :created_at, :updated_at)'); $s->execute(['flash_id'=>$flashId,'user_id'=>$userId,'reason'=>$reason,'description'=>$description,'created_at'=>$now,'updated_at'=>$now]);}
        catch(PDOException $e){if((string)$e->getCode()==='23000') throw new \DomainException('Already reported',0,$e); throw $e;}
        return ['id'=>(int)$pdo->lastInsertId(),'flash_id'=>$flashId,'reason'=>$reason,'status'=>'open','created_at'=>$now];
    }
}