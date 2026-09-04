<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Security\OtpService;
use App\Security\RateLimiter;
use App\Support\Env;
use App\Support\Request;
use App\Support\Response;

final class OtpController
{
    public function __construct(
        private readonly OtpService $otps = new OtpService(),
        private readonly RateLimiter $limits = new RateLimiter(),
    ) {
    }

    public function request(): never
    {
        $data = Request::json();
        $destination = trim((string) ($data['destination'] ?? ''));
        $purpose = trim((string) ($data['purpose'] ?? ''));

        if ($destination === '' || $purpose === '') {
            Response::error('VALIDATION_ERROR', 'Destination and purpose are required', 422);
        }

        if (!$this->limits->allow('otp:' . $purpose, $destination, 5, 900)) {
            Response::error('RATE_LIMITED', 'Too many OTP requests', 429);
        }

        $code = $this->otps->generate(null, $destination, $purpose);
        $response = ['message' => 'OTP issued'];

        if (Env::get('APP_ENV', 'production') === 'local') {
            $response['code'] = $code;
        }

        Response::json($response);
    }

    public function verify(): never
    {
        $data = Request::json();
        $destination = trim((string) ($data['destination'] ?? ''));
        $purpose = trim((string) ($data['purpose'] ?? ''));
        $code = trim((string) ($data['code'] ?? ''));

        if ($destination === '' || $purpose === '' || !preg_match('/^[0-9]{6}$/', $code)) {
            Response::error('VALIDATION_ERROR', 'OTP details are invalid', 422);
        }

        if (!$this->otps->verify($destination, $purpose, $code)) {
            Response::error('UNAUTHORIZED', 'OTP is invalid or expired', 401);
        }

        Response::json(['verified' => true]);
    }
}
