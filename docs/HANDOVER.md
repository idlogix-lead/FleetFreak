# FleetFreak Re-Architecture — Session Handover (2026-09-24, updated 2026-09-29)

> **Start here (2026-09-29):** the Figma UI redesign track is mid-flight. Commit 1
> (`97846e2`) is done, and commit 2 (app shell) is committed as **WIP**.
> **Tomorrow's first task is the UI fix list in §7.5.**

Written so a fresh Claude session (or any developer) can continue with zero
conversational memory. Read top to bottom before touching anything.

---

## 1. Project context

FleetFreak (`d:\laragon\www\FleetFreak`) is a multi-tenant fleet/transport
management monolith — Laravel 10 + PostgreSQL, Blade/jQuery admin UI, Sanctum
APIs for three mobile apps, fully custom RBAC, homegrown double-entry
accounting. It is being re-architected into an **Asset & Vehicle Management
Platform** under an 8-phase program:

| Phase | Scope | Status |
|---|---|---|
| 0 | Baseline + test harness | DONE (commit `721f238`) |
| 1 | Tenant/organization data isolation | DONE (commit `daf4ac3`) + follow-ups and fixes, all committed (see §2/§3) |
| 2 | Accounting hardening (posting rules, transactional safety, reversals) | not started |
| 3 | Asset core (`assets`/`asset_types` wrapper around vehicles) | not started |
| 4 | Maintenance generalization (plans, usage logs, work orders) | not started |
| 5 | Subscriptions (greenfield) | not started |
| 6 | Employee expenses (greenfield) | not started |
| 7 | Dashboards & reports | not started |

Parallel UI track, outside the phase numbering: the **Figma redesign** of the main dashboard and the app-wide
header and sidebar. Presentation only, with no query, model or scoping changes. See §7.

**Three locked client decisions (do not reopen):**

1. **Asset wrapper**: new `assets` + `asset_types` tables; a vehicle-type
   asset holds a 1:1 link (`assets.vehicle_id`) to the existing `vehicles`
   row. `order_lines.vehicle_id` and `invoices.vehicle_id` are NEVER
   re-pointed. New modules (maintenance, expenses, assignments) reference
   `assets`.
2. **Organization naming**: the tenant-hierarchy level the client calls
   "Organization" is the existing `companies` table. No DB renames. Keep
   `company` in code; relabel progressively in the UI. New code uses the
   `OrganizationContext` layer.
3. **Subscriptions**: built in Phase 5, with a "Legacy Unlimited" plan
   auto-assigned to every existing client so launch changes no behavior.
   NOTE: subscriptions and employee expenses do not exist anywhere in the
   code today — both are greenfield builds.

**Standing client rules:**
- ALL work happens on branch `moeen` (the `claudeai` branch was a throwaway test).
- **Commit at the end of every phase.**
- `composer.lock` is tracked in git (do not re-ignore it).
- **Never run `migrate:fresh` (or anything destructive) against the dev DB
  `fleet_freak`** — the test harness uses `fleet_freak_testing` only.

**Reference documents:**
- `docs/TECHNICAL_KNOWLEDGE_BASE.md` — the codebase knowledge base (stack,
  RBAC chain, module map, seeding order, gotchas). Read this first.
- The full architecture/gap analysis and per-phase plans live in the Claude
  plan file `C:\Users\DELL\.claude\plans\claude-development-master-prompt-resilient-spark.md`
  (machine-local to the previous account; if unavailable, this handover plus
  the knowledge base cover the essentials).
- Claude memory files (machine-local, same caveat):
  `C:\Users\DELL\.claude\projects\d--laragon-www-FleetFreak\memory\`.

**Test harness facts:** pgsql test DB `fleet_freak_testing` (creds in
`phpunit.xml`: postgres @ localhost:5432). Run the suite with
`php artisan test` (~15 minutes: most feature tests re-run the full
`DatabaseSeeder`, which includes a 4.2 MB city seeder). Seeded web login: `admin@idl.pk` /
`00000000` (created by `RolePermissionSeeder`, `active_company_id = 1`).
PHPUnit 10.5 (was never installed before this program — `phpunit/phpunit`
is in require-dev).

---

## 2. Current state

_Refreshed 2026-09-24 (end of the Phase 1 follow-up session). Redesign status added 2026-09-29._

- **2026-09-29:**
  - `97846e2` (the new `/dashboard` page) and the WIP commit
    "WIP: app shell redesign — UI fixes pending" sit on top of the history below. Details in §7.
  - The working tree still holds the user's own uncommitted edits, deliberately left out of both commits:
    - editor reformatting of `DashboardController.php` and `routes/web.php`;
    - `public/assets/css/app.css`;
    - `DashboardController copy 2.php`;
    - an untracked `.mcp.json` (local MCP config; never commit it).
- Branch: `moeen`. Commit history before 2026-09-29:
  `ff32402` first commit, `c734f18` bootstrap+docs, `721f238` Phase 0,
  `6e1ab1f` housekeeping, `daf4ac3` Phase 1, `0293bc5` handover,
  `6c43a48` Phase 1 follow-ups (ledger PG fixes, `accounting:audit` checks),
  `861fa5a` knowledge-base refresh, `3f45c0a` agent-dashboard hotfix,
  `e1fbae4` approved orders on `/orders`, `67dbf89` driver-ledger non-admin
  fix, `42096a8` backlog docs, `ba587ca` RBAC web-action registration, then
  the transactions-test fix + this handover refresh.
- **Test suite: fully green** — 23 tests (`OrganizationContextTest` ×9,
  `AgentDashboardTest`, `CompanySwitchTest` ×2, `DriverLedgerAccessTest`,
  `MigrationSanityTest`, `OrderIndexShowsApprovedTest`,
  `OrganizationIsolationMatrixTest`, `OrganizationIsolationTransactionsTest`,
  `RbacWebActionsTest` ×4, `SmokeLoginTest` ×2).
- **Pending on existing databases:** migration
  `2026_09_24_000001_register_rbac_web_actions` (additive, idempotent; inserts
  70 `role_permission_type_functions` rows on the dev DB) must be applied with
  `php artisan migrate` — check the `migrations` table. Until then the Excel
  exports still redirect to `/unauthorized` on that database.
- **NOTHING IS PUSHED.** Origin is
  `https://github.com/idlogix-lead/FleetFreak.git` and denies write access to
  the `moeenidl` account (HTTP 403 on push). The client must grant
  collaborator access or repoint the remote; then run
  `git push -u origin moeen`. Only `master` (at the very first commit)
  exists on origin.
- **Phase 0** (`721f238`): guarded the `migrate:fresh` breaker
  (`invoice_lines.material_inout_line_id` was added by two migrations —
  `2025_01_20_122339` and `2025_02_18_105450`; the latter now has
  `Schema::hasColumn` guards); created `fleet_freak_testing`; phpunit.xml
  points at it; added PHPUnit; replaced stock Laravel test scaffolding with
  app smoke tests; tag `baseline-phase0` on `c734f18`.
- **Phase 1** (`daf4ac3`): organization-level data isolation:
  - `app/Support/OrganizationContext.php` — fail-closed context (throws
    `OrganizationContextMissing` for an authenticated non-super-admin with no
    active company; deliberately neutral for CLI and for `is_super_admin`).
  - `app/Support/OrganizationAccess.php`, `app/Support/OrganizationContextMissing.php`.
  - `app/Models/Concerns/BelongsToOrganization.php` — global scope filtering
    every query by the active company, plus a `creating()` hook that stamps
    `company_id` from the context (context wins over any payload value).
    `app/Models/Concerns/HasClient.php` — stamps `client_id` on create only
    (no query scope: tenant isolation is transitive via company membership).
  - Trait + schema-derived `$fillable` applied to 17 models: Partner,
    Vehicle, Order, OrderDetail, Invoice, PaymentHeader, PaymentLine,
    Account, AccountTransaction, GlJournal, Route, RateList, Location,
    Activity, VehicleModel, VehicleCompany, VehicleClass.
  - Leak fixes: Jasper generic entry gated by an `ALLOWED_REPORTS` constant +
    auth check; `auth:sanctum` added to Api ActorController/BlogController/
    TimeLogController; `RolePermissions` middleware `catch { dd() }` replaced
    with `report()` + 403 JSON / redirect; `CompanyController::change_active_company`
    now rejects invalid membership with 422 (previously nulled
    `active_company_id`); `VehicleDashboardController::topVehicles` no longer
    hard-codes `account_id = 5` (resolves "Ride Revenue" per company);
    `AccountController::index` uses the active company.
  - `app/Console/Commands/AccountingAudit.php` (`accounting:audit`, read-only).
  - `database/migrations/2026_09_22_000001_add_company_id_indexes_for_org_scoping.php`
    (guarded company_id indexes on 10 big tables).
  - Tests: `tests/Unit/OrganizationContextTest.php` (9 cases),
    `tests/Feature/OrganizationIsolationMatrixTest.php` (two-org matrix),
    `tests/Feature/CompanySwitchTest.php` (incl. foreign-company rejection).
- **After Phase 1** (all committed, 2026-09-24):
  - Agent dashboard (`DashboardController::index`, agent branch): varchar
    casts + `DB::query()->fromSub()` — the Phase 1 global scope had made
    `toSql()` + `mergeBindings(getQuery())` drop the first leg's
    `company_id` binding. `AgentDashboardTest`.
  - `/orders` lists approved orders with a view-only link;
    `OrderController::show` renders the order and its lines.
    `OrderIndexShowsApprovedTest`.
  - Web driver ledger/export: no more 500 for non-admins — drivers see only
    their own ledger, other non-admin roles get an empty result.
    `DriverLedgerAccessTest`.
  - RBAC: the 11 module-controller Excel exports plus
    `VehicleController@getVehicleClassDetails` and
    `RoleModuleController@delete_row` registered (seeder: `export` method in
    the default `export` permission type, `driver_export` on Ledgers,
    helpers on Vehicle read / RoleModules delete; migration for existing
    DBs). Exports now return the same rows as their list pages
    (`CustomerExport` limits agents to their own customers; `checkGlobal`
    added to the agent/vehicle/route/rate-list exports) and stay
    organization-scoped via the model global scope. `RbacWebActionsTest`.

---

## 3. Six Phase 1 follow-up items — COMPLETE (committed)

The client asked for a verification report on six items before Phase 2 starts.
Status per item:

1. **Non-HTTP contexts — DONE (no code needed).**
   `php artisan schedule:run` executes the every-second email command
   repeatedly and cleanly (exit 0) — the fail-closed context is neutral
   without an authenticated user. Seeders and artisan commands are proven by
   the suite (full `DatabaseSeeder` runs green in every test run). Mobile
   APIs use the same trait-scoped models with the Sanctum-authenticated user,
   so isolation applies identically.

2. **Leak register — DONE.** Verified status of every original item:
   ledger controllers (fixed, see §4), dashboard queries (closed by the
   trait; `account_id=5` removed), all 27 Excel exports (each is backed by a
   trait-scoped model or an explicit company filter, so all are scoped; the
   11 module-controller exports were additionally unreachable until the RBAC
   registration in §2),
   Jasper gate, 3 unauthenticated API controllers, middleware `dd()`,
   `AccountController::index`, aggregate endpoints `api_today_total_rides` /
   `api_today_active_rides` (they query `OrderDetail`, which is trait-scoped,
   so they are auto-scoped). The `->when($companyId, ...)` fail-open sweep
   was NOT mechanically rewritten — it is redundant-but-safe on trait-scoped
   models (the scope still applies, and a null active company throws). The
   only remaining fail-open surface is the legacy `app/Models/Driver.php`
   (no migration, legacy table) — deferred.

3. **Unscoped tables enumeration — DONE as analysis.** Tables carrying
   `company_id`/`client_id` but NOT trait-scoped, with reasons:
   - Tenancy/RBAC tables (clients, companies, users, roles, role_*,
     sidebar_*, user_companies) — deliberate: they ARE the access model
     (`user_companies` is the membership list).
   - ERP 2025-wave tables (products, product_categories/sub_categories,
     product_groups_1/2/3, product_types, price_lists/_versions/_prices,
     product_costings, unit_measures, manufacturing_companies, brands,
     taxes, ware_houses, locators, material_inouts + lines, inventory_moves
     + movement_lines, inventory_consumptions + internal_uselines,
     physical_inventories + lines, stock_storages, m_match_po,
     partner_locations, activity_lines, events, time_logs,
     broadcast_messages, load_types) — deferred until each module is
     touched; scope them when working on them.
   - **`gl_journal_lines` has NO company_id at all.** Protection is
     transitive: it is a draft-only staging table; the GL of record is
     `account_transactions` (scoped), and the parent `gl_journals` (scoped)
     gates access via the relation. Phase 2 should add `company_id` to
     `gl_journal_lines` and `client_id` to `account_transactions`.

4. **Isolation test extension — DONE.**
   `tests/Feature/OrganizationIsolationTransactionsTest.php`: model-level
   isolation for Order, OrderDetail, Invoice, PaymentHeader and
   AccountTransaction across two organizations, the `/ledgers` page for both
   organizations (each sees its own agent, never the other's), and the
   customer export — all passing. See §4 for why the ledger check first
   failed.

5. **accounting:audit full output — DONE.**
   `app/Console/Commands/AccountingAudit.php` now performs four checks:
   (1) per-company total debit vs credit, (2) per-document balance,
   (3) traceability (NULL record_id and orphaned source rows per
   table_id 15/21/24/51/65), (4) double-posted documents (more than 2 rows
   for one source document; GL journals excluded as multi-line-legit).
   Result against the dev DB: the books are EMPTY (no postings yet) — every
   check reports clean/zero; the command itself is verified working and
   read-only.

6. **Composer.lock — RESOLVED** (tracked since `6e1ab1f`; the earlier
   `.gitignore` excluded it, which is why it never committed).
   **Vendor payment flow — ANSWERED, no code:** there is no vendor payment
   module. Accounts Payable builds up from completed fuel/toll/maintenance/
   purchase-invoice documents (Cr AP) and is reduced ONLY by agent payments
   (Dr AP / Cr Cash via `AgentPaymentController` and
   `Order::store_agent_payment`) — settlement for true vendors does not
   exist; relevant when Phase 3 introduces a vendor partner type.
   **RBAC `$fillable` — OPEN** (see §5).

---

## 4. Resolved investigations (nothing half-finished — do not redo)

**Ledger controllers on PostgreSQL** — three real bugs, fixed in `6c43a48`
in `LedgerController` and `Api\LedgerController`:

1. **VARCHAR money columns**: `order_lines.rate` and
   `payment_lines.total_amount` are varchar. PostgreSQL rejects
   `SUM(varchar)` (MySQL silently cast — the app's original DB). Fixed with
   `NULLIF(col, '')::numeric` casts at every sum/select site.
2. **UNION type mismatches**: the opening legs select `''` text literals
   while the period legs selected date columns — fixed with
   `order_lines.date::text` / `payment_headers.date::text` as `tr_date`.
3. **Global scopes break the ledger UNION pattern**: the controllers build
   UNION legs, then call `toSql()` + `mergeBindings($query->getQuery())`.
   Scope wheres appear in the SQL but their bindings never reach
   `mergeBindings` (they are materialized lazily into a clone), producing
   scrambled bindings (`company_id = '2020-01-01'`). Fixed by calling
   `->withoutGlobalOrganizationalScope()` on every leg and adding an explicit
   `->where('orders.company_id', OrganizationAccess::companyId())` (or
   `payment_headers.company_id`) per leg. Verified: all 9 ledger UNIONs bind
   18/18 with every company slot = the active company (2026-09-24 probe).

**The "missing ledger row" in the transactions test** — the fixture orders
had no customer, and every ledger UNION leg inner-joins
`partners as customers`, so customer-less orders never reach the ledger
(pre-existing behaviour; client question in §5). Fixed in the test data only
(orders now carry customers); the query is unchanged by decision.

**Agent dashboard outage** — `DashboardController::index` (agent branch,
`actor_id = 4`) had the same varchar `SUM` (pre-existing, identical at
`721f238`) plus a Phase 1 regression: `toSql()` applies global scopes but
`getQuery()` does not, so `mergeBindings(getQuery())` dropped the first leg's
`company_id` binding (18 placeholders / 17 bindings). Fixed with casts +
`DB::query()->fromSub()` in `3f45c0a`; verified against a `721f238` worktree.

**"Ledgers are empty" on the dev DB** — NOT a Phase 1 regression: its only
order line is `incomplete` and dated in the future; the ledgers count only
`completed`/`paid` lines in the date range (recorded in the knowledge base
§13).

**RBAC audit** — see §5 (web part fixed; mobile API part open).

---

## 5. Open questions & known defects (not fixed)

Deferred by design:
- **Per-report Jasper RBAC (Phase 7)**: the generic
  `JasperController::report()` is gated only by the `ALLOWED_REPORTS`
  constant + auth check. Static dispatch to `Reports\*ReportController::boot()`
  bypasses constructor middleware — that is WHY the allow-list exists. Wire
  real per-report permissions in Phase 7.
- **`$guarded = []` still open** on RBAC/tenancy models: User, Role, Actor,
  RoleModule, RolePermission, RolePermissionType, RolePermissionTypeFunction,
  SidebarItems, and most non-Phase-1 models. Mass-assignment hardening for
  these is backlog — do it alongside Phase 3's ModuleRegistrar work.
- **Unregistered RBAC actions (audit 2026-09-24). WEB PART FIXED** — the 11
  exports and `getVehicleClassDetails` / `delete_row` are registered (see
  §2). **Still open:** the 13 mobile API actions below (register only after
  the client/app owners confirm which ones the apps call — registering could
  change mobile behaviour), and two dead web routes that were deliberately
  NOT registered: `POST /driver_assignments/completed_rides` →
  `completedRidesUpdate` does not exist; `GET /fetch-timelogs` →
  `VehicleController@get_time_logs` ends in a leftover `dd('$data')` and has
  no caller. Remove both routes (or fix them) during cleanup.
  Original finding: `RolePermissions` looks up
  `role_permission_type_functions` by (controller `$role_module_id`, method);
  a method with no row, and not listed in the controller's `$ignores`, is
  redirected to `/unauthorized` for **everyone, admins included** (confirmed
  with real admin requests: `/customers` 200; `/export_customer`,
  `/export_agent_ledger`, `/export_vehicle` → 302 `/unauthorized`).
  28 routes in 15 controllers:
  - **Every Excel export on a module controller (11 routes — a whole class
    never registered):** `/export_agent` (BusinessAgent), `/export_customer`,
    `/export_driver`, `/export_agent_ledger` + `/export_driver_ledger`
    (Ledger), `/export_location`, `/export_ratelist`, `/export_route`,
    `/export_company` (VehicleCompany), `/export_vehicle`, `/export_models`
    (VehicleModel). The exports served from `DashboardController` (no
    module id) are unaffected.
  - **Web AJAX/helper actions:** `VehicleController@getVehicleClassDetails`
    (`POST /get-vehicle-class-details`), `VehicleController@get_time_logs`
    (`/fetch-timelogs`), `DriverAssignmentController@completedRidesUpdate`
    (`POST /driver_assignments/completed_rides`),
    `RoleModuleController@delete_row` (`DELETE /delete-row/{id}`).
  - **Mobile API actions:** `Api\AdminOrderController` `api_store_draft`,
    `currentorders`; `Api\OrderController` `api_store_draft`,
    `api_login_partner_orders`, `currentorders`,
    `updateOrderStatusnotification`; `Api\DriverAssignmentController`
    `api_ridelist`, `ride_assign_to_driver_completed`,
    `ride_assign_to_driver_unapproved`, `update_ride_status` (its URI contains
    a literal `{$rideId}`), `get_time_logs`, `store_time_logs`,
    `admin_index`. Check with the app owners which of these the mobile apps
    actually call before registering them.
  - Fix = seed the missing function rows (or add keyed `$ignores`) — decide
    per action which permission type (read/export/…) should grant it.
  - Not a gap: 40 routes the **admin** role cannot use are deliberate —
    RoleModules/Actors/AccountTypes/InvoiceDocumentType are Super-Admin-only;
    AgentOrders (web + `Api\OrderController` CRUD) is agent-only.
  - Note: the `App\Models\Ledger` import in `AgentLedgerExport` /
    `DriverLedgerExport` is unused and harmless — earlier "dead export" claim
    was wrong; the RBAC gap above is the real reason exports fail.
- **Backlog — convert the 9 ledger UNION queries to `fromSub()`**
  (`LedgerController` index×2, export×2, driver_ledger, driver_export;
  `Api\LedgerController` api_index×2, driver_ledger). All 9 bind correctly
  today (verified 2026-09-24: 18/18 bindings, every company slot = active
  company, clean PG runs) via the bypass + explicit
  `OrganizationAccess::companyId()` workaround, so this is a consistency
  refactor only — deliberately deferred until after Phase 2. Keep super
  admins pinned to the active company (do not rely on the scope's
  super-admin neutrality when converting).
- **QUESTION FOR THE CLIENT — ledger inner joins hide rows without a
  customer (pre-existing since `ff32402`; query deliberately unchanged).**
  Every ledger UNION leg (web `/ledgers` + export, web/API driver ledgers,
  `Api\LedgerController::api_index`, and the agent dashboard's ledger)
  inner-joins `partners as customers` on `orders.customer_partner_id` /
  `payment_headers.customer_id`. But `orders.customer_partner_id` has been
  nullable since migration `2025_02_03_150517`, every order-creation path
  validates it as `nullable` (only the web order form marks it HTML
  `required`), and ride completion copies a missing customer onto the
  payment header — so completed rides and payments without a customer
  silently vanish from the ledgers. Related: `Order::store_agent_payment`
  (Payments screen) never sets `customer_id` and creates no payment lines, so
  those agent payments never appear in the agent ledger at all. Ask whether
  customer-less rides/payments must show (then switch those joins to LEFT
  JOIN) or whether a customer should become mandatory. Impact SQL for a data
  copy: count orders / payment_headers with no customer per company.
- **QUESTION FOR THE CLIENT — driver ledger only counts rides whose
  business partner is the driver (pre-existing since `ff32402`; do NOT assume
  it is deliberate).** The order legs of both the web and API driver ledgers
  require `orders.business_partner_id = <driver>` in addition to
  `order_lines.driver_id = <driver>`. Agent-booked rides (business partner =
  the agent) are the normal flow, so **driver ledgers are empty in
  practice**; with no driver selected the clause becomes
  `business_partner_id IS NULL`. Ask what a driver ledger should contain
  (all rides the driver drove? only driver-sourced rides?) before changing
  it. Related: the web opening-payments leg filters
  `payment_headers.agent_id` where the API uses `payment_headers.driver_id`.
- **Legacy `app/Models/Driver.php`** (no migration; "machine management"
  era) has a fail-open `when($company)`; the table exists only in old SQL
  dumps. Defer or delete during cleanup.
- **`gl_journal_lines` has no `company_id`** (protected transitively only) —
  add the column + backfill in Phase 2.
- **`account_transactions` has no `client_id`** — additive column + backfill
  in Phase 2.
- **Super admin sees across organizations**: `OrganizationContext::scopeCompanyId()`
  returns null for `is_super_admin` (platform-operator mode). Documented and
  deliberate.
- **VARCHAR money/date columns** in core tables (`order_lines.rate`,
  `payment_lines.total_amount`, `vehicles.milage`, likely others). The pg
  casts unblock queries; the real fix is `ALTER TYPE ... USING` migrations —
  raise with the client in Phase 2.

Pre-existing, unrelated to this program (noticed during verification):
- **Recurring fatals on the user's machine**: `storage/logs/laravel.log`
  shows "Cannot redeclare is_active_route() (app/helpers.php)" roughly every
  minute, and earlier "Cannot declare class App\Models\DriverAssignment"
  (duplicate class name in `BulkPayment.php`, per KB §13). These fire on
  external scheduler ticks. Investigate when convenient.
- **GitHub push blocked**: 403 for `moeenidl` on
  `idlogix-lead/FleetFreak`. Client must fix access; then push `moeen`.
- **"Format on save" produces huge noise diffs (2026-09-30).** `DashboardController.php`, `routes/web.php` and
  `public/assets/css/app.css` showed up as heavily modified (app.css alone: 2671 lines removed, 226 added) with no
  intentional edit behind it. Checked and ruled out as the cause: no `.prettierrc`/`prettier.config.*` anywhere, no
  `prettier` in `package.json` or `node_modules`, and **no `.vscode/settings.json` exists at all**, tracked or not
  (`.vscode/` is gitignored, but the folder isn't even present on disk). So nothing in this repo enables or
  configures it — it's the editor's own **global** "format on save" setting (VS Code's built-in CSS formatter, going
  by the exact signature below), which can fire again for anyone who has that on, on any machine, independent of
  this repo.
  - **Signature of this specific formatter** (useful for spotting it again fast): CSS — single quotes to double,
    `.5px` to `0.5px` (adds a leading zero), `0.70` to `0.7` (trims a trailing zero), `li+li` to `li + li` (spaces
    around `>`/`+`), `#640D5F` to `#640d5f` (hex lowercased), long selectors/values wrapped onto multiple lines,
    blank lines between declarations collapsed. PHP — brace-on-own-line, a space after `if`/`function`/`foreach`,
    spaces around `.`/`,` in expressions and argument lists. No selector, property, value, or logic ever changed.
  - **How to confirm a diff is formatter-only before spending time reading it, instead of eyeballing a huge diff:**
    for PHP, tokenize both versions and drop whitespace/comment tokens —
    `token_get_all($src)`, filter out `T_WHITESPACE`/`T_COMMENT`/`T_DOC_COMMENT`, compare the arrays (expect
    identical, aside from the opening `<?php` tag's line ending — this repo is `autocrlf=true`, so LF in the git
    blob vs. CRLF in the working copy is expected there and nowhere else). For CSS, there's no equivalent built-in
    tokenizer handy, so normalize both versions the same way (lowercase, add leading zeros before a bare `.digit`,
    trim trailing zeros, collapse whitespace, strip blank lines) and diff the result; if anything real changed, it
    survives that normalization.
  - Not fixed, and not this session's call to make: turning "format on save" off for this workspace, or going the
    other way and checking in a `.prettierrc` plus a one-time full-repo reformat, so future diffs stay quiet either
    way. Flag it to whoever owns the editor setup.

Behavior changes shipped in Phase 1 (communicate to the client/users):
- `/ledgers` and the driver-ledger pages now WORK on PostgreSQL (previously
  a 500 for any date-filtered query — the varchar SUM bug).
- A failed company switch returns 422 and leaves the active company
  unchanged (previously returned 200 and nulled it).
- Cross-tenant data is no longer visible anywhere (ledgers, dashboards,
  exports). Users who were accidentally seeing other organizations' data
  will notice.
- `api_today_total_rides` / `api_today_active_rides` are now
  organization-scoped (numbers get smaller but correct) — inform the mobile
  app owners.
- The 11 Excel export buttons work again (previously every user, admins
  included, was redirected to Unauthorized). An agent's customer export now
  contains only that agent's customers — the same rows as their list page.
- The web driver ledger no longer errors for non-admins: drivers see their
  own ledger; other non-admin roles see an empty page.
- The agent dashboard works again (it errored for every agent login).

---

## 6. Next steps for a fresh session

0. **First (2026-09-30): work the UI fix list in §7.5.** Re-verify as described in §7.6, report, and commit
   only when the user says so.
1. Read `docs/TECHNICAL_KNOWLEDGE_BASE.md`, then this document.
2. On any existing database, make sure migration
   `2026_09_24_000001_register_rbac_web_actions` has been applied
   (`php artisan migrate`; additive only — never `migrate:fresh` on the dev DB).
3. Deliver the follow-up report to the client (§3 is the substance; §5 lists
   the open client questions and deferred items).
4. Start **Phase 2 — accounting hardening** (only after the client approves)
   per the plan:
   - `accounts.system_key` column + backfill of the 19 seeded system
     accounts; extend `Account::defaultAccounts()` to write it.
   - `posting_rules` table + platform seed catalog (ride_completion,
     customer_receipt, agent_payment, toll_completed, fuel_completed,
     maintenance_completed, purchase_invoice, material_receipt,
     inventory_consumption, employee_expense_*, gl_journal, ...).
   - `App\Services\Accounting\PostingService` — `post(ruleKey, payload)` and
     `reverse(...)`: wraps everything in `DB::transaction` (currently ZERO
     uses in the whole app), validates Dr = Cr, fails loudly on a missing
     rule/account, stamps `posting_rule_key`, `reversal_of_id`, `client_id`
     (additive columns on `account_transactions`).
   - Strangler-migrate the 19 posting sites in this order:
     `GlJournalController::complete()`, then `Api\DriverAssignmentController`
     (ride completion; also fixes the wrong `payment_headers.type` and the
     hardcoded 'testing description'), then `Order::store_payment` /
     `store_window_payment`, then `AgentPaymentController` (plus
     reverse-on-amendment; fixes wrong `table_id` 21 vs 24 and null
     `record_id`), then `Invoice.php` toll/fuel/maintenance/purchase
     postings, then `MaterialInout.php`, then `PendingRentalInvoices.php`
     and `InventoryConsumption.php` (fixes the undefined-variable null
     posting).
   - Acceptance: `php artisan accounting:audit` stays clean; trial balance
     before/after diff on a staging copy.
5. Keep the cadence: every phase = implement, verify, report, commit on
   `moeen`, stop for client go-ahead.

---

## 7. Figma redesign track: dashboard and app shell (2026-09-29)

**Source.** Figma Make file `XpGQx5r44NYM1vQxnB3mFv` ("FleetFreak Final Design – HomePage"). Its React source
(`src/App.tsx`, the `Sidebar`, `Topbar` and dashboard components) is the source of truth. The Figma MCP runs on a
Starter plan (about 20 calls a month), so work from the extracted values and screenshots rather than new MCP calls.
The tokens, component map and Phase 7 placeholder checklist are in `docs/design-tokens-dashboard.md`.

**Rules the user set for this track:**
- Presentation layer only: no changes to queries, models, controller data or Phase 1 scoping.
- Plain CSS and JS; there is no Vite/Mix build.
- Report before code, and commit only when told to.
- Stage explicit paths, and never the user's unrelated edits. The user's editor reformats files on save. When a
  file mixes the user's edits with yours, build the staged blob from HEAD plus your hunks
  (`git hash-object -w --no-filters` + `git update-index --cacheinfo`). The repo uses `autocrlf=true`, and blobs are LF.

### 7.1 Commit 1: `97846e2`, "Main dashboard: new /dashboard page from the Figma design" (done)

- `/dashboard` runs `DashboardController@indexNew`, which has the same body as `index` and renders
  `home_dashboard/main.blade.php`. `/` (`index`, `dashboard.blade.php`) stays unchanged as the user's fallback.
- The route is inside the `auth` + `afterauth` group and is registered **before** `/`. Both routes are named
  `dashboard`, and the last one registered wins, so `route('dashboard')` still resolves to `/`.
- The CSS and JS live in `public/assets/css/main-dashboard.css` and `public/assets/js/main-dashboard.js`. Everything
  is scoped under `.ffd` and the view has no inline CSS or JS.
- Placeholder values live in a single `$placeholderData` block at the top of the view. They are tagged SAMPLE, and the
  Phase 7 checklist is in the tokens doc.

### 7.2 Commit 2: the app shell (committed as WIP; UI fixes pending)

The Figma sidebar and top bar now apply to every page that extends `layouts.app`. The user accepted that `/` gets
the new shell around its old body. There are 8 files:

| File | What changed |
|---|---|
| `resources/views/layouts/nav.blade.php` | New wrapper: a plum logo band (the white logo; the collapsed rail clips it to its built-in tile), the NAVIGATION label, `#menu` as the scroll area with a thin native scrollbar (simplebar removed), and a pinned **Collapse** button carrying `.toggle-icon`, so Synadmin's `app.js` still drives collapse and hover-expand. The menu pipeline (61 lines: permissions, items, `layouts.partials.menu`) and the position-sort script are **byte-identical to HEAD**. The hard-coded Dashboards group gained `has-arrow`. Removed: the Zaroon logo-swap script, the stray "k", and the broken inline style. |
| `resources/views/layouts/header.blade.php` | Rewritten, with the same data calls and role gates. **Title:** `@section('title')`, then the active breadcrumb (a Create/Edit/Show crumb gets its parent: "Ride · Create"), then the dashboard name by route, then "FleetFreak". **Date:** `now()->format('l, j F Y')`. **Search:** SAMPLE, read-only; Ctrl/⌘K focuses it; hidden below 1200px. **Refresh:** only on the routes `dashboard`, `vehicle_dashboard`, `driver_dashboard` and `financial_dashboard`. **Actor 2:** unapproved-agents tile (`#notificationCount`, hidden at 0) and Vehicle Locations. **Actors 2 and 4:** Create Ride, and a bell showing the real unread count, which replaces the fake 3-second dot. The notifications offcanvas keeps the same ids and queries but now renders only for actors 2 and 4. **User chip:** image or initial; its dropdown has the company switch (`#active_company_dropdown`, URL in `data-change-url`), Dashboard, Profile and Logout (POST with `@csrf`). Removed: about 370 lines of commented demo markup, the orphan "Create order" `<ul>`, the light-theme `$('#1')` script, and the bug where clicking the bell zeroed the agents badge. `header.css` **is still loaded**. |
| `public/assets/css/app-shell.css` | New. `--ffs-*` tokens on `:root`, dark values under `html.dark-theme`, and the sidebar's dark values also under `html.semi-dark`. `ffs-` classes. Menu rules go through `#menu` to outrank `app.css` and the theme files, with scoped `!important` only where those use it: menu white background, hover colour, `.parent-icon`, and the `input::placeholder` rule. The two surface rules marked "D3" retire `headercolorN` / `sidebarcolorN`. Geometry is unchanged: 230px sidebar, 70px rail from 1025px, an off-canvas drawer at 1024px and below, and a 60px top bar (its left edge moves from 200px to 230px). |
| `public/assets/js/app-shell.js` | New, loaded deferred. The company switch keeps the same POST, reload and `msgboxbox` errors, with CSRF read from the meta tag. Also: the Unread/All tabs, Refresh (`location.reload()`), and Ctrl/⌘K (the hint reads "Ctrl K" off a Mac). |
| `resources/views/home_dashboard/main.blade.php` | Removed the in-page title, date and Refresh, which are now in the top bar. The chips and Filter stay. |
| `public/assets/css/main-dashboard.css` | Removed the dead `.ffd-page-title` / `.ffd-page-date` rules; the page head is right-aligned. |
| `public/assets/js/main-dashboard.js` | Removed the dead Refresh handler. |
| `docs/design-tokens-dashboard.md` | New "App shell" section (files, behaviour, tokens, type, breakpoints); Phase 7 item 12 covers the search. |

Decisions the user made for commit 2:
- **D1:** the title fallback chain above.
- **D2:** Refresh on the dashboards only.
- **D3:** the header and sidebar colour options are retired. Their classes, the `/update-header` and `/update-sidebar` endpoints and the user columns are untouched.
- **D4:** fix both header bugs (the fake dot, and the bell zeroing the agents badge).
- **D5:** remove the dead code.
- The "Main Dashboard" menu link keeps pointing at `/` for now.

### 7.3 Verification results (before the UI fixes)

Scratch tooling, not in the repo. It lives in the Claude session scratchpad
`C:\Users\DELL\AppData\Local\Temp\claude\d--laragon-www-FleetFreak\2f34042c-f212-470f-847a-6eaf564921cb\scratchpad\`.
Temp storage can be cleared, so see §7.6 to rebuild it.

- **Crawl.** Every parameter-free authenticated GET route: 176 routes once export, PDF, Jasper and logout routes are
  excluded. Run
  as three users (seeded admin, actor 2; an agent, actor 4; a super admin, actor 1) on `fleet_freak_testing`. It
  records the status, an md5 of the `.page-content` HTML with the CSRF token normalised, and the ordered sidebar
  (label → href) list. The baseline ran twice before any edit.
  - **Status changes: 0** out of 528 checks.
  - **Page content changes:** only `/dashboard` (expected: the title, date and Refresh moved to the header).
  - **Pages with the new shell:** all 385 pages that use the layout (admin 135, agent 129, super 121).
  - **Sidebar:** identical for all 3 users (81 links). It is also identical for 5 permission sets on the admin role
    (81 / 60 / 58 / 12 / 5 links).
  - **Nondeterministic pages:** `/drivers` and `/business_agents` have no `ORDER BY`, so row order changes between
    identical runs. Replaying with the old and new layout files reproduced both hashes, so the shell isn't the cause.
  - **Errors that predate this work:**
    - missing `create()` methods (bulkpayments, driver_assignments, ledgers, maintenance_approvals, pending_orders);
    - a missing `DataTableController`;
    - the missing `service_providers` table;
    - `/profile` needs route `verification.send`;
    - the agent's vehicle, driver and financial dashboards fail with `$agentallcustomer` undefined;
    - super-admin forms fail with `actor_id` on null;
    - `/pending_orders` (super admin) fails with `$export_link` undefined.
  - **Crawler gotcha:** with `RefreshDatabase`, one failing query aborts the Postgres transaction and every later
    request fails with `25P02`. Run each page in a savepoint (`DB::beginTransaction()` / `DB::rollBack($level)`).
- **Tests:**
  - `SmokeLoginTest` and `AgentDashboardTest` pass (3 tests, 14 assertions).
  - The commit-1 render test (`/dashboard` has the same data as `/`, and 11 SAMPLE markers) passes (37 assertions).
  - A scratch role-gate test of the header passes (50 assertions). It covers ids, URLs, CSRF, badges, titles and
    where Refresh appears, for admin, agent and super admin.
  - The full `php artisan test` suite was **not** re-run.
- **Screenshots** (headless Edge, 1440, 1024 and 375px; light, dark and semi-dark; collapsed rail, active item, user
  menu, notifications, drawer, agent view, retired colour options). Private review page:
  https://claude.ai/artifact/XUCt5WrBuPZaJ2Qg1R9nHe
- **Not yet checked by hand in a browser:**
  - the company switch;
  - the bell, the tabs and the badges;
  - collapse, hover-expand and the drawer;
  - menu groups;
  - Ctrl+K;
  - Logout;
  - console errors, including FCM.

### 7.4 Deviations from the approved commit-2 plan

1. **`header.css` still loaded** (the plan said to stop loading it). Pages depend on its global rules:
   `.list-group-item` in 13 views, `.dropdown-item:hover` in 67, and `.navbar-expand-lg`.
2. **Refresh is gated by route name**, not by `@section('header_actions')`. The dashboard views, including the
   frozen `dashboard.blade.php`, can't be edited.
3. **Title refinement:** Create, Edit and Show are the active crumb on 266 pages, so they get their parent in front.
   Dashboards are named by route. `main.blade.php` needed no `@section('title')`.
4. **`only-logo.png` not used**, because it's blue. The white logo already contains a white tile with the plum mark.
5. **SAMPLE tag sits outside the search pill.** Inside, it cut the placeholder short.
6. **Zero badges hidden**, and the offcanvas is rendered only for actors 2 and 4. Only they have a bell. Before, it
   rendered for everyone and ran the agent notification queries.
7. **simplebar removed** from the sidebar, so Collapse can stay pinned outside the scroll area.

### 7.5 Open UI fix list — tomorrow's first task

1. **User-reported UI issues: TO ADD.** The user reported open UI issues at the end of the 2026-09-29 session, but
   they never reached that Claude session: no message, no comments on the review page. Get them from the user first
   and number them here, ahead of the items below.
2. **Two Refresh buttons on `/`.** The frozen old body has its own Refresh next to the header's. Options: hide the
   header Refresh on route path `/` only, or leave it until `/` switches to the new dashboard.
3. **Title truncates on phones.** At 375px an admin on a dashboard has Refresh, four tiles and the avatar, so the
   title shrinks to "Main …". Options: move Vehicle Locations and Create Ride into the user menu below 576px, or
   drop the title below 400px.
4. **Pages without breadcrumbs show "FleetFreak"** as the title (calendar, user profile and others). Options: add a
   route-name → title map to the header, or add `@section('title')` to those views.
5. **Hover state not captured in screenshots.** Check the menu hover pill (`#faf5fa`), the icon-tile hover and the
   user-menu hover in a browser against the Figma hover reference.
6. **Manual browser checks** (see the last item of §7.3) have not been done yet.
7. **Chart y-tick density on `/dashboard`** (from commit 1). Chart.js uses 10k and $1k steps where the design uses
   20k and $1.5k. Set `ticks.stepSize` / `maxTicksLimit` if the user wants an exact match.
8. **Vehicles table clips its Actions column at 1024px.** That's the existing page body, not the shell. Confirm
   whether it's in scope.
9. **Decision still open:** should the menu's "Main Dashboard" link move from `/` to `/dashboard`?

Data issue noticed, not UI and not fixed:
- `Notification::admin_all_notifications()` is `self::get()`, which is unscoped. The admin's "All" tab lists
  notifications from every company, so it's a Phase 1 scoping gap. Scope it by the active company, or by
  `receiver_id` as the agent version does, once the client confirms the intended behaviour.

### 7.6 How to re-verify after the UI fixes

1. **Re-run the crawl** (`CrawlShellTest.php` in the scratchpad) with `CRAWL_OUT` set.
2. **Compare with the baseline** using
   `php crawl-compare.php crawl-baseline-a.json crawl-baseline-b.json <new>.json`.
   - Expected: 0 status changes, only `/dashboard` differs, and every layout page has the new shell.
   - `/drivers` and `/business_agents` may flip.
3. **Re-run the menu-variant capture** (`MenuVariantsTest.php`, `MENU_OUT`) and diff it against `menu-before.json`.
4. **If the scratchpad has been cleared,** rebuild the baseline from `97846e2`: check it out in a worktree, run the
   same crawl there, then crawl the current tree.
5. **Screenshots:**
   - `ShellScreensTest.php` dumps the HTML.
   - Serve it same-origin with `php -S 127.0.0.1:8124 -t public shots-router.php`. Loaded from `file://`, the
     boxicons font fails to load and the icons show as squares.
   - Take the shots with `shoot.ps1` (headless Edge; below 500px wide it uses an iframe wrapper).
6. **Run the tests:** `SmokeLoginTest`, `AgentDashboardTest`, the render test and the role-gate test.
