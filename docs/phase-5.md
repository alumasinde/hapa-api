# Phase 5: Moderation and Administration

Phase 5 activates the existing administration schema without creating a parallel admin model.

## Features

- Admin login with admin-only JWT claims.
- Admin authentication middleware.
- Permission middleware backed by roles and permissions.
- Reported Flash queue.
- Flash report review.
- Hide and restore moderation actions.
- User listing and detail review.
- User active, suspended and disabled states.
- Admin role assignment.
- Role permission updates.
- Dynamic settings backed by the settings table.
- Audit logging for sensitive admin actions.
- Bruno coverage for the primary admin flow.

## Migration

Run:

```powershell
php bin/migrate.php --status
php bin/migrate.php admin
```

Phase 5 seeds the permissions, roles and moderation threshold setting.

## Create a local admin

Run after the admin migrations:

```powershell
php bin/create-admin.php admin@example.com StrongPassword123! Albert Admin
```

The command creates or updates the admin and assigns the `super_admin` role.

## Admin endpoints

| Method | Endpoint | Permission |
|---|---|---|
| POST | /v1/admin/auth/login | Public |
| GET | /v1/admin/flashes/reported | flashes.moderate |
| GET | /v1/admin/flashes/{id}/reports | flashes.moderate |
| POST | /v1/admin/flashes/{id}/hide | flashes.moderate |
| POST | /v1/admin/flashes/{id}/restore | flashes.moderate |
| GET | /v1/admin/users | users.read |
| GET | /v1/admin/users/{id} | users.read |
| PATCH | /v1/admin/users/{id}/status | users.manage |
| POST | /v1/admin/admin-users/{id}/roles | roles.manage |
| PATCH | /v1/admin/roles/{role}/permissions | roles.manage |
| GET | /v1/admin/settings | settings.manage |
| PATCH | /v1/admin/settings/{key} | settings.manage |

## Audit actions

Sensitive actions write to `audit_logs`, including Flash moderation, user status changes, role changes and settings updates.
