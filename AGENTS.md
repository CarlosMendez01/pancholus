# AGENTS.md

Laravel 13 app (PHP 8.3, Pest 4, Tailwind v4, Vite 8) for a food-truck/PDV management system. UI copy, identifiers, and route slugs are in Spanish.

## Commands

- Setup: `composer setup` (installs deps, copies `.env.example` — note: no `.env.example` is committed, so copy `.env` manually if missing —, migrates, builds assets)
- Dev server (artisan + queue + logs + vite): `composer run dev`
- Tests: `php artisan test --compact` (runs against in-memory SQLite via `phpunit.xml`; no external DB needed)
- Single test: `php artisan test --compact tests/Feature/ExampleTest` or `--filter=testName`; `vendor/bin/pest` works the same
- After editing any PHP file: `vendor/bin/pint --dirty --format agent` (do not use `--test`)
- Frontend changes need `npm run build` (or a running `npm run dev` / `composer run dev`) or Blade will throw a Vite manifest error

## Architecture

- Standard Laravel layout; domain models in `app/Models` (Rol, User, FoodTruck, PDV, PDI, Meal, Ingredient, Recipe, Order, CentralStock, plus assignment/detail pivot models)
- Controllers in `app/Http/Controllers`; only `UserController`, `RolController`, `StatisticsController`, `AuthController` (login/logout) and `OrderController` have routes so far. `OrderController` mixes client flows (`/menu`, cart, `/pedidos`) and the employee area (`/empleados`). Other controllers/models/migrations/seeders exist but are unrouted — wire them up, don't recreate them
- Auth: custom session login (no Breeze/Fortify). Guard `web` uses `User` (extends `Authenticatable`); `bootstrap/app.php` registers a `role` middleware alias (`EnsureUserHasRole`) used on route groups (`role:1` client, `role:3` employee). Roles: 1=cliente, 2=administrador, 3=empleado, 4=encargado_deposito
- All write endpoints use Form Requests (`app/Http/Requests`, paired `Store*Request`/`Update*Request` per model) — follow this pattern for new endpoints
- Policies exist for most models in `app/Policies` but are not yet wired to routes
- Seeders for every domain model in `database/seeders`

## Conventions (verify against siblings before diverging)

- Route style: named routes (`admin.empleados.index`, `admin.roles.*`), snake Spanish slugs, implicit model binding via POST+DELETE pairs rather than PUT/PATCH
- Domain rules encoded in queries, e.g. `UserController` filters `role_id` 1/2 and `status = 'alta'` — preserve these when refactoring
- Passwords are assigned raw in controllers and hashed by the `hashed` cast in `User::casts()` — keep that cast if touching auth flows
- Tests: Pest. `RefreshDatabase` is commented out in `tests/Pest.php` — order/auth tests opt in per-file with `uses(RefreshDatabase::class)` and seed `RolSeeder` (rols FK). Create tests with `php artisan make:test --pest Name` (no suite prefix in the name)

## Agent tooling

- Laravel Boost MCP is configured (`.mcp.json`, `php artisan boost:mcp`) — prefer its tools (`database-query`, `database-schema`, `search-docs`) over raw shell when available
- Full Boost/PHP/Pest/Pint guidelines are in `CLAUDE.md`; follow them. Project rules for `.ai/rules` are referenced there but the directory does not exist yet — skip it
- Do not create documentation files, verification scripts, or new top-level directories without being asked; do not change dependencies without approval
