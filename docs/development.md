# Hapa API Development Rules

## Shared components

Prefer reusable services and helpers over repeated module-specific implementations.

Common components include:

- Date and time handling in UTC
- JSON response formatting
- Validation
- Pagination and cursors
- Authentication and authorization
- Password and PIN hashing
- OTP lifecycle
- Rate limiting
- Idempotency
- Spatial distance queries
- Media validation and storage metadata
- Audit logging

## Dates

Persist timestamps in UTC and return ISO 8601 UTC timestamps from the API.

## Comments

Code comments must be one line and explain intent only when the code is not already self-explanatory.

## API boundary

API contracts belong to the backend. The Flutter app consumes versioned endpoints and must not duplicate backend business rules that can be configured or enforced server-side.
