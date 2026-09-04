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
    $router->get('/v1/me', [$profile, 'me']);
    $router->patch('/v1/me', [$profile, 'update']);
    $router->post('/v1/me/pin', [$profile, 'setPin']);
};
