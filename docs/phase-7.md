# Phase 7 — Deployment, API Contract Freeze & Client Integration Readiness

Phase 7 closes the Hapa API MVP. New client features should now be implemented without casually changing existing v1 response contracts.

## API contract

The public API namespace is frozen under `/v1`.

- Public/system: `/v1/health`, categories, modes and flash reads
- User authentication: `/v1/auth/*`
- OTP: `/v1/otp/*`
- User profile: `/v1/me*`
- Core flashes: `/v1/flashes*`
- Administration: `/v1/admin/*`

Breaking changes require a new API version. Do not repurpose existing fields or silently change response shapes used by the mobile client.

## Standard responses

Success responses return JSON with endpoint-specific payloads.

Errors use:

```json
{
  "error": {
    "code": "MACHINE_READABLE_CODE",
    "message": "Human readable message"
  }
}
```

Validation errors may additionally contain `error.fields`.

## Production gate

Run before deployment:

```powershell
composer install --no-dev --optimize-autoloader
php bin/migrate.php --status
composer stan
php bin/verify-phase6.php
php bin/verify-production.php
```

Apply migrations only after reviewing the status:

```powershell
php bin/migrate.php all
```

## HTTP deployment requirements

- Serve only `public/` as the web root.
- Terminate TLS before exposing the API publicly.
- Set `APP_ENV=production` and `APP_DEBUG=false`.
- Use a strong unique JWT secret.
- Configure exact allowed client origins with `CORS_ALLOWED_ORIGINS`.
- Never expose `.env`, `database/`, `docs/`, `tests/` or `bin/` through the web server.

## Client readiness

The mobile client should:

1. Store the access and refresh tokens securely.
2. Refresh authentication instead of repeatedly prompting for login.
3. Treat API error `code` as stable programmatic input.
4. Send `Idempotency-Key` for operations where retrying could duplicate a mutation.
5. Avoid embedding API secrets in the mobile application.

Phase 7 marks the Hapa API MVP as feature complete. Future backend work should be bug fixes, security patches, operational improvements, or versioned product changes.
