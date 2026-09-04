# Regression testing

Run static analysis:

```powershell
composer stan
```

Run backend verification:

```powershell
php bin/verify-phase6.php
php bin/verify-production.php
```

Run HTTP regression tests with the Bruno collection under `tests/bruno`.

Use a dedicated local/test database where repeatable test data is required. Production credentials must never be used by Bruno environments.
