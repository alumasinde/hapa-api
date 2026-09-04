<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\RequestContext;
use App\Repository\AuditLogRepository;
use App\Repository\SettingsRepository;
use App\Support\Request;
use App\Support\Response;

final class AdminSettingsController
{
    public function __construct(private readonly SettingsRepository $settings = new SettingsRepository(), private readonly AuditLogRepository $audit = new AuditLogRepository())
    {
    }

    public function index(): never
    {
        Response::json(['settings' => $this->settings->all()]);
    }

    public function update(string $key): never
    {
        if (!preg_match('/^[a-z0-9_.-]{2,120}$/', $key)) Response::error('VALIDATION_ERROR', 'Setting key is invalid', 422);
        $data = Request::json();
        if (!array_key_exists('value', $data)) Response::error('VALIDATION_ERROR', 'Setting value is required', 422, ['value' => 'Setting value is required']);
        $this->settings->set($key, $data['value']);
        $this->audit->log('admin', RequestContext::adminId(), 'setting.updated', 'setting', null, ['setting_key' => $key]);
        Response::json(['key' => $key, 'value' => $data['value']]);
    }
}
