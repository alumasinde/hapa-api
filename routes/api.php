<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\CategoryController;
use App\Controllers\FlashController;
use App\Controllers\ModeController;
use App\Controllers\OtpController;
use App\Controllers\ProfileController;
use App\Core\Router;

return static function (Router $router): void {
    $auth = new AuthController();
    $profile = new ProfileController();
    $otp = new OtpController();
    $categories = new CategoryController();
    $modes = new ModeController();
    $flashes = new FlashController();
    $media = new FlashMediaController();
    $reports = new FlashReportController();

    $router->post('/v1/auth/register', [$auth, 'register']);
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
