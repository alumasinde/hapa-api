<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\OtpController;
use App\Controllers\ProfileController;
use App\Core\Router;

return static function (Router $router): void {
    $auth = new AuthController();
    $profile = new ProfileController();
    $otp = new OtpController();

    $router->post('/v1/auth/register', [$auth, 'register']);
    $router->post('/v1/auth/login', [$auth, 'login']);
    $router->post('/v1/auth/refresh', [$auth, 'refresh']);
    $router->post('/v1/auth/logout', [$auth, 'logout']);
    $router->post('/v1/otp/request', [$otp, 'request']);
    $router->post('/v1/otp/verify', [$otp, 'verify']);
    $router->get('/v1/me', [$profile, 'me'], true);
    $router->patch('/v1/me', [$profile, 'update'], true);
    $router->post('/v1/me/pin', [$profile, 'setPin'], true);
    $router->post('/v1/me/password', [$profile, 'changePassword'], true);
    $router->post('/v1/me/logout-all', [$profile, 'logoutAll'], true);
};
