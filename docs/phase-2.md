# Hapa API Phase 2

## Endpoints

- POST /v1/auth/register
- POST /v1/auth/login
- POST /v1/auth/refresh
- POST /v1/auth/logout
- POST /v1/otp/request
- POST /v1/otp/verify
- GET /v1/me
- PATCH /v1/me
- POST /v1/me/pin

## Security rules

- Passwords and PINs use Argon2id hashes.
- JWT access tokens are short lived.
- Refresh tokens are random, hashed in storage, and rotated on refresh.
- Sessions can be revoked through logout.
- OTP values are hashed, expire after ten minutes, and allow five verification attempts.
- Registration, login, and OTP requests are rate limited.
- Public user responses never expose password, PIN, OTP, or refresh token hashes.

## OTP delivery

Phase 2 generates and verifies OTPs but does not expose OTP codes in API responses. SMS and email delivery providers will be connected through a reusable notification layer in a later phase.
