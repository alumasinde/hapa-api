# Hapa API Phase 2 Hardening

## Added

- Pre-insert uniqueness checks for phone and email.
- Database duplicate-key exceptions mapped to validation errors.
- Password change with current-password verification.
- Password changes revoke all refresh sessions.
- PIN changes require the current PIN once a PIN exists.
- Logout-all-devices support.
- Reusable authentication middleware.
- Request ID context and X-Request-Id response header.
- Idempotency-key storage foundation for authenticated write operations.

## Idempotency rule

Sensitive write endpoints should require an Idempotency-Key header when duplicate submissions could create duplicate state. The initial middleware rejects replayed keys and stores keys for twenty-four hours. Flash creation will use this foundation in Phase 3.
