# Property Management System — PHP (mysqli) — Main Parts

This repository contains the **main parts only** for a simple PHP OOP property management API using **mysqli**.

## Main components
- `public/api.php` — single file entrypoint for API routes (demo router)
- `src/Config.php` — configuration constants
- `src/Database.php` — mysqli connection wrapper
- `src/Models/Property.php` — simple DTO model
- `src/Repositories/PropertyRepository.php` — DB CRUD using prepared statements
- `src/Controllers/PropertyController.php` — request handling, validation, responses
- `src/Middleware/AuthMiddleware.php` — minimal token auth example
- `tests/simple_test.php` — quick smoke tests

## Quick start
1. Create DB and table (see SQL in original README).
2. Configure `src/Config.php`.
3. Serve `public/` as document root: `php -S localhost:8000 -t public`.
4. Use `Authorization: Bearer <token>` for protected endpoints.

This snapshot is intentionally concise for GitHub showcase.
