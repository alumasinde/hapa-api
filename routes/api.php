<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\AdminAuthController;
use App\Controllers\AdminModerationController;
use App\Controllers\AdminRoleController;
use App\Controllers\AdminSettingsController;
use App\Controllers\AdminUserController;
use App\Controllers\CategoryController;
use App\Controllers\FlashController;
use App\Controllers\FlashMediaController;
use App\Controllers\FlashReportController;
use App\Controllers\ModeController;
use App\Controllers\OtpController;
use App\Controllers\ProfileController;
use App\Core\Router;

return static function (Router $router): void {
    $auth = new AuthController();
    $adminAuth = new AdminAuthController();
    $adminModeration = new AdminModerationController();
    $adminUsers = new AdminUserController();
    $adminRoles = new AdminRoleController();
    $adminSettings = new AdminSettingsController();
    $profile = new ProfileController();
    $otp = new OtpController();
    $categories = new CategoryController();
    $modes = new ModeController();
    $flashes = new FlashController();
    $media = new FlashMediaController();
    $reports = new FlashReportController();

    $router->post('/v1/auth/register', [$auth, 'register']);
    $router->post('/v1/admin/auth/login', [$adminAuth, 'login']);
    $router->postAdmin('/v1/admin/auth/logout', [$adminAuth, 'logout'], 'admin.session');

    $router->getAdmin('/v1/admin/flashes/reported', [$adminModeration, 'reported'], 'flashes.moderate');
    $router->getAdmin('/v1/admin/flashes/{id}/reports', [$adminModeration, 'reports'], 'flashes.moderate');
    $router->postAdmin('/v1/admin/flashes/{id}/hide', [$adminModeration, 'hide'], 'flashes.moderate');
    $router->postAdmin('/v1/admin/flashes/{id}/restore', [$adminModeration, 'restore'], 'flashes.moderate');
    $router->getAdmin('/v1/admin/users', [$adminUsers, 'index'], 'users.read');
    $router->getAdmin('/v1/admin/users/{id}', [$adminUsers, 'show'], 'users.read');
    $router->patchAdmin('/v1/admin/users/{id}/status', [$adminUsers, 'status'], 'users.manage');
    $router->postAdmin('/v1/admin/admin-users/{id}/roles', [$adminRoles, 'assign'], 'roles.manage');
    $router->patchAdmin('/v1/admin/roles/{role}/permissions', [$adminRoles, 'permissions'], 'roles.manage');
    $router->getAdmin('/v1/admin/settings', [$adminSettings, 'index'], 'settings.manage');
    $router->patchAdmin('/v1/admin/settings/{key}', [$adminSettings, 'update'], 'settings.manage');
    $router->post('/v1/auth/login', [$auth, 'login']);
    $router->post('/v1/auth/refresh', [$auth, 'refresh']);
    $router->post('/v1/auth/logout', [$auth, 'logout']);
    $router->post('/v1/otp/request', [$otp, 'request']);
    $router->post('/v1/otp/verify', [$otp, 'verify']);

    $router->get('/v1/categories', [$categories, 'index']);
    $router->get('/v1/modes', [$modes, 'index']);
    $router->get('/v1/flashes', [$flashes, 'index']);
    $router->get('/v1/flashes/{id}', [$flashes, 'show']);
    $router->post('/v1/flashes', [$flashes, 'create'], true);
    $router->post('/v1/flashes/{id}/observations', [$flashes, 'observe'], true);
    $router->post('/v1/flashes/{id}/media', [$media, 'upload'], true);
    $router->patch('/v1/flashes/{flash}/media/{media}', [$media, 'remove'], true);
    $router->post('/v1/flashes/{id}/reports', [$reports, 'create'], true);

    $router->get('/v1/me', [$profile, 'me'], true);
    $router->patch('/v1/me', [$profile, 'update'], true);
    $router->post('/v1/me/pin', [$profile, 'setPin'], true);
    $router->post('/v1/me/password', [$profile, 'changePassword'], true);
    $router->post('/v1/me/logout-all', [$profile, 'logoutAll'], true);
};
