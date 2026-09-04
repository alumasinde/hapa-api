<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\RequestContext;
use App\Repository\AdminModerationRepository;
use App\Repository\AuditLogRepository;
use App\Support\Request;
use App\Support\Response;

final class AdminModerationController
{
    public function __construct(private readonly AdminModerationRepository $moderation = new AdminModerationRepository(), private readonly AuditLogRepository $audit = new AuditLogRepository())
    {
    }

    public function reported(): never
    {
        $limit = min(100, max(1, (int) (Request::query('limit') ?? 50)));
        Response::json(['flashes' => $this->moderation->reportedFlashes($limit)]);
    }

    public function reports(string $id): never
    {
        $flashId = (int) $id;
        if (!$this->moderation->flashExists($flashId)) {
            Response::error('NOT_FOUND', 'Flash not found', 404);
        }
        Response::json(['flash_id' => $flashId, 'reports' => $this->moderation->reports($flashId)]);
    }

    public function hide(string $id): never
    {
        $this->moderate((int) $id, 'hidden', 'flash.hidden');
    }

    public function restore(string $id): never
    {
        $this->moderate((int) $id, 'visible', 'flash.restored');
    }

    private function moderate(int $flashId, string $status, string $action): never
    {
        if (!$this->moderation->flashExists($flashId)) {
            Response::error('NOT_FOUND', 'Flash not found', 404);
        }
        $data = Request::json();
        $reason = trim((string) ($data['reason'] ?? ''));
        if ($reason === '') {
            Response::error('VALIDATION_ERROR', 'Moderation reason is required', 422, ['reason' => 'Moderation reason is required']);
        }
        $adminId = RequestContext::adminId();
        $this->moderation->moderate($flashId, $status, $reason, (int) $adminId);
        $this->audit->log('admin', $adminId, $action, 'flash', $flashId, ['reason' => $reason, 'moderation_status' => $status]);
        Response::json(['flash_id' => $flashId, 'moderation_status' => $status, 'reason' => $reason]);
    }
}
