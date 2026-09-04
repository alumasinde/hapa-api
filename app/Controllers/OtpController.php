<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Security\OtpService;
use App\Security\RateLimiter;
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
        $destination = (string) ($data['destination'] ?? '');
        $purpose = (string) ($data['purpose'] ?? '');

        if ($destination === '' || $purpose === '') {
            Response::error('VALIDATION_ERROR', 'Destination and purpose are required', 422);
        }

        if (!$this->limits->allow('otp:' . $purpose, $destination, 5, 900)) {
            Response::error('RATE_LIMITED', 'Too many OTP requests', 429);
        }

        $this->otps->generate(null, $destination, $purpose);

        Response::json(['message' => 'OTP issued']);
    }

    public function verify(): never
    {
        $data = Request::json();
        $destination = (string) ($data['destination'] ?? '');
        $purpose = (string) ($data['purpose'] ?? '');
        $code = (string) ($data['code'] ?? '');

        if ($destination === '' || $purpose === '' || !preg_match('/^[0-9]{6}$/', $code)) {
            Response::error('VALIDATION_ERROR', 'OTP details are invalid', 422);
        }

        if (!$this->otps->verify($destination, $purpose, $code)) {
            Response::error('UNAUTHORIZED', 'OTP is invalid or expired', 401);
        }

        Response::json(['verified' => true]);
    }
}
