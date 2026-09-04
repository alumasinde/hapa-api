<?php
declare(strict_types=1);
namespace App\Services;

use App\Database\Connection;
use App\Repository\FlashIntelligenceRepository;
use App\Repository\SettingsRepository;
use App\Repository\UserTrustRepository;

final class FlashIntelligenceService
{
    public function __construct(
        private readonly FlashIntelligenceRepository $intelligence = new FlashIntelligenceRepository(),
        private readonly UserTrustRepository $trust = new UserTrustRepository(),
        private readonly SettingsRepository $settings = new SettingsRepository(),
    ) {}

    public function evaluate(int $flashId): array
    {
        $s=Connection::get()->prepare('SELECT f.user_id,
            COALESCE(SUM(ot.observation_key = \'still_happening\'),0) confirms,
            COALESCE(SUM(ot.observation_key = \'cleared\'),0) clears,
            (SELECT COUNT(*) FROM flash_reports fr WHERE fr.flash_id=f.id AND fr.status=\'open\') reports
            FROM flashes f
            LEFT JOIN flash_observations fo ON fo.flash_id=f.id
            LEFT JOIN observation_types ot ON ot.id=fo.observation_type_id
            WHERE f.id=:id GROUP BY f.id');
        $s->execute(['id'=>$flashId]); $row=$s->fetch();
        if(!$row){ throw new \RuntimeException('Flash not found'); }

        $base=(float)$this->settings->get('intelligence.base_confidence',35);
        $confirmWeight=(float)$this->settings->get('intelligence.confirm_weight',12);
        $clearWeight=(float)$this->settings->get('intelligence.clear_weight',18);
        $reportWeight=(float)$this->settings->get('intelligence.report_penalty',8);
        $trustScore=$this->trust->score((int)$row['user_id']);
        $trustComponent=($trustScore-50)*0.35;
        $confirmation=((int)$row['confirms']*$confirmWeight)-((int)$row['clears']*$clearWeight);
        $penalty=(int)$row['reports']*$reportWeight;
        $confidence=max(0,min(100,$base+$trustComponent+$confirmation-$penalty));
        $state=$confidence>=75?'high':($confidence>=45?'medium':'low');
        $this->intelligence->upsert($flashId,$confidence,$trustScore,$confirmation,$penalty,$state);
        return ['confidence_score'=>round($confidence,2),'state'=>$state,'confirm_count'=>(int)$row['confirms'],'cleared_count'=>(int)$row['clears'],'report_count'=>(int)$row['reports']];
    }
}