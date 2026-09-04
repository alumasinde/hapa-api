# Hapa mobile client integration

The backend contract is frozen for the Hapa MVP under `/v1`.

## Base URL

Development:

```
http://127.0.0.1:8000/v1
```

A physical mobile device cannot reach its own `127.0.0.1` to access a computer-hosted API. Use the development machine's reachable LAN address or a secure tunnel during device testing.

## Authentication

User login returns a top-level access token and refresh token. Protected user endpoints use:

```
Authorization: Bearer <token>
```

Admin authentication is separate and must never be reused as a normal user session.

## Flash workflow

1. Read categories and modes.
2. Authenticate.
3. Create or read flashes.
4. Use nearby feed and flash detail endpoints.
5. Add observations, media and reports according to permissions.
6. Surface server error codes directly to client error handling.

Do not hardcode categories, modes, moderation thresholds or other server-owned configuration in the client.
