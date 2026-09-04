# Hapa API deployment

## Recommended release sequence

1. Back up MariaDB.
2. Deploy application files.
3. Run `composer install --no-dev --optimize-autoloader`.
4. Configure production `.env`.
5. Run `php bin/verify-production.php`.
6. Run `php bin/migrate.php --status`.
7. Apply approved migrations with `php bin/migrate.php all`.
8. Run the HTTP regression suite against the deployment environment.
9. Restart PHP/web-server workers.

## Web root

Point Apache or Nginx only at the repository's `public/` directory.

## CORS

Set exact origins, for example:

```
CORS_ALLOWED_ORIGINS=https://app.example.com,https://admin.example.com
```

Do not use wildcard origins in production when authenticated browser clients are involved.

## Roll-forward policy

Migrations are additive and tracked in `schema_migrations`. Do not edit an applied migration. Fix a production issue with a new migration.

Take a database backup before schema changes. Prefer roll-forward fixes over destructive rollback.
