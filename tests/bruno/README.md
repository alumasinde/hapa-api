# Hapa API Bruno Tests

Start the API:

```powershell
php -S localhost:8000 -t public
```

Apply migrations first:

```powershell
php bin/migrate.php all
```

Open the repository root in Bruno, select the Local environment, then run the collection.

The collection reuses one fixed local test user. Registration accepts 201 on the first run or 422 when that user already exists. Login captures the access token for later requests. The Hapa engine creates a new Flash on every run and passes its ID through collection variables.

Do not use the Local environment credentials in production.
