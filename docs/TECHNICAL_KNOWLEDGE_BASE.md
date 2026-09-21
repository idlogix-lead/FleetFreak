# FleetFreak — Technical Knowledge Base

> **Read this first:** FleetFreak is a **multi-tenant fleet / transport-rental management platform** built as a classic **Laravel 10 monolith** (Blade + jQuery admin UI, Sanctum JSON APIs for three mobile apps). It manages vehicles, drivers, bookings ("orders"/rides), fuel, tolls, maintenance, rentals, customers and agents — and has grown a homegrown **double-entry accounting** and **inventory/procurement (ERP-style)** layer. Previously known as *"Zaroon Transport"* and descended from a *"machine management system"* — old naming still lingers in code and DB dumps.

---

## 1. TL;DR (60 seconds)

| | |
|---|---|
| **Backend** | Laravel 10, PHP ≥ 8.1, no service/event layer — fat controllers |
| **Database** | **PostgreSQL** (`fleet_freak`) — *not* MySQL despite the config default; ~83 tables from 163 migrations |
| **Web frontend** | Blade (593 views) + **"Synadmin" Bootstrap 5 admin theme**, jQuery, DataTables, Select2, ApexCharts/Chart.js/Highcharts, **Leaflet** maps |
| **Mobile clients** | 3 apps (Agent, Admin, Driver) hitting `routes/api.php` via **Sanctum** tokens |
| **Auth** | Web: laravel/ui sessions (self-registration disabled → company-onboarding wizard). API: Sanctum + mandatory `firebase_token` (FCM device registration), homegrown OTP password reset |
| **Permissions** | Fully **custom RBAC** (no spatie/gates): role → module → permission-type → method, enforced by `RolePermissions` middleware; sidebar is DB-driven per role |
| **Multi-tenancy** | `Client → Company → User`; nearly every business table carries `company_id`; users switch `active_company_id` |
| **Core flow** | `Order` → `order_lines` (trip legs) → driver dispatch/ride status → payments + **automatic double-entry journal postings** |
| **Notifications** | FCM push (Firebase), self-hosted **WhatsApp HTTP gateway**, Mailtrap email, in-app notification table driven by an `events` audit log |
| **Reporting** | **JasperReports** (PHPJasper, direct DB connection) + **~28 Laravel-Excel export classes** |
| **Queues** | `QUEUE_CONNECTION=sync` — everything runs inline, no workers. Scheduler runs an email command **every second** |
| **Tests** | Stock Laravel scaffolding only — zero domain tests |

---

## 2. High-level architecture

```
┌──────────────┬──────────────┬──────────────┬─────────────────┐
│ Web browser  │ Agent app    │ Admin app    │ Driver app      │
│ Synadmin UI  │ (mobile/SPA) │ (mobile/SPA) │ (mobile/SPA)    │
│ Blade+jQuery │              │              │                 │
└──────┬───────┴──────┬───────┴──────┬───────┴────────┬────────┘
       │ session      │ Sanctum token│ Sanctum token │ Sanctum token
       ▼              ▼              ▼                ▼
  routes/web.php                routes/api.php
  (~50 resources,               (flat /api/* — three parallel
   auth + afterauth mw)          controller families per app)
       │                              │
       ▼                              ▼
  App\Http\Controllers\*       App\Http\Controllers\Api\*
       └───────────────┬──────────────┘
                       ▼
      Eloquent models (~90 — almost all extend BaseModel)
                       │
   ┌──────────┬────────┴────────┬────────────┬─────────────┐
   ▼          ▼                 ▼            ▼             ▼
PostgreSQL  Firebase FCM    WhatsApp      SMTP          Jasper /
fleet_freak (token + topic   gateway       (Mailtrap)    Excel
            push)            (plain HTTP)               exports
```

**Request flow (web):** session auth → `afterauth` middleware (forces one-time password change + company registration) → `RolePermissions` middleware (checks `Controller@method` against the role's permission rows) → fat controller → Blade view rendered server-side; dynamic bits via jQuery AJAX to web routes.

**Request flow (api):** `auth:sanctum` (custom `SanctumMiddleware` only authenticates `expectsJson()` requests) → controller (`Api\` namespace), validation inline via `Validator::make`.

**Base classes (important):**
- `app/Models/BaseModel.php` — all models inherit a `static::saved()` hook that **resets the PostgreSQL sequence** (`setval`) after every save. Needed because seeders/imports insert explicit IDs. If you add a model, extend `BaseModel` (or `AuthenticatableModel` for the User-like model) or pgsql IDs will collide.
- Nearly every model uses `protected $guarded = []` — **mass-assignment is fully open** everywhere.

**Global helpers:** `app/helpers.php` (composer-autoloaded) — currency formatting (`current_currency()`, `apply_currency_symbol/code()`, symbol positioned *after* the amount) and active-nav helpers (`is_active_route`, `active_class`, `show_class`).

---

## 3. Multi-tenancy & data scoping

- `clients` (tenants) → `companies` → `users` (pivot `user_companies`; `users.active_company_id` selects the working company; switch via `CompanyController::change_active_company`).
- Every business table carries `company_id` (+ `created_by`/`updated_by`); newer ERP tables also carry `client_id`.
- Scoping is **manual**, not global-scope based — controllers filter by company themselves.
- `users.is_super_admin` bypasses company requirements; `is_company_admin` marks per-company admins.
- Public signup: `/company_registration` wizard creates Client + Role + User (random password emailed via `RegisterNotification`).

---

## 4. Auth & the custom RBAC

**Web:** laravel/ui sessions; `Auth::routes(['register' => false])`.

**API:** `Api\LoginController::login` requires email/password **plus `firebase_token`** (stored on the user — that's device registration for FCM). Returns a Sanctum personal token; logout deletes all tokens. Password reset is homegrown: 6-digit OTP, 15-min expiry (`users.otp`, `otp_expires_at`), mailed via `SendOtpMail`.

**RBAC chain (all custom, seeded by `RolePermissionSeeder`):**

```
actors (user archetypes) ──< roles (users.role_id)
roles >── role_has_modules ──> role_modules (a module = screen group)
role_modules ──< role_permission_types (action: read/create/global/…)
role_permission_types ──< role_permission_type_functions (method names, view|json)
roles + modules + types ──> role_permissions (the actual grants)
sidebar_groups ──< sidebar_items  → DB-driven menu, filtered by role
```

- Controllers declare `static $role_module_id = N` (+ optional `static $ignores` method whitelist).
- `RolePermissions` middleware resolves `Controller@method` → `User::role_module_permission_via_method()` → allow / redirect to "unauthorized" / 401 JSON. (It `dd()`s on exceptions — debug leftover.)
- Row-level visibility: models expose `scopecheckGlobal($role_module_id)` — unless the role has the "global" permission, queries fall back to `where('created_by', auth()->id())`.

---

## 5. Module map

| Module | Main controllers | Notes |
|---|---|---|
| **Dashboards** | `DashboardController`, `Vehicle/Driver/FinancialDashboardController` | Heavy inline aggregate queries; most Excel exports hang off these |
| **Orders / rides (core)** | web `OrderController`, `AgentOrderController`; api `Api\OrderController` (agent), `Api\AdminOrderController` (45 KB) | Draft → pending → approved/completed/cancelled lifecycle, order numbering, receipts |
| **Approvals** | `PendingOrderController` (web + Api) | Admin approves agent orders; FCM notify on decision |
| **Dispatch / driver ops** | `DriverAssignmentController`, `Api\DriverAssignmentController` (**55 KB — biggest controller**) | Vehicle assignment, ride status updates, walk-in customers, driver time logs. `updateRideStatus` **posts double-entry `AccountTransaction`s and creates `PaymentHeader/Line` on completion** — accounting lives inside the dispatch controller |
| **Rentals** | `RentalVehicleController` (monthly), `DailyRentalController`, `TourServiceController` | Each has an Api twin |
| **Vehicles** | `VehicleController` + class/company/model controllers | Status toggle, vehicle managers (pivot to users) |
| **People (Partners)** | `Driver/Customer/BusinessAgent/Vendor/Employee/UnapprovedAgentController` | **One `Partner` model for everyone**, differentiated by `actor_id` / `partner_type` / `employee_type` |
| **Fuel & toll** | `FuelExpenseController`, `TollTaxController` | Header/line docs; API twins |
| **Maintenance & inspection** | `MaintenanceController`, `MaintenanceApprovalsController`, `InspectionController` | Inspections convert into maintenance invoices |
| **Accounting** | `Account/AccountType/GlJournalController`, `LedgerController` (63 KB) | Chart of accounts with auto-codes, manual journals + posting, agent/driver ledgers |
| **Payments** | `BulkPaymentController`, `AgentPaymentController` | `payment_headers`/`payment_lines` |
| **Inventory / ERP** | `PurchaseOrder/PurchaseInvoice/MaterialInout/InventoryMove/InventoryConsumption/PhysicalInventory/StockStorage/M_MatchPoController` + full `Product*` family, `WareHouse`, `Locator`, `Brand`, `Tax`, `PriceList` | iDempiere/ADempiere-style mini-ERP added ~2025 |
| **Tracking** | `OrderDetailRouteHistoryController::getRouteHistory` | GPS breadcrumbs (lat/lng) plotted on Leaflet; map view polls every 10 s |
| **Notifications** | `FirebaseController`, `Api\NotificationController`, `Api\WhatsAppController` | See §8 |
| **Users/roles/companies** | `User/Role/RoleModule/RolePermission/CompanyController` | See §3–4 |
| **Misc** | `CalendarController` (TUI calendar), `ThemeController` (per-user theme colors), `BlogController` (in-app user guide — content in `blogs` table), `BroadcastMessageController`, `Jasper\*` | |

---

## 6. Database

**Engine:** PostgreSQL (`DB_CONNECTION=pgsql`, db `fleet_freak` on localhost:5432). `config/database.php` defaults to mysql but `.env` overrides — a fresh clone misconfigured as MySQL will silently break (sequence resets, enums).

**Conventions:** snake_case tables; enums/flag columns (no JSON columns anywhere); `company_id` + `created_by`/`updated_by` on business tables; soft-deletes on ~14 models (products, invoices, clients, roles, GL, activities…). `order_details` was **renamed to `order_lines`** (2025-01-22) — code uses `OrderDetail` model over `order_lines` table.

**~83 tables, grouped by domain:**

| Domain | Tables |
|---|---|
| Tenancy & users | `clients`, `companies`, `user_companies`, `users` |
| RBAC & nav | `actors`, `roles`, `role_modules`, `role_permission_types`, `role_permission_type_functions`, `role_permissions`, `role_has_modules`, `role_module_actors`, `sidebar_groups`, `sidebar_items` |
| Partners (people) | `partners` (customer/employee/business/walk-in, self-ref `business_partner_id`), `partner_locations` |
| Vehicles | `vehicles`, `vehicle_companies` (make), `vehicle_classes`, `vehicle_models`, `vehicle_managers` |
| Routes & rates | `locations`, `routes` (from/to loc, distance), `route_rates`, `rate_lists` ("packages"), `load_types` |
| Orders | `orders` (header: status, amounts, direction, trip type), `order_lines` (trip legs: route, driver, vehicle, dates, mileage, qty/pricing), `order_detail_route_histories` (GPS) |
| Documents / expenses | `invoice_document_types` (prefixes `FUEL-`, `MNT-`, `TOLL-`, `ENT-`, `Insp-`, `PO-`, `MI-`, …), `invoices`, `invoice_lines`, `invoice_line_products`, `activities`, `activity_lines` |
| Payments | `payment_headers` (receipt/payment), `payment_lines` |
| Accounting | `account_types` (self-ref tree), `accounts`, `gl_journals`/`gl_journal_lines`, `account_transactions`, `taxes`, `tables` (table-name registry) |
| Inventory/ERP | `products`, `unit_measures`, `product_categories/sub_categories`, `product_groups_1/2/3`, `brands`, `manufacturing_companies`, `product_types`, `price_lists`/`price_list_versions`/`product_prices`, `product_costings`, `ware_houses`, `locators`, `material_inouts`/`material_inout_lines`, `inventory_moves`/`movement_lines`, `inventory_consumptions`/`internal_uselines`, `physical_inventories`/`physical_inventory_lines`, `stock_storages`, `m_match_po` |
| Messaging | `events` (audit log), `notifications`, `broadcast_messages`, `time_logs` |
| Reference | `country_codes`, `cities`, `currencies`, `blogs` |
| Laravel stock | `password_resets`, `failed_jobs`, `personal_access_tokens` |

**Core entity chain:**

```
Client ──< Company ──< User (role_id, active_company_id, firebase_token)
Partner (drivers | customers | agents | employees, via actor_id)
Vehicle (make/company, class, model, driver_id → Partner)

Order (booking header — customer Partner, status, amounts, company)
 └──< order_lines (trip legs — route, driver, vehicle, dates, mileage)
       ├──< order_detail_route_histories   (GPS breadcrumbs)
       └──< payment_lines >── payment_headers (receipt/payment)

Invoice (universal doc — typed by invoice_document_type:
         fuel / maintenance / toll / entertainment / inspection / PO / …)
 └──< invoice_lines (fuel liters, meter reading, toll pictures, …)

GlJournal ──< GlJournalLines ──> Account ──> AccountType (tree)
AccountTransactions  ← auto-posted when a ride completes
MaterialInout ──< lines ──> Product / Locator / StockStorage   (ERP)
```

**Seeders (run order):** `AccountTypeSeeder` (chart-of-accounts tree) → `TableSeeder` (table registry) → `RolePermissionSeeder` (RBAC + sidebar + demo client/company/user) → `CountryCodeSeeder`/`CountryCitySeeder`/`CurrencySeeder` → `InvoiceDocumentTypeSeeder` (12 doc types) → `RandomDataSeeder` (demo fleet, routes, partners, products, users).

---

## 7. Order → ride lifecycle (the heart of the system)

1. Agent (or admin) creates an **Order** (draft) — customer, vehicle class/model, amounts.
2. Order becomes **pending** → admin approves (`PendingOrderController`, web or Api) — FCM push notifies the creating agent.
3. Dispatcher assigns **driver + vehicle** to each `order_line` (`DriverAssignmentController`).
4. Driver app updates ride status (`ride-assign-driver-*`, `ride-status-update-driver`) — milestones: pending → in-progress → complete / unapprove; start/end mileage recorded.
5. While active, breadcrumb lat/lng rows land in `order_detail_route_histories`; the web map view polls `/vehicle/route-history` every 10 s.
6. On completion, `updateRideStatus` **posts double-entry** `AccountTransaction`s (Dr Accounts Receivable / Cr Ride Revenue) and creates `PaymentHeader` + `PaymentLine`.
7. Status changes fire **WhatsApp** messages (order details to customer/driver/agents) and **FCM** pushes; rows in the custom `notifications` table track per-channel delivery (`*_sent_at`).
8. Operational costs (fuel, tolls, maintenance, inspections) are captured as typed **Invoices** against the vehicle.

---

## 8. Notifications & messaging

- **Custom in-app model** (`App\Models\Notification`, not Laravel's): receiver/sender, channel flags (`email`, `sms`, `whatsapp`, `fcm_web_push`, `fcm_mobile_push`, `calendar`) with `*_sent_at` stamps, `sent_type` direct/scheduled. Created via `Event::createEvent(...)` — the `events` table is an audit log whose "source" is resolved through the `tables` registry (hand-rolled polymorphism).
- **FCM (kreait/firebase-php):** used **only for Messaging** — Firebase Auth/Firestore/etc. are configured but vestigial. Direct `CloudMessage` sends in controllers: login stores the device token, order-approval pushes to agents, ride-reminder pushes to drivers, topic push to `admin_notifications`. Web push: inline script in `layouts/app.blade.php` (Firebase project `tms-zaroon`, **hardcoded apiKey/vapidKey**) + `public/firebase-messaging-sw.js`; the browser re-POSTs its token to `/store_fcm` every 5 minutes.
- **WhatsApp:** `Api\WhatsAppController` posts to a self-hosted plain-HTTP gateway (`http://72.255.1.252:9000/api/zaroon`). Live from the order/ride API controllers; a scheduled variant stores rows for a cron-based command.
- **Email:** SMTP → **Mailtrap** sandbox. `RegisterNotification` (emails the new user's plaintext password), `LoginNotification`, `SendOtpMail`.
- **Scheduler** (`app/Console/Kernel.php`): `app:send-email-notifications` runs **everySecond()** scanning `events`/`notifications` for unsent email. Other commands (`notifications:send-scheduled`, `app:generate-maintenance-document` — incomplete, `app:vehicle-route-expired` — crashes under CLI) are manual-only. Queue driver is `sync`; the single job (`SendScheduledNotification`) is dormant.

---

## 9. Reporting

- **JasperReports** (`geekcom/phpjasper`): `.jrxml` sources in `storage/app/report/source/MyReports/src/` (users, user_filter, testingusers, `trial_balance_two_column_FF`), compiled via `php artisan jasper:compile` to `.jasper`, executed by `Jasper\JasperController::report()` using a **direct DB connection built from env credentials** (pgsql→postgres driver map), output per client/user under `storage/app/report/output/`, streamed as pdf/xlsx/csv/html. Report-permission check is commented out.
- **Excel** (`maatwebsite/excel`): ~28 `app/Exports` classes (full-table `FromCollection` dumps) — customers, drivers, vehicles, routes, rate lists, agents, ledgers, and the full order-status matrix (pending/approved/completed/cancelled/unapproved + agent variants), served from `DashboardController` routes. No imports.

---

## 10. Frontend details

- **Theme:** purchased "Synadmin" (codervent) Bootstrap 5.2 template; assets are hand-copied static files under `public/assets/`. Layout `resources/views/layouts/app.blade.php` with three sections: `@yield('style')`, `@yield('wrapper')`, `@yield('script')`. Includes `layouts.nav` (metismenu sidebar, **generated from the user's role permissions**) and an 80 KB `layouts.header` (global search, notifications offcanvas).
- **Per-user theming:** `users.theme` / `header_color` / `sidebar_color` switch CSS (`dark-theme.css`, `semi-dark.css`) on `<html>`; managed by `ThemeController`.
- **JS stack:** jQuery everywhere (~109 AJAX call sites in 54 views, hitting web routes); DataTables (sorting only — search/pagination server-side via `partials/overall_search_query`); Select2; toastr/msgbox; flatpickr; TUI Calendar. Charts: ApexCharts (dashboards), Chart.js, Highcharts, jvectormap. Maps: **Leaflet 1.9.4 + routing machine + Nominatim geocoder** (no Google Maps server-side; a gmaps plugin sits unused).
- **Data flow:** server-rendered Blade `@foreach` tables; chart data as inline `@json()` in per-view `<script>` blocks (order forms are 130–145 KB, mostly inline JS).
- **Auth pages** (login/register/onetimepass/company_details) are standalone full-HTML documents, no shared layout.
- **Build tooling is decorative:** Laravel Mix compiles an Alpine/axios bundle that **no page references**; `vite.config.js` points at packages not installed; Tailwind/Alpine/Breeze components are dead leftovers. Treat all real assets as static files in `public/assets/`.
- **No localization** — hardcoded English; `resources/lang/en` is stock.

---

## 11. Environment & configuration

| Key | Value |
|---|---|
| `DB_CONNECTION` | `pgsql` → `fleet_freak` @ localhost:5432 |
| `MAIL_MAILER` | smtp → Mailtrap sandbox |
| `QUEUE_CONNECTION` / `CACHE` / `SESSION` / `BROADCAST` | sync / file / file / log |
| `FIREBASE_CREDENTIALS` | `firebase_credentials.json` — **missing from repo** (gitignored; a `firebase_credentials.zip` sits at root — unzip it or FCM facade calls fail) |
| `APP_URL` / `FRONTEND_URL` | `http://localhost:8000` / `http://localhost:3000` (the latter is vestigial) |

No payment gateway, no SMS provider, no map/geocoding API keys are configured anywhere — WhatsApp gateway + Firebase are the only real outbound integrations.

---

## 12. Running locally (Laragon)

```bash
composer install
# .env: DB_CONNECTION=pgsql, DB_DATABASE=fleet_freak (create the DB), unzip firebase_credentials.zip
php artisan key:generate
php artisan migrate --seed
php artisan serve            # http://localhost:8000
php artisan schedule:work    # needed for registration emails (runs every second)
```

No `npm` step is required — the Mix bundle is unused (see §10). Seeded demo login comes from `RolePermissionSeeder` / `RandomDataSeeder`.

---

## 13. Code health — gotchas a newcomer must know

- **"copy" file debris everywhere:** `routes/web copy.php`, `DashboardController copy 2.php`, `OrderController copy.php`, `InspectionControllerOriginal.php`, `* copy.php` blades, `app/Models/User copy 2.php`, `app/firebase.js` inside the PHP tree — treat them as dead; never edit a `*copy*` file expecting behavior to change.
- `BulkPayment.php` declares `class DriverAssignment` (duplicate of `DriverAssignment.php`) — same class name in two files.
- `$guarded = []` on nearly all models — be careful with mass-assignment from user input.
- `RolePermissions` middleware calls `dd()` in its catch block — exceptions become debug dumps, not 500s.
- `QUEUE_CONNECTION=sync` + every-second scheduler = notification work (email + WhatsApp HTTP calls) blocks requests inline.
- Legacy models with no migrations: `drivers`, `driver_assignments`, `payments`, `receipts`, `sales`, `service_providers`… — schema exists only in the SQL dumps in `database/*.sql` (which are per-developer snapshots: `machine_management_system_*.sql`).
- `vehicle_route_expired` command calls `auth()->user()` — crashes under CLI; `GenerateMaintenanceDocument` never persists anything.
- Password-change endpoints live on `OrderController` and `RentalVehicleController` (misplaced); duplicate route names exist (`dashboard.orders`, `customers` registered twice).
- The bottom half of `routes/web.php` is ~60 static theme-demo routes (`/charts-apex-chart`, `/component-*`…) — leftovers of the Synadmin sample pages.
- API login's `if ($user->flag)` one-time-password branch is unreachable (after an unconditional `return`).
- `layouts/app.blade.php`: `charset=iso-8859-1` meta, a Firebase config with **hardcoded keys committed in source** (and a second project's keys in comments), bootstrap CSS loaded twice.
- Git history has a single "first commit" — archaeology lives in the `*copy*` files and SQL dumps, not in commits.

---

## 14. Where things live

| I want to… | Look at |
|---|---|
| Understand routing | `routes/web.php` (admin), `routes/api.php` (mobile apps — ignore `web copy.php`) |
| Trace a booking end-to-end | `app/Http/Controllers/OrderController.php` → `Api/AdminOrderController.php` → `Api/DriverAssignmentController.php` |
| Change a page | `resources/views/<module>/` (index/create/edit/form/show pattern) + `layouts/app.blade.php` |
| Understand permissions | `app/Http/Middleware/RolePermissions.php`, `app/Models/User.php::role_module_permission_via_method()`, seeder `RolePermissionSeeder` |
| Add a model | extend `BaseModel` (or `AuthenticatableModel`), mind the pgsql sequence hook |
| Find a scheduled task | `app/Console/Kernel.php` + `app/Console/Commands/` |
| Notifications / push | `Api/FirebaseController.php`, `Api/WhatsAppController.php`, `app/Models/Notification.php`, `Event::createEvent()` |
| Reports | `app/Http/Controllers/Jasper/`, `storage/app/report/`, `app/Exports/` |
| Global helpers | `app/helpers.php` |
| DB truth beyond migrations | `database/*.sql` dumps (per-developer snapshots) |
