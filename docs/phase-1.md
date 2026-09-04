# Hapa API Phase 1

## Scope

- PHP 8.2 foundation
- Composer autoloading
- UTC application dates
- MySQL 8 database connection
- Standard JSON responses
- Users and security tables
- OTP and session foundations
- Dynamic categories and modes
- Election-ready configuration
- Source and observation reference data
- Spatial Flash storage
- Multiple media records per Flash
- Moderation and audit foundations
- Admin users, roles, permissions, and settings

## Repository boundary

This repository contains only PHP backend code, API infrastructure, Admin foundations, and MySQL database assets.

Flutter code belongs only in the separate Hapa frontend repository.

## Database application order

1. Create the MySQL database.
2. Run migration files in numeric order.
3. Run reference seed files in numeric order.

## Date rule

All application timestamps are UTC and API timestamps will be ISO 8601 UTC.

## Flash lifecycle rule

Lifecycle, verification, and moderation remain separate state dimensions.
