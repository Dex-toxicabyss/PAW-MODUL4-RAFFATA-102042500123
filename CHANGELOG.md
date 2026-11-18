# Changelog

All notable changes to this project are documented in this file following [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2026-11-18

### Added
- Multi-role database schema migration (`admin` and `mahasiswa` roles on `users` table) and `cover` column migration on `buku` table.
- Role-based access control middleware (`RoleMiddleware`) registered under the `role` alias in `bootstrap/app.php` protecting admin routes in `routes/web.php`.
- Book cover image upload, live preview, automatic old file replacement, and storage deletion in `BukuController` and Blade views (`buku/create`, `buku/edit`, `buku/index`).
- Public library catalog search, publisher/stock availability filtering, dynamic sorting, and query-string-preserving pagination in `HomeController` and `home.blade.php`.
- Automated feature tests for `RoleMiddleware` authorization and public catalog search/detail endpoints.
