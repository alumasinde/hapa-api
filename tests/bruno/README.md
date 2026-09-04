# Hapa API Bruno Tests

Start the API:

```powershell
php -S localhost:8000 -t public
```

Apply migrations first:

```powershell
php bin/migrate.php all
```

Open the `tests/bruno` collection in Bruno and select your Local environment.

## Required Local environment variables

```text
baseUrl = http://127.0.0.1:8000/v1
testEmail = hapa.test@example.com
testPhone = +254700000001
testPassword = StrongPassword123!
adminEmail = admin@example.com
adminPassword = StrongPassword123!
```

Do not manually set these runtime variables. The login requests populate them during a collection run:

```text
token
refreshToken
reporterToken
reporterRefreshToken
adminToken
adminSessionId
flashId
```

## Run order

Run the collection from top to bottom:

1. Health
2. Primary user registration and login
3. Profile
4. Hapa engine
5. Second user and media/safety
6. Admin moderation
7. Engagement

Authentication tokens are written to the Bruno environment by the login requests. This prevents later requests from depending on stale request-scoped variables.

The registration requests intentionally accept either `201 Created` on the first run or `422` when the fixed local test account already exists.

The Hapa engine creates a new Flash during each run and stores its ID in `flashId`. The reporter tests then use `reporterToken`, while the own-flash protection test deliberately uses the primary user's `token`.

If a login test reports that a token is undefined while the API returns `200`, inspect the actual response body and ensure the local server has been restarted from the current `main` code. The repository contract is a top-level `token` (and, for user login, top-level `refresh_token`).

Do not use the Local environment credentials in production.
