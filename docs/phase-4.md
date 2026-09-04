# Phase 4 — Flash Media and Community Safety

Phase 4 stays entirely inside `hapa-api`.

## Delivered

### Multi-photo Flash media

- Owner-only media management.
- JPEG, PNG, and WebP validation using server-side image inspection.
- Maximum 6 images per Flash.
- Maximum 8 MB per image.
- Randomized storage names.
- Media metadata stored in `flash_media`.
- Active media included in Flash feed and detail payloads.
- Soft-delete in the database and physical file cleanup.
- Upload rate limiting.

### Community safety reporting

- Authenticated users can report another user's Flash.
- Users cannot report their own Flash.
- Controlled report reasons.
- Optional report descriptions.
- One report per user per Flash.
- Database-level duplicate protection.
- Duplicate report response is `409 CONFLICT`.
- Report rate limiting.

## Migration

```powershell
php bin/migrate.php --status
php bin/migrate.php all
```

The migration is safe for existing report data: older duplicate reports are removed before the unique key is added.

## API

- `POST /v1/flashes/{id}/media`
- `PATCH /v1/flashes/{flash}/media/{media}`
- `POST /v1/flashes/{id}/reports`

Media uploads use `multipart/form-data`. Use `media[]` as the file field when uploading multiple images.

## Bruno tests

The Phase 4 folder covers:

1. Second-user setup.
2. Second-user login.
3. Valid report creation.
4. Duplicate report protection.
5. Own-Flash report protection.

Media upload is documented as a manual multipart test because local file paths should not be committed to the shared Bruno collection.

## Verification

```powershell
composer dump-autoload -o
Get-ChildItem -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
php bin/migrate.php --status
```

Then run the Phase 3 setup requests followed by `05-media-and-safety` in Bruno.
