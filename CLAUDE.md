# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

"Admin Dikitivi" — Laravel 10 (PHP 8.1) back-office for a media/video platform ("Dikitivi"): book/media catalog organized by categories and pricing tiers, chunked video uploads, a legal-info CMS (subjects/titles/contents), donations, carts/orders/payments, user management with roles/groups/countries, and S3 (AWS) storage. Admin UI is a Filament 3 panel; there's also a thin set of authenticated web routes/controllers outside Filament (legacy or API-adjacent).

See `ACTIONS_A_FAIRE.md` for the (French) migration/seeding history and manual setup steps still needed after a fresh clone — it documents real quirks in this codebase (see below) rather than being generic boilerplate.

## Commands

- Install: `composer install && npm install`
- Dev asset watch: `npm run dev` (vite)
- Build assets: `npm run build`
- No composer `dev`/`test` scripts are defined — run Laravel commands directly: `php artisan serve`, `php artisan test`, `php artisan test --filter=TestName`
- Migrate a fresh DB: `php artisan migrate` (or `php artisan migrate:fresh` to reset — destructive)
- Seed super admin: `php artisan db:seed --class=SuperAdminSeeder` (default credentials `contact@silasmas.com` / `silasmas` — change in production)
- Post-deploy cache clear: `php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear`

## Architecture

- **Admin panel**: Filament 3 resources in `app/Filament/Resources/<Model>Resource(.php)` — Aws, Category, Client, Country, Group, LegalInfoContent/Subject/Title, Media, Role, Status, Type, User — plus a `MediaByCategoryChart` widget. Panel access is restricted to users with at least one role other than "Membre" (see `User::canAccessPanel()` implementing `FilamentUser`). Uses `filament-shield` for permissions and `filament/spatie-laravel-translatable-plugin` (models like `Status` use `Spatie\Translatable\HasTranslations` for multi-language fields).
- **Models** (`app/Models`): core content is Book/Media/Category/CategoryMedia/Pricing; commerce is Cart/Order/Payment/Donation; taxonomy/reference data is Group/Status/Type/Country; `Aws` model (PSR-4 named, not lowercase `aws`) backs the S3/AwsResource config; `LegalInfoSubject`/`Title`/`Content` form a 3-level legal-info CMS tree.
- **Video upload flow**: chunked upload via `Test` controller (`app/Http/Controllers/Test.php` — note the non-descriptive name) exposing `/upload-video-chunk`, `/finalize-video-upload`, `/upload/progress`; there's also a simpler single-shot `/upload-video` route. Uploaded files are stored via Laravel filesystem (`public` disk or S3, per `FILESYSTEM_DISK`/`AWS_*`/`FILAMENT_FILESYSTEM_DISK` env vars).
- **Routing**: `routes/web.php` has a mostly-empty `routes/api.php` (only `auth:sanctum` `/user`) — real functionality is server-rendered web routes plus the Filament panel. Dashboard/base view lives at `BaseController::dashbord` (note: intentionally misspelled method name, matches existing code — don't silently "fix" without checking callers).
- **Auth**: standard Laravel Breeze-based auth controllers (`app/Http/Controllers/Auth/*`) plus Sanctum for API tokens.
- **Known rough edges to be aware of** (from `ACTIONS_A_FAIRE.md`): migrations were originally miscreated under `database/factories/` and moved to `database/migrations/`; `AdminPanelProvider` duplicate registration was removed from `config/app.php`; the `users` migration was enriched with `firstname`/`lastname`/`country_id`/`status_id`; a `role_user` pivot table was added. When adding new migrations, check `ACTIONS_A_FAIRE.md`'s numbered migration order (groups/countries → statuses/types → users/roles → categories/pricing/books/media → legal info/carts/donations/orders/payments → notifications/media pivots) since dependencies matter.
