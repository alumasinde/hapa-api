# Hapa API

Backend API and administration system for Hapa.

## Responsibilities

- PHP API v1
- Authentication and authorization
- User, OTP, PIN, and session management
- Location-based Flash discovery
- Categories, modes, observations, sources, and moderation
- Media metadata for multi-photo Flash feeds
- Administration and audit logging
- MariaDB-compatible spatial queries
- Multi-photo Flash media
- Community Flash reporting and safety controls

## Repository boundary

This repository contains backend code only. The Flutter application lives in `alumasinde/hapa-app`.

php -S 127.0.0.1:8000 -t public