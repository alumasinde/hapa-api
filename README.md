# Hapa API

Backend API and administration system for Hapa.

## Responsibilities

- PHP API v1
- Authentication and authorization
- User, OTP, PIN, and session management
- Location-based Flash discovery
- Categories, modes, observations, sources, and moderation
- Media metadata for multi-photo Flash feeds
- Administration and audit logging
- MariaDB-compatible spatial queries
- Community Flash reporting and safety controls
- Flash intelligence, lifecycle automation, trust signals, and abuse primitives

## Repository boundary

This repository contains backend code only. The Flutter application lives in `alumasinde/hapa-app`.

## Local server

```powershell
php -S 127.0.0.1:8000 -t public
```

## Phase 6 verification

Run these checks before treating a deployment as verified:

```powershell
composer install
composer stan
php bin/verify-phase6.php
php bin/migrate.php --status
php bin/worker.php --once
```

The verification gate checks required environment values, Phase 6 runtime files, database connectivity, and required Phase 6 tables. PHPStan is configured in `phpstan.neon` and its cache is ignored from version control.

For HTTP regression testing, run the Bruno collection in `tests/bruno` against the local environment after the database verification passes.
