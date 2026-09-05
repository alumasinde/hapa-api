# Hapa API

Backend API and administration system for Hapa.

## Status

Phase 7 closes the Hapa API MVP backend:

- Feature complete
- Regression tested with Bruno
- Hardened through Phase 6
- Documented with OpenAPI and deployment guides
- Production verification gates included
- API v1 contract frozen for client integration

The Flutter/mobile application should now be developed against this API contract.

## Local server

```powershell
php -S 0.0.0.0:8000 -t public
```

For a physical phone, use the computer's LAN IPv4 address in the Flutter client, for example `http://192.168.x.x:8000/v1`. The server must bind to `0.0.0.0`, not `127.0.0.1`.

## Verification

Development verification:

```powershell
composer install
composer stan
php bin/verify-phase6.php
php bin/migrate.php --status
php bin/worker.php --once
```

Production verification:

```powershell
composer install --no-dev --optimize-autoloader
php bin/verify-production.php
php bin/migrate.php --status
```

See:

- `docs/phase-7.md`
- `docs/deployment.md`
- `docs/client-integration.md`
- `docs/openapi.yaml`
- `tests/bruno/README.md`

## Repository boundary

This repository contains backend code only. The mobile client belongs in its own repository/project and communicates with the frozen `/v1` API.
