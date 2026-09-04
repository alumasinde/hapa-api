# Hapa API Architecture

## Domain model

Hapa is organized around location-based Flashes and reusable configuration.

```text
User -> Flash -> Category
           |
           +-> Media
           +-> Observations
           +-> Source
           +-> Moderation

Mode -> Categories -> Observation Types
```

## Lifecycle

Flash lifecycle is separate from verification and moderation.

- Lifecycle: `active`, `resolved`, `expired`, `removed`
- Verification: `unverified`, `community_confirmed`, `questioned`, `verified`, `official`
- Moderation: `visible`, `hidden`, `removed`

## Repository boundary

The API repository owns backend business rules, persistence, API responses, and admin services. Flutter UI and device code belong only in `hapa-app`.

## Reusable backend components

Shared services should be centralized and reused across modules for dates, validation, authentication, rate limiting, identifiers, pagination, media handling, and JSON responses.

## Dynamic configuration

Categories, modes, observation types, source types, expiry rules, and enabled states are backend-managed so the mobile application can adapt without a client release.
