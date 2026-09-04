<?php
declare(strict_types=1);
namespace App\Services;

use App\Database\Connection;
use App\Repository\SettingsRepository;

final class FlashLifecycleService
{
    public function __construct(private readonly SettingsRepository $settings = new SettingsRepository()) {}

    public function evaluate(int $flashId): void
    {
        $s=Connection::get()->prepare('SELECT COALESCE(SUM(ot.observation_key=\'still_happening\'),0) confirms, COALESCE(SUM(ot.observation_key=\'cleared\'),0) clears FROM flash_observations fo JOIN observation_types ot ON ot.id=fo.observation_type_id WHERE fo.flash_id=:id');
        $s->execute(['id'=>$flashId]); $row=$s->fetch() ?: ['confirms'=>0,'clears'=>0];
        $required=(int)$this->settings->get('lifecycle.auto_resolve_cleared_count',3);
        $margin=(int)$this->settings->get('lifecycle.auto_resolve_margin',1);
        if((int)$row['clears'] >= $required && (int)$row['clears'] >= (int)$row['confirms'] + $margin){
            Connection::get()->prepare('UPDATE flashes SET lifecycle_status=\'resolved\', resolved_at=COALESCE(resolved_at,UTC_TIMESTAMP()), updated_at=UTC_TIMESTAMP() WHERE id=:id AND lifecycle_status=\'active\'')->execute(['id'=>$flashId]);
        }
    }

    public function expireDue(int $limit=500): int
    {
        $s=Connection::get()->prepare('UPDATE flashes SET lifecycle_status=\'expired\', updated_at=UTC_TIMESTAMP() WHERE lifecycle_status=\'active\' AND expires_at<=UTC_TIMESTAMP() LIMIT :limit');
        $s->bindValue('limit',$limit,\PDO::PARAM_INT); $s->execute(); return $s->rowCount();
    }
}