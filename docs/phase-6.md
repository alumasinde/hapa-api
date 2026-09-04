# Phase 6: Production Readiness and Hapa Intelligence

## Delivered
- Dynamic Flash confidence scoring using community confirmations, clear observations, reports and reporter trust.
- Automatic lifecycle evaluation and expiry worker.
- User trust profiles and abuse-event recording primitives.
- Production-ready database indexes and cursor-friendly feed ordering.
- Dynamic thresholds through the existing settings table.
- JSON structured application logging with request IDs.
- Health endpoint for deployment checks.
- Environment-aware runtime error handling.
- Database-backed jobs table and worker entry point for future asynchronous work.
- OpenAPI 3 documentation.
- Bruno regression coverage for health and intelligence-visible feed behaviour.

## Migration
```powershell
php bin/migrate.php --status
php bin/migrate.php app
```

## Worker
One production cycle:
```powershell
php bin/worker.php --once
```
Continuous worker:
```powershell
php bin/worker.php
```

## Production checks
Set `APP_ENV=production`, `APP_DEBUG=false`, a strong `JWT_SECRET`, and valid database credentials. The health endpoint is `GET /v1/health`.