# Phase 3 — Core Hapa Engine

Phase 3 is complete.

## Delivered

- Enabled category and mode discovery.
- Category-driven flash expiry.
- MariaDB-compatible spatial flash storage and nearby feed queries.
- Authenticated flash reporting.
- Category-specific observations.
- Duplicate observation protection at database level.
- Observation switching by user and flash.
- Validation and authorization hardening.
- Flash create and observation rate limits.
- Lifecycle-aware feed filtering.
- Expired flashes reported as expired when read.
- Bruno coverage for categories, modes, flash creation, nearby feed, retrieval, and observations.

## Hardening migration

Run:

```powershell
php bin/migrate.php --status
php bin/migrate.php all
```

The Phase 3 hardening migration changes observation integrity from one row per observation type to one current observation per user per flash.

## Verification

```powershell
composer dump-autoload -o
Get-ChildItem -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
php bin/migrate.php --status
```

Then run the Bruno collection against the Local environment.

## Notes

The idempotency middleware remains available as reusable infrastructure. Flash endpoints preserve their existing request contract and do not require an Idempotency-Key in this completed Phase 3 milestone.
