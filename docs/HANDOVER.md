# FleetFreak Re-Architecture — Session Handover (2026-09-24, updated 2026-10-01)

> **Start here (2026-10-01):** the Figma UI redesign track is mid-flight.
> - `97846e2` added the new `/dashboard` page.
> - The app shell is committed as WIP.
> - On 2026-10-01, `/dashboard` was cut down to exactly the design, and the header was simplified (§7.7).
>
> **Next task: the remaining UI fix list in §7.5.**

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

_Refreshed 2026-09-24 (end of the Phase 1 follow-up session). Redesign status added 2026-09-29, updated 2026-10-01._

- **2026-09-29:** `97846e2` (the new `/dashboard` page) and `1fbf3f4` ("WIP: app shell redesign — UI fixes
  pending"). Details in §7.
- **2026-09-30:** the working tree was cleaned up.
  - `6fd9e46` is the editor's formatter output for `DashboardController.php`, `routes/web.php` and `app.css`.
    Token-verified: no logic changes.
  - `5688324`: `.mcp.json` is now gitignored (local MCP config; never commit it); the stale
    `DashboardController copy 2.php` is deleted; and the format-on-save note is in §5.
- **2026-10-01:** "Dashboard and app shell: Figma design-only content, header simplified" (§7.7). The working tree is
  clean after it.
- **2026-10-02:** `2fe2a9a` (employee create/edit fix) and `37b151b` (production error pages), both pushed to
  `origin/moeen`. See §8.
- **2026-10-05:** three commits, pushed: "docs: permission and ownership audit" (§9), "Security: delete endpoints
  removed every row in the company, not one" (§8.3), and "Security: agents could read and modify other agents'
  orders" (§9.11, which also fixes the `/agentorders` crash). The working tree is clean after them.
- **2026-10-05, also pushed:** "docs: user management and password audit" (§9.12).
- **2026-10-05, also pushed:** "Fix: remove stray <?xml ?> declarations from Blade views" (8 inline declarations in
  7 driver / driver-assignment views; with `short_open_tag` On they were a ParseError on the server) and "Security:
  password reset restricted to the admin's own organization" (§9.13, commit 1).
- **2026-10-06:** "Security: user and role management restricted to the admin's own organization" (§9.13, commit 2),
  pushed. The null-safe `checkGlobal` (§9.10 step 3) and the module ID corrections (step 4) wait until the user says
  to start them.
- **Next (user, 2026-10-06): test-speed report, no code yet.** Each feature test re-runs the full `DatabaseSeeder`,
  including the 4.2 MB city seeder. The bigger clue: the same 6-test file took about 5 minutes (296 s) in a clean
  export of the repo in the temp folder, but 9–17 minutes (529 s, 809 s, 1036 s) in the working tree. The full suite
  takes 35–55 minutes. Report the options with the risk of each (seeding once per run, per-test transactions,
  splitting the seeder) and explain the working-tree gap. Don't change the test setup while permission work is still
  in flight.
- **Standing rule:** report first; the user checks, then says "commit". Never commit, push, amend, reset or stash
  without that, and a described commit ("it must be its own commit") is not permission. No database writes; tests run
  only on `fleet_freak_testing`.
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
- **Backlog, code quality and security: variable variables built from payload arrays (flagged 2026-10-02).**
  - `Partner::store_employee` starts with `foreach ($payload as $key => $val) { $$key = $val; }`. The pattern
    appears 83 times across 29 models, including Order (14), Invoice (9) and Partner (9).
  - Today every caller builds `$payload` with keys it chooses (`partner_data`, `user_data`, …), and no caller passes
    `$request->all()` straight in. So it is not exploitable as written, but it is one refactor away: a request key
    such as `partner`, `company` or `user` would silently overwrite a local variable.
  - It also hides the data flow: every variable appears from nowhere, and a missing key becomes a confusing
    "undefined variable" error far from its cause.
  - Fix: replace each with explicit reads (`$partner_data = $payload['partner_data'];`) or typed method parameters.
    Do it model by model, with tests.
- **LIVE BUGS for the mobile apps when they launch. The mobile API partner endpoints read fields their validators
  don't keep (found 2026-10-02, not fixed).**
  - The same class of bug as the employee one fixed on 2026-10-02. `validated()` returns only keys that have a
    rule, and the shared `Partner::store_*` / `update_*` methods read some keys without a default.
  - `POST /api/update-customer/{partner}` (`Api\CustomerController::api_update`) is missing `prefix_whatsapp` and
    `prefix_phone`.
  - `POST /api/create-partner` (`Api\PartnerController::api_store`) fails in every branch:
    - customer: `prefix_whatsapp`;
    - business: `prefix_whatsapp`, `source`;
    - employee: no `actor_id`, and no `call_from_employee_controller` flag.
  - `POST /api/update-partner/{Partner}` has the same problem in its customer and employee branches.
  - Check with the app owners whether these endpoints are used before fixing. The web forms are fine.
- **Public prototype page `/map` (found 2026-10-02).**
  - `Route::get('/map')` → `home_dashboard/test_map.blade.php` sits outside the auth group.
  - The page loads Leaflet from unpkg and queries Nominatim (OpenStreetMap) from the browser.
  - Nothing links to it. Remove it, or move it behind auth, during cleanup.
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
- **Backlog: the admin password reset lives in the wrong controller (noted 2026-10-05).** `PUT /change-password/{id}`
  is `OrderController@changePassword`, so its RBAC check is Orders/update (module 9): whoever may update orders may
  reset passwords, and an admin without Orders can't. It belongs with user management, `UserController` under
  Users/update (module 1). Moving it means registering `changePassword` on the Users module (an additive RBAC
  migration the user must approve before it runs on the dev DB), repointing the `users.change-password` route, and
  removing the Orders and AgentOrders registrations. The organization rule (`User::manageableBy`, §9.13) moves with it
  unchanged.
- **SECURITY: permission and ownership audit (2026-10-02). See §9 for the full record and the agreed fix order.**
  In short: agents can read, edit and delete other agents' orders; 38 routed methods in `$ignores` that write data
  are open to every logged-in user; external roles have no owner checks; 42 ERP methods reach other companies' rows
  by ID; `/agentorders` crashes for every agent; the system-role permission screen silently doesn't save.

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

0. **First: work the remaining UI fix list in §7.5.** §7.7 covers what changed on 2026-10-01. Re-verify as
   described in §7.6, report, and commit only when the user says so.
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

**Superseded in part on 2026-10-01 (§7.7):**
- In `header.blade.php`, these are now commented out:
  - the Unapproved Agents tile and its count query;
  - Vehicle Locations;
  - Create Ride;
  - the search SAMPLE chip.
- `main.blade.php` no longer has the chips or the Filter: the page now holds only the design's components.
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

### 7.5 Open UI fix list — next session's first task

1. **User-reported UI issues: TO ADD.** The user reported open UI issues at the end of the 2026-09-29 session, but
   they never reached that Claude session: no message, no comments on the review page. Get them from the user first
   and number them here, ahead of the items below.
2. **Two Refresh buttons on `/`.** The frozen old body has its own Refresh next to the header's. Options: hide the
   header Refresh on route path `/` only, or leave it until `/` switches to the new dashboard.
3. **Title truncates on phones: re-check.** At 375px an admin on a dashboard had Refresh, four tiles and the avatar,
   so the title shrank to "Main …". Since 2026-10-01 three of those tiles are commented out (§7.7), leaving Refresh,
   the bell and the avatar. Take a 375px screenshot before deciding whether anything else is needed.
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
10. **Backlog: trim `DashboardController@indexNew`** once Phase 7 settles what `/dashboard` shows. It still computes
    all the old dashboard data, which is now partly unused (§7.7).

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
   - The render test (`RenderMainDashboardTest.php`) was rewritten on 2026-10-01 for the design-only page.
   - The role-gate test (`ShellBehaviourTest.php`) still expects the header tiles that are now commented out
     (`#notificationCount`, Vehicle Locations, Create Ride). Update it before reusing it.

### 7.7 2026-10-01: design-only `/dashboard` and a simpler header

The client wants `/dashboard` to match the Figma design exactly, not a blend. Everything below is in the commit
"Dashboard and app shell: Figma design-only content, header simplified". `/` is untouched: `dashboard.blade.php`,
`DashboardController@index` and `routes/web.php` have no changes.

**What `/dashboard` contains now (admins).** Only what the design renders, in its order and proportions:
- the section label;
- **Row 1:** five KPI tiles;
- **Row 2:** Fleet Mileage vs Target (`1fr`) and Fleet Insights (340px);
- **Row 3:** Maintenance Costs (`1fr`), Fleet Status (300px) and Next Actions (340px).

The design defines six KPIs but renders only five, so "Overdue Tasks" is gone.

**KPIs.**
- Real values:
  - **Total Vehicles** is `Vehicle::countByStatus()` active + inactive, with sold vehicles excluded.
  - **Active Vehicles** is `countByStatus()['active']`.
  - **Total Drivers** is `$drivers->count()`.
- Sample values:
  - **In Maintenance:** there is no real source, because vehicles have no maintenance status. The vehicle
    dashboard's figure counts completed maintenance invoices in a date range, which is a different metric.
  - **Monthly Revenue.**
- All five change badges and sub-labels are still samples.

**Removed.** The nine existing components that aren't in the design:
- Vehicle Status, Vehicle Assignment, Payment;
- Rides Order Status, Today Vehicle Assignment, Top Agents;
- Drivers, Vehicle Details, Upcoming Busy Rides.

Also removed: the filter row (chips, Filter button and panel), the Overdue Tasks KPI, and the agents' Rides chart
and Customers list. Every one of them still exists on `/`. `docs/design-tokens-dashboard.md` → "Removed from
/dashboard" lists each with its data and its location in `dashboard.blade.php`. That list was written before
anything was deleted.

**SAMPLE marking: one chip per card,** instead of one per element.
- Each card holding any sample value has exactly one chip, at the right end of its top row; there are 10 today.
- The chip's tooltip says what is sample.
- Gone: the dashed outline on KPI badges, the second tag beside sub-labels, and the page-level note.

**Deliberate deviations from the design.**
- **Fleet Status has no "148 total vehicles" subtitle.** Next to the real Total Vehicles KPI, a second total would
  look like a bug. Its chip says the donut's figures are placeholders unrelated to the KPIs above.
- **Next Actions' priority pills sit on their own row.** They don't fit beside the title in a 340px card.

**Agent view.**
- Agents and other non-admin roles see one card, "Open my dashboard", linking to `/`. They see no fleet numbers and
  no charts.
- This is recorded as a known limitation: `/dashboard` is an admin fleet view, and an agent version needs designing
  first.

**Layout fix after the user's 1440px report.**
- **The bug:** row 3 broke into "chart on top, two below" whenever the window was under 1400px, which happens with
  zoom, display scaling or DevTools open.
- **Breakpoints:** they are now container queries on the dashboard's own width, and the side cards may narrow (to
  250px and 310px) before the row breaks. A 1440, 1366 or 1280 window (sidebar open) and a 1024 window (sidebar as
  a drawer) all show the design layout.
- **Version stamps:** the view links the CSS and JS with `?v=<file modified time>` so browsers can't keep stale copies.

**Header simplified** (`layouts/header.blade.php`, on request). Commented out with Blade comments, so nothing
reaches the HTML:
- the admin tiles Unapproved Agents and Vehicle Locations, and the agents-badge count query
  (`Partner::unapprovedAgentsCount()`, inside the same comment block, so it no longer runs on every admin page);
- the Create Ride tile;
- the SAMPLE chip beside the search box.

Unapproved Agents, Vehicle Locations and Create Ride stay reachable from the sidebar menu. Uncommenting each block
restores it; the tile block brings its count query back with it.

**Backlog.** `DashboardController@indexNew` still computes all the old dashboard data, which is now partly unused.
It's kept on purpose: some components may come back, and restoring one is markup-only.

**Verification.**
- **Scratch render test:** passes (78 assertions). It checks:
  - the card order;
  - the five KPI values;
  - one chip per card;
  - no donut subtitle;
  - the removed components, the filter, ApexCharts and the "real" chart data are absent;
  - the agent card;
  - `viewData` parity with `/`.
- **Repo tests:** `SmokeLoginTest` and `AgentDashboardTest` pass.
- **Screenshots:** 1440, 1366, 1280, 1152, 1024, 768 and 375px, light and dark, plus the agent view.
  Review page: https://claude.ai/artifact/Vkc2hKBX8P1AA1VDpmX8aF

---

## 8. 2026-10-02: employee create/edit fix, production error pages, delete-one-row fix

### 8.1 Creating or editing an employee failed: fixed

- **Symptom:** `ErrorException: Undefined array key "prefix_whatsapp"` from `Partner::store_employee`, via
  `EmployeeController::store`.
- **Cause:** `store_employee` and `update_employee` are shared with drivers. They read 10 driver-only fields with no
  default:
  - `prefix_whatsapp`, `prefix_emergency_contact1/2`;
  - `nic_expiry_date`, `license_country`, `licensee_expiry_date`;
  - `emergency_contact_no1/2`, `emergency_contact_name`;
  - `driver_license`.

  The employee form never sends them. The controller also passes `validated()`, which keeps only keys that have a
  rule.
- **History:** broken since the first commit (`ff32402`). No version of the employee form had these fields.
- **Affected:** employee create and edit, on the web (`/employees`) and the mobile API (`/api/create-employee`,
  `/api/update-employee`). Drivers send every field and are unaffected.
- **Fix (`app/Models/Partner.php`):**
  - On create, the 10 fields default to `null`. All are nullable columns; checked in the migrations and on the dev DB.
  - On edit, they are written only when the request sends them, so an employee edit (or a request that omits one)
    keeps the stored value.
  - Drivers behave as before.
- **Test:** `tests/Feature/EmployeeCreateTest.php` covers create with and without a login, an edit that keeps a stored
  driver field, and driver fields still being written. It passes.
- **Related, not fixed:** the mobile API partner endpoints and the `$$key` pattern (§5).

### 8.2 Production error pages and AJAX error toasts

**Server: `app/Exceptions/Handler.php`.**
- **Reference ID:** every failing request gets one (`FF-` + 10 characters from a ULID, via `Handler::errorRef()`). It
  appears on the error page or in the AJAX JSON, and in the log entry.
- **Reported exceptions** (500 and the like) are logged by Laravel as before, with the full exception and trace,
  plus `error_ref` and `url` in the context.
- **HTTP errors** are never "reported", so the handler writes one short line with `error_ref` instead:
  - 403 at `warning`;
  - 404, 419, 503 and other 4xx at `debug`;
  - any other 5xx from `abort()` at `error`.

  `LOG_LEVEL` must be `debug` in production for the 404, 419 and 503 IDs to have a log line.
- **JSON for AJAX** with debug off: `{message, ref}` only. The message is plain, from `Handler::PUBLIC_MESSAGES`;
  never the exception message, file or trace.
- **`APP_DEBUG=true`:** unchanged. Ignition for exceptions; the styled pages for HTTP errors.

**Pages: `resources/views/errors/`.**
- **One layout,** `shell.blade.php`. It is fully self-contained, with inline CSS and JS, an inline SVG mark and
  system fonts:
  - no CDN, no app assets, no network requests;
  - no `layouts.app`, which needs a signed-in user and the database.
- **Pages:** 401, 402, 403, 404, 419, 429, 500 and 503, plus `4xx`/`5xx` fallbacks for any other status.
- **Each page has:**
  - a plain-English message;
  - "Back to dashboard";
  - "Reload page" on 419 and 503;
  - the reference ID with a Copy button.
- **Removed:** 403 no longer prints the exception message. 503 no longer loads jQuery from a CDN or the broken
  `js/main.js`.
- **`/unauthorized`** (`home_dashboard/no_permission_found.blade.php`, where `RolePermissions` sends denied page
  requests) uses the same layout. It has no reference ID: it is a redirect target, not a logged error.
- **Unused now:** the stock `errors/layout.blade.php` and `errors/minimal.blade.php`. Left in place.

**AJAX: `public/assets/js/app-shell.js`, loaded at the end of the header, not deferred.**
- A global `ajaxError` handler shows one dismissible `msgboxbox` toast for a failed jQuery request: the plain
  message, plus the reference when the server sent one.
- It is bound to the layout's jQuery and, once the page has loaded, to a second copy if the page brought one. Five
  pages do: the 4 driver-assignment pages and the receipts index.
- It skips 422 validation errors, aborted requests, and requests cut off by leaving the page.
- **Convention for new code:** a call site that shows its own error message must opt out, or the user gets two
  messages. Wrap the call, `ffsQuiet($.post(url, data)).then(...).fail(...)`, or pass `{ ffsQuiet: true }` to `$.ajax`.
  - The flag has to be set at send time, because on jQuery 3 a `.fail()` chained after `.then()` runs after the
    global event.
  - 39 existing call sites that show their own message are wrapped.
  - The 4 call sites in the unused files `calendar/calendar_script` and `home_dashboard/calender_code_for_reference`
    were left alone.
- **`fetch()`:** the "5 call sites" from the first count turned out to be dead (backup copies and Blade-commented
  code), except `/map` (§5), a standalone page with no `msgboxbox`. None were changed.

**Config:** `.env.example` now has `APP_ENV=production` and `APP_DEBUG=false`, with a comment telling local machines
to set `local` / `true`.

**Verification:**
- `tests/Feature/ErrorPagesTest.php` passes. For each of 500, 403, 419, 404 and 503 it checks:
  - the status;
  - the plain message;
  - no exception message, path, class or trace;
  - no external stylesheet, script or font;
  - that the page's reference ID matches a log entry.

  It also checks that the full details still reach the log, the AJAX JSON shape, that debug mode keeps detailed
  errors, and `/unauthorized`.
- **Browser harness** (scratchpad `ajax-harness/`): toasts appear for 500, 419, a permission denial, a plain-text 502,
  and a 404 sent through a second jQuery copy. No toast for an opted-out call, a 422, the `$.ajax` `ffsQuiet` option,
  or an aborted request.
- **Edited views:** all 39 wrapped calls sit in inline scripts that parse before and after the edit (Node check).
- **Full suite** (with the employee fix): OK, 34 tests, 267 assertions, on `fleet_freak_testing`.

### 8.3 Deleting one record deleted every row in the company: fixed

- **Bug:** five delete methods ran `Model::find($id)->where('company_id', ...)->delete()`. Calling `where()` on a
  loaded model starts a new query without the id (`Model::__call` forwards to `newQuery()`), so the delete targeted
  every row of that table in the company.
- **Sites:**
  - `EmployeeController::destroy` (web; the partners table holds customers, agents, drivers and employees);
  - `Api\VehicleController::api_destroy` (`DELETE /api/delete-vehicle/{id}`);
  - `Api\RouteController::api_destroy` (`DELETE /api/route/delete/{id}`);
  - `Api\RateListController::api_destroy` (`DELETE /api/ratelist/delete/{id}`);
  - `Api\DriverAssignmentController::api_destroy` (no route).
- **Effect:** hard deletes (no `deleted_at`), and every foreign key to these tables is `NO ACTION`, so each delete was
  all-or-nothing. In a company where any row was referenced it failed (500, nothing deleted); where none was, the
  whole table for that company went. The new test reproduced both against the old code: the employee and route
  deletes returned 500, and the vehicle and rate-list deletes also removed the other row.
- **Fix:** `Model::where('company_id', ...)->findOrFail($id)->delete()`. Exactly one row; an unknown id, or another
  company's, is a 404 (it used to crash on null).
- **Test:** `tests/Feature/DeleteOneRecordTest.php`. For each routed endpoint it deletes one of two new rows and
  checks that the other remains, that the table count drops by exactly one, and that an unknown id is a 404.
  - With the fix: 4 tests, 20 assertions, all pass.
  - Against the old code (controllers swapped back temporarily, then restored): all 4 fail.
  - Full suite with the fix: OK, 38 tests, 287 assertions, on `fleet_freak_testing`.
- **Still open** (part of the `$ignores` problem, §9.5): the vehicle, route and rate-list API deletes are in their
  controllers' `$ignores`, so any logged-in API user can call them, now one row at a time.
- **Committed on its own** so it can be reviewed and reverted separately (the user's decision).

---

## 9. Permission and ownership audit (2026-10-02)

Found while investigating the `/agentorders` crash. Everything here was established read-only: code, git history,
SELECT-only queries on the dev DB `fleet_freak` (inside a read-only transaction), `storage/logs/laravel.log` and the
Laragon Apache access log. **Nothing in this section is fixed unless it says so.** The agreed fix order is §9.10.

### 9.1 How the permission system works (what the findings rely on)

- **`RolePermissions` middleware** (`app/Http/Middleware/RolePermissions.php`). It reads the controller's static
  `$role_module_id` and the route's method name, then looks for the user's `role_permissions` row whose permission
  type (`role_permission_types`, per module) lists that method (`role_permission_type_functions`). The role must
  also hold the module (`role_has_modules`).
  - Row found and `permission = 1`: allowed.
  - Row found and `permission = 0`: the type's denial message (`back()` for views, 401 JSON otherwise).
  - **No row: allowed if the method is in the controller's `$ignores`**, otherwise redirected to `/unauthorized`.
  - Any exception: `report()` plus 403 JSON or `/unauthorized`.
  - A controller with no `$role_module_id` is not checked at all.
- **`scopecheckGlobal($role_module_id)`** (row-level, 9 identical copies). If the role's `global` permission on that
  module is 0, the query is limited to `created_by = auth()->id()`; if 1, no limit.
- **`get_user_role_session_permissions()`** doesn't use the session despite its name. It calls
  `get_user_roles_permissions()`, which queries `role_permissions` fresh on every call. So permission changes apply
  on the user's next request; no re-login is needed. (The session caching at login is commented out.)
- **Organization scope** (Phase 1, `BelongsToOrganization`) limits 17 models to the active company: Partner, Vehicle,
  Order, OrderDetail, Invoice, PaymentHeader, PaymentLine, Account, AccountTransaction, GlJournal, Route, RateList,
  Location, Activity, VehicleModel, VehicleCompany, VehicleClass.

### 9.2 The `/agentorders` crash: "Attempt to read property "permission" on null"

- **Who:** user 3 `testingagent`, role 3 `agent`. Log references FF-SGDBKEMH2E, FF-PY6CYVANXZ, FF-FG52FSHVCX
  (2026-10-02 14:38–14:46). The crash is at `Order::scopecheckGlobal`, `app/Models/Order.php:135`.
- **Cause:** `AgentOrderController` is module 45 (AgentOrders), and the middleware lets the agent in on module 45.
  But `index()` (lines 61, 77) and `show()` (445) call `checkGlobal(9)`, module 9 (Orders). The agent role doesn't
  hold module 9, so `role_module_permission_via_action(9, 'global')` returns null and `->permission` crashes. In the
  code since the first commit (`ff32402`). Every agent hits it, whatever the admin ticks.
- **Database vs the admin screen: they match.** Role 3 holds modules 12 (Customers), 17 (Ledgers) and 45
  (AgentOrders). Its 8 AgentOrders rows (read, create, update, delete, import, export, print, global) are all
  `permission = 1`, written by the seeder on 2026-09-17 17:35 and unchanged since. It has no module 9 rows. The
  screen (`resources/views/role/form.blade.php`) draws ticks from the same rows and lists only assigned modules, so
  Orders never appears for this role.
- **The `global` checkbox** exists on every module and saves like the others, but it can't help here: an unticked
  `global` is a row with 0, which only filters; the crash needs the row to be missing, and the screen can't add
  module 9 to this role (system roles can't be saved, §9.9).
- **Sessions:** not stale; see §9.1. Logged-in users pick up permission changes on their next request.

### 9.3 PRIORITY 1: agents can read, edit, re-status and delete other agents' orders

- **Web, `AgentOrderController` (module 45; the agent role has read, update and delete ticked):**
  - `edit()` uses `checkGlobal(45)`. The agent role has `global` ticked on 45, so there is no owner filter; only the
    organization scope applies.
  - `update()` loads the order with a bare `Order::find($order)` (line 490): no owner check.
  - `destroy()` checks only the company (line 658).
  - `show()` would open the same way once its module ID is corrected, so the crash fix needs the owner rule too.
  - Changing the ID in `/agentorders/{id}/edit` reaches other agents' orders.
- **Web, order lines:** `OrderController::deleteRow` (`DELETE /delete-route-row/{id}`) is in `$ignores` and runs
  `OrderDetail::findOrFail($id)->delete()`. Any logged-in user can delete any order line in their company. The agent,
  admin order, pending-order, tour, daily-rental and rental forms all call it.
- **Forged fields:** the agent form sends `business_partner_id` in a hidden input (the "Built To" select is
  `disabled` for non-admins) and `overall_status` in a hidden input set by Save (`draft`) or Submit (`pending`). The
  server validates them only as `required` and `Order::update_order` saves them as sent. So an agent can put an
  order in another agent's name, or set their own order to `approved`.
- **Mobile API, `Api\OrderController` (module 45, the agent app):** `api_show` (`Order::find`), `api_edit`
  (`checkGlobal(45)`, no owner filter), `api_update` (`Order::find`) and `updateOrderStatus` (in `$ignores`,
  `Order::find`, any status) have no owner check. `api_store`/`api_update` accept any `business_partner_id` and
  `overall_status`. `api_destroy` and the controller's own `deleteRow` have no routes.
- **What "own order" means (decided 2026-10-02): `orders.business_partner_id = users.partner_id`.** The agent's
  ledger, commission and dashboard all key on it, and an admin can create an order on an agent's behalf; with
  `created_by` the agent wouldn't see that order.
  - Today the web and mobile agent order lists filter on `created_by` (`AgentOrderController.php:77`,
    `Api/OrderController.php:83`). The dashboard, both ledgers and the mobile partner-order endpoints use
    `business_partner_id`.
  - Dev data: 2 orders, both `approved`. The agent list hides `pending` and `approved`, so the agent sees 0 rows
    either way. Order 1 is the admin-on-behalf case (`created_by` 2, `business_partner_id` 3): it shows only under
    the new rule.
  - Agents lose nothing by the switch. For agents the "Built To" choice is locked to their own partner
    (`Partner::BusinessPartnerDropdown`), so an order they created is always theirs; the dev DB has 0 orders that
    break this. The switch only adds orders an admin created for them.

### 9.4 Deleting one record deleted every row in the company

Fixed in its own commit, "Security: delete endpoints removed every row in the company, not one". Details in §8.3.

### 9.5 `$ignores` lets every logged-in user call 38 methods that write data

- **What `$ignores` really does:** an ignored method is allowed whenever the user's role has no permission row
  covering it. That is every role that doesn't hold the module, and every role at all if the method isn't
  registered for the module. Roles that do hold the module, with the method registered, are still checked. It was
  meant as "skip the check for helper actions"; it works as "open to any logged-in user". For API routes that means
  any Sanctum token: agents, drivers, vehicle managers, employees, vendors.
- **Scale:** 101 routed methods are listed in a module controller's `$ignores`. **38 of them write data** (create,
  update, delete or post accounting entries); 63 only read (same bypass; reads stay within the organization scope
  where it applies). `NotificationController` has no module, so its 4 routes are unchecked by design and not
  counted.
- **Correction (2026-10-05):** the first count said 33. It matched method names: it missed the 9 mobile create
  endpoints and the mobile vehicle delete (that route line has unusual spacing), and it counted 5 read-only methods
  (`getallride_assign_to_driver`, `getStatusCount`, `api_vehicle_status`, `role_module_create`,
  `create_maintainence`). The 38 below come from reading each method body.
- **Web (13):**
  - `RoleController@role_module_update`, `POST /roles/module/update/{role_id}`: replaces any non-system role's
    module list. System roles are refused; there is no client check.
  - `AgentPaymentController@update_status`, `POST /payments_update/{id}`: changes a payment's status and posts
    accounting entries.
  - `BulkPaymentController@payment_window_update`, `POST /payment_window/{id}/update`: updates a payment window and
    posts accounting entries.
  - `CustomerController@create_customer_from_order`, `POST /customer/modal/create`: creates a customer.
  - `DriverAssignmentController@assignVehicle`, `POST /driver_assignments/assign_vehicle`; and
    `@incompleteRidesUpdate`, `POST /driver_assignments/incomplete_rides`: dispatch writes.
  - `OrderController@deleteRow`, `DELETE /delete-route-row/{id}`: deletes an order line (§9.3).
  - Line deletes with no company check, so they reach other companies' rows:
    `ActivityController@deleteActivityRow` (`/delete-activity-row/{id}`, activity lines),
    `InventoryMoveController@deleteActivityRow` (`/delete-movement_line-row/{id}`, movement lines),
    `PhysicalInventoryController@deleteRow` (`/delete-phy_inv_line-row/{id}`, movement lines),
    `PriceListController@deleteActivityRow` (`/delete-version-row/{id}`, price list versions),
    `ProductController@deleteActivityRow` (`/delete-product_price-row/{id}`, product prices).
  - `PurchaseOrderController@deleteActivityRow`, `/delete-poline-row/{id}` and `/delete-inoutline-row/{id}`: order
    lines, limited to the user's company by the organization scope.
- **Mobile API (25):**
  - Vehicles: `api_store` (`POST /api/create-vehicle`), `api_update` (`POST /api/update-vehicle/{vehicle}`),
    `api_destroy` (`DELETE /api/delete-vehicle/{id}`).
  - Routes: `api_store` (`POST /api/route/create`), `api_update` (`POST /api/route/update/{route}`), `api_destroy`
    (`DELETE /api/route/delete/{id}`).
  - Rate lists: `api_store` (`POST /api/create-ratelist`), `api_update` (`POST /api/update-ratelist/{ratelist}`),
    `api_destroy` (`DELETE /api/ratelist/delete/{id}`).
  - Locations: `api_store` (`POST /api/create-locations`), `api_update` (`POST /api/update-locations/{locations}`),
    `api_destroy` (`DELETE /api/locations/delete/{id}`).
  - Toll tax: `api_store` (`POST /api/store-toll-tax`), `api_update` (`PUT /api/update-toll-tax/{id}`),
    `api_destroy` (`DELETE /api/delete-toll-tax/{id}`).
  - Partners and employees: `Api\PartnerController@api_store` (`POST /api/create-partner`) and `@api_update`
    (`POST /api/update-partner/{Partner}`); `Api\EmployeeController@api_store` (`POST /api/create-employee`) and
    `@api_update` (`POST /api/update-employee/{partner}`).
  - Vehicle reference data: `Api\VehicleClassController@api_store` (`POST /api/store-vehicle-class`),
    `Api\VehicleCompanyController@api_store` (`POST /api/store/vehicle/company`),
    `Api\VehicleModelController@api_store_vehicle_model` (`POST /api/create-vehicle-model`).
  - Dispatch: `Api\DriverAssignmentController@api_vehicle_assign` (`POST /api/vehicle-assign`) and
    `@updateRideStatus` (`POST /api/ride-status-update-driver`). `updateRideStatus` checks the ride's driver itself
    (only rides assigned to the caller's partner), so the bypass there is harmless.
  - Orders: `Api\OrderController@updateOrderStatus` (`POST /api/order/updatestatus/{id}`), §9.3 and §9.9.
- **Fixing it changes behaviour for the mobile apps** (methods they may rely on would start needing permissions), so
  it ties into the 13 unregistered mobile API actions in §5. Plan it as its own item after §9.10 steps 1–5; don't
  fold it into another change.

### 9.6 No owner checks for external roles

- Every non-admin role has `global` ticked on every module it holds (seed data), and all 8 seeded roles are system
  roles that can't be edited (§9.9). So `checkGlobal` restricts nobody today.
- Modules held by external roles (dev DB, 2026-10-02): agent: Customers, Ledgers, AgentOrders. Driver: Customers,
  DriverAssignment, Ledgers, Maintenance, Inspection. Management and office_staff: Customers, Ledgers. Vehicle
  manager: Vehicle, VehicleClass, VehicleModel, VehicleCompany, Maintenance, Calendar, Inspection. Vendor: none.
  Every action is ticked on all of them.
- **Effect:** these roles can edit or delete any record in their company in those modules:
  - Customers: agents and drivers can change or delete other agents' customers (`CustomerController@update` and
    `destroy` check only the company). The mobile `Api\CustomerController@api_update` also sets the customer's
    `business_partner_id` to the caller, moving the customer to them.
  - DriverAssignment, Maintenance, Inspection: no owner check on edit, update or destroy.
  - Vehicle: vehicle managers aren't limited to their `vehicle_managers` rows.
- Each module needs its own "own" rule, decided the way §9.3 decided it for orders.

### 9.7 Cross-company access on tables outside the organization scope

- 42 show/edit/update/destroy methods load a record by ID with no company check, on 18 models whose tables have
  `company_id` but aren't in the organization scope:
  - all four methods: ProductPrice, Locator, ProductCategory, ProductSubCategory, ProductType, AccountType;
  - destroy, edit, show: PartnerLocation;
  - destroy, update: WareHouse, StockStorage, Brand, ManufacturingCompany;
  - update: UnitMeasure, PriceList, Product, MaterialInout, InventoryMove, InventoryConsumption, BroadcastMessage.
- Only admins hold these modules today, so the exposure is an admin of one organization reaching another
  organization's rows by ID. This puts a number on the cost of the §3 item 3 deferral.
- 14 more methods are on 6 models whose tables don't exist in the dev DB (PhysicalInventory, Tax, ProductCosting,
  M_MatchPo, Sale, ServiceProvider), so those pages can't work at all today. Check them against the migrations
  when each module is touched. 5 methods are on tables with no company column (InvoiceDocumentType, Blog): shared
  data by design.
- The ignored line deletes in §9.5 (activity lines, movement lines, price list versions, product prices) reach other
  organizations' rows for any logged-in user, not just admins.

### 9.8 `scopecheckGlobal` crashes on a missing permission row; wrong module IDs

- All 9 copies (Actor, Order, Partner, RateList, Role, RoleModule, Route, User, Vehicle) read `->permission` on the
  result without a null check.
- 13 `Order` call sites pass a module that isn't their controller's own:

  | Controller (own module) | Passes | Methods | Today |
  |---|---|---|---|
  | AgentOrderController (45) | 9 | index, show | crashes for every agent |
  | TourServiceController (43) | 9 | index, show, edit | no crash |
  | DailyRentalController (42) | 9 | index, show, edit | no crash |
  | RentalVehicleController (41) | 9 | show, edit | no crash |
  | VendorController export (67) | 13, via `AgentExport` | export | no crash; downloads agents (§9.9) |

  The "no crash" rows survive only because the admin is the only role holding those modules and also holds Orders
  (and BusinessAgent). Their own module's `global` tick has no effect; the Orders tick decides. Correcting the IDs
  changes nothing for the admin today: role 2 has `global` ticked on all of them.
- Every other call site passes its own module. All 88 role-module assignments in the dev DB have a `global` row,
  so nothing else crashes today. **Remaining risk:** a permission type added to a module later has no row for
  existing roles until they're re-saved, and every page in that module would crash.
- **Agreed fallback:** a missing row is treated like an unticked `global`: the user sees only rows they created.
  They never lose access and never gain any; the middleware has already decided they may open the page. Log a
  warning naming the user, role and module so a misconfiguration shows up. Write it once, shared by all 9 models.
- **Show pages need the 404 page** when the record isn't found: `agent-order/show.blade.php` and others use
  `$order->...` directly and would crash in the view instead.

### 9.9 Other findings

- **The vendor export downloads agents.** `VendorController@export` uses `AgentExport` (`agents.xlsx`, a module 13
  check). It needs its own export class and module.
- **System roles show editable checkboxes that silently don't save.** `RoleController@update` and
  `role_module_update` refuse `is_system = 1` roles with "System Roles are not editable", and all 8 seeded roles are
  system roles. But the edit screen still shows every checkbox and a Save button. An admin believes they granted a
  permission and nothing changed; the page reloads showing the stored state. Make the screen read-only for system
  roles (with a note saying why), or allow editing them. Not fixed; report only.
- **Performance backlog:** the permission query in §9.1 runs several times per request (the middleware, each
  `checkGlobal`, the sidebar), each time with eager loads. Memoize it per request. Not fixed; report only.
- **Who calls `POST /api/order/updatestatus/{id}`: only the agent flow** (checked 2026-10-02):
  - The driver app updates rides through `POST /api/ride-status-update-driver` (`updateRideStatus`, module 14, which
    the driver role holds). The one driver status call in the access log is the user's test on 25 Sep (200).
  - Nothing in the repo calls `/api/order/updatestatus`, and the access log (Aug 2024 to 2 Oct 2026) has never
    recorded a request to it. It lives in the agent API controller (module 45); the driver role doesn't hold that
    module and only gets in through `$ignores`.
  - The admin app has its own `/api/admin-order/updatestatus/{id}` (`AdminOrderController`, module 9). That's where
    the `updateOrderStatus` permission registration (module 9) actually belongs.
  - `updateRideStatus` never sets the order to `completed`: the line has been commented out since the first commit
    (`Api/DriverAssignmentController.php:793`). The driver lists filter on the ride's status, and the admin's
    completed-rides list expects the order to stay `approved`.
  - Caveat: the mobile apps' code isn't in this repo and the log only shows local traffic. Strong evidence, not
    proof.

### 9.10 Agreed fix order and decisions (2026-10-02)

**Decisions:**
- "Own order" for agents = `business_partner_id = users.partner_id` (§9.3).
- Agents may set only `draft` or `pending` through order create/update, web and mobile.
- `POST /api/order/updatestatus/{id}`: admins can set any order; agents only their own orders, and only to `draft`,
  `pending` or `cancelled`; every other role gets 403. Restrict it as planned, since it's agent-only (§9.9).
- The delete-all fix is part of this work but is its own commit, first.
- Every step: build, test on `fleet_freak_testing`, report, and wait for the user to say "commit".

**Order:**
1. **Delete-all fix** (§8.3). Done and committed on its own.
2. **Agent order ownership** (§9.3). Done and committed; what changed is in §9.11. The plan was:
   - Web `AgentOrderController`: `show`, `edit`, `update` and `destroy` load through the ownership rule, and another
     agent's order is a 404 (not 403, so the ID's existence isn't confirmed). The `index` agent branch switches
     from `created_by` to the rule. `index` and `show` move from `checkGlobal(9)` to module 45, which fixes the
     `/agentorders` crash.
   - For agents, `store` and `update` ignore the posted `business_partner_id` (always their own) and accept only
     `draft` or `pending`.
   - `OrderController::deleteRow`: for agents, the line's order must pass the rule, or 404. Admins unchanged.
   - Mobile `Api\OrderController`: `api_index` uses the rule; `api_show`, `api_edit`, `api_update` and
     `updateOrderStatus` load through it (404 JSON); `api_store`/`api_update` get the same field enforcement;
     `updateOrderStatus` gets the role rules above. The unrouted `api_destroy` and `deleteRow` are left alone.
   - Test, `tests/Feature/AgentOrderOwnershipTest.php`: two agents in one company, each with an order and a line,
     plus an admin-created order for agent A.
     - A on B's order: show, edit, update and destroy are 404 with B's order unchanged; deleting B's line is 404
       with the line kept.
     - A on A's own order: edit, update and destroy work.
     - A posts B's `business_partner_id` and `overall_status=approved`: saved as A's, status `pending`.
     - A's list shows the admin-created order for A and not B's.
     - Mobile, as A on B's order: show, edit, update and updatestatus are 404, order unchanged; on A's own order
       they work; status rules enforced; a non-agent, non-admin role gets 403.
     - The admin is unaffected, including deleting lines on any order.
3. **Null-safe `checkGlobal`** with the warning log, shared by all 9 models (§9.8).
4. **Module ID corrections** for TourService, DailyRental and RentalVehicle, and the 404 page for missing records
   (§9.8).
5. **Vendor export** gets its own export class and module (§9.9).

**Not scheduled yet; each needs its own plan and the user's approval:**
- The `$ignores` bypass (§9.5): changes mobile behaviour; ties into the 13 unregistered mobile API actions (§5).
- Owner rules for the other external-role modules (§9.6).
- Organization scoping for the ERP tables (§9.7, §3 item 3).
- The system-role permission screen and the permission query memoization (§9.9).

### 9.11 Agent order ownership (§9.10 step 2): what changed (2026-10-05)

**The rule, defined once on `App\Models\Order`:**
- `Order::isAgent($user)`: `actor_id` 4.
- `scopeAccessibleBy($user)`: limits an agent to `business_partner_id = users.partner_id` (an agent with no partner
  sees nothing); every other user is unchanged, and the organization scope still applies.
- `Order::applyAgentRules($orderData, $user)`: for an agent, forces `business_partner_id` to their partner and turns
  any `overall_status` other than `draft` or `pending` into `pending`. Other users' data is untouched.

**Web:**
- `AgentOrderController`: `show`, `edit`, `update` and `destroy` load through one private `findOrder()`: the
  user's company, `checkGlobal(45)` and the rule, else 404. `index` uses module 45 in both branches (it used 9,
  which crashed for every agent), and its agent branch filters by the rule instead of `created_by`; non-agents keep
  `created_by`. `store` and `update` apply the field rules.
- `OrderController::deleteRow` (`DELETE /delete-route-row/{id}`, shared by every order form): an agent gets a 404
  JSON unless the line's order is theirs. Everyone else is unchanged.

**Mobile API (`Api\OrderController`):**
- `api_index` filters agents by the rule (non-agents keep `created_by`).
- `api_show`, `api_edit` and `api_update` load through the rule; a missing order is now a 404 JSON
  (`{"message": "Order not found"}`) where `api_show`/`api_edit` used to return 200 with `order: null`.
- `api_store` and `api_update` apply the field rules, on the draft path too.
- `updateOrderStatus`: admins (`actor_id` 2) may set any order in their organization; agents only their own, and
  only to `draft`, `pending` or `cancelled` (otherwise 422); every other user gets 403, the super admin (`actor_id` 1)
  included. Nothing suggests the super admin uses this mobile endpoint; say so if that's wrong.

**Test:** `tests/Feature/AgentOrderOwnershipTest.php`, 8 tests, 64 assertions, all pass. Two agents in one company,
each with a draft cargo order and a line, plus a cancelled order an admin created for agent A.
- Agent A on B's order, web: show, edit, update, destroy and the line delete are all 404; B's order, status, owner,
  amount and line are unchanged.
- Agent A on A's own order, web: show and edit open; the line delete works; update saves; destroy works on an order
  with no lines (see below).
- Forged `business_partner_id` (B's) and `overall_status=approved` on web create and update: saved as A's, `pending`.
- A's web list holds exactly A's order and the admin-created order for A.
- Mobile, A on B's order: show, edit, update and updatestatus are 404, B unchanged. On A's own: show, edit and the
  list work; forged owner and status on update and create are saved as A's, `pending`; updatestatus to `cancelled`
  works and to `approved` is a 422 with the status unchanged.
- updatestatus as a driver: 403, unchanged. As an admin: works on any order.
- An admin can still delete lines on any order.
- **Against the old code** (the four files swapped back temporarily, then restored), 7 of the 8 fail: web create
  saved the order under B's partner; the mobile app showed B's order to A; a driver changed an order's status; the
  web list and show pages crashed. The eighth (admin line delete) was already correct.

**Found while doing it (pre-existing, not fixed):**
- **An order that still has lines can't be deleted by anyone.** `order_lines.order_id` is a `NO ACTION` foreign key
  and neither `AgentOrderController::destroy` nor `OrderController::destroy` removes the lines first, so the delete
  fails with a foreign-key violation (500). Confirmed with a throwaway test on `fleet_freak_testing` (2026-10-05).
  Since every real order has lines, the order delete button never works. `OrderController::destroy` also crashes
  on an unknown id (`find()->delete()` on null).
- **An admin can reset any user's password in any organization.** `PUT /change-password/{id}`
  (`OrderController@changePassword`, module 9, used by the user, customer, driver, employee, vendor, agent and
  profile screens) runs `User::findOrFail($id)` with no company or role check, and `User` isn't organization-scoped.
  An admin of one organization can set the password of another organization's users, the super admin included.
  Non-admin roles can't use it at all, even for their own password from the profile page (they don't hold Orders).
- **Mobile order create/update can file a new customer under another agent.** When no `customer_partner_id` is sent,
  `api_store`/`api_update` create the customer with `business_partner_id` taken from the request's
  `customer_business_partner_id`. Belongs with the customer ownership rule (§9.6).

### 9.12 User and role management audit (2026-10-05, read-only, not fixed)

Prompted by the password finding in §9.11. `users` is deliberately outside the organization scope (§3 item 3: it is
the access model), so every user-management action has to check membership itself. Tenancy: a client
(`clients`, owner `clients.user_id`) has organizations (`companies`); users join organizations through
`user_companies`. The super admin (`is_super_admin = 1`, role 1 "Supper Admin", `actor_id` 1) has no client and no
organization.

- **Password reset, `PUT /change-password/{id}`** (`OrderController@changePassword`, gated by Orders/update):
  `User::findOrFail($id)`, no organization, client or role check. An admin can set any user's password in any
  organization, including the super admin's. Existing sessions and API tokens of the target stay valid.
- **Own password:** the profile page's form posts to the same route, so only roles holding Orders can change their
  own password there. `/password/change` (the agents' first-login page, `PasswordChangeController`) changes the
  caller's own password with no current-password check, and any logged-in user can reach it. The mobile
  `POST /api/change-password` is correct (own account, old password required).
- **`password.update` name collision:** `Auth::routes()` names the password-reset POST `password.update`;
  `routes/web.php` reuses the name for `/password/change`, so the forgot-password form
  (`auth/passwords/reset.blade.php`) posts to the logged-in change page. Email password reset can't work today.
- **Edit user, `PUT /users/{id}`** (`UserController@update`): route-model binding loads any user, no organization or
  client check (`edit()` checks both; `update()` doesn't). An admin can change any user's name, email and role,
  the super admin's included. `role_id` isn't validated, so it can be any role: another client's, or role 1. Setting
  role 1 gives its platform modules (RoleModules, Actors, AccountTypes, InvoiceDocumentType), including to the
  admin themselves. The update also clears the user's image when none is uploaded.
- **Create user, `POST /users`** (`UserController@store`): `role_id` is only `required`. The form lists the client's
  roles, but the server accepts role 1 (the new user then gets `actor_id` 1 and the super admin dashboard) or
  another client's role.
- **Show user** checks the client but not the organization: users of a sibling organization in the same client are
  visible.
- **Correction (2026-10-06):** an earlier version of this list said "the edit form's role list holds every client's
  roles". That was wrong. `UserController@edit` did compute every client's roles (`Role::whereNot('actor_id', 1)`),
  but the view never used them: the create and edit forms built their own drop-downs with
  `Role::dropdown(client_id)`, the caller's client's roles only. The drop-downs were always correct; the hole was the
  missing server-side validation. See §9.13.
- **Delete user** (`UserController@destroy`) is broken: it looks the user up with `where('id', <the caller's own
  id>)`, so deleting anyone else is a 500 (null `->delete()`), and only self-deletion goes through. Hard delete.
- **Deactivate:** no such feature. `users` has no active/status column.
- **Role permissions, `PATCH /roles/{id}`** (`RoleController@update`): route-model binding, only system roles are
  refused, no client check. Worse, it writes `RolePermission::where('id', $posted_permission_id)` without checking
  the row belongs to the role being edited. An admin who edits any custom role of theirs can switch any permission
  row in the system on or off: system roles, the super admin role, other clients' roles. This bypasses "System
  Roles are not editable". (`role_module_update` in §9.5 is the same problem for module lists.)
- **Custom roles can hold platform modules:** `RoleController@store` accepts any existing module id, including
  RoleModules and Actors, so an admin can build a role with platform-wide powers and assign it.
- **Agent approval** (`UnapprovedAgentController@update`) creates the login with `role_id` 4 hard-coded: role 4 is
  `driver` in the dev DB, not `agent`. The new user has no client, no active organization and no membership, so
  `AfterAuthentication` sends them to company registration. By code reading; not exercised.
- **Public self-registration, `POST /api/register`** (`Api\LoginController@register`, no auth): anyone can create a
  user with no role, client or organization and receive an API token. With it, every `$ignores` API method lets
  them through (§9.5). Reads of organization-scoped models fail closed (no active organization), but creating
  records doesn't pass through the read scope, so what that token can write needs checking. Not exercised.
  **Deferred:** the user is focusing on the web app for now (2026-10-05); mobile API items wait.
- **Also noticed:** `php artisan route:list` crashes: a route points at a missing
  `App\Http\Controllers\RolePermissionTypeController`.

### 9.13 User-management fixes (approved 2026-10-05)

**Decisions (the user's, 2026-10-05):**
- One rule decides which users a user may manage: `User::scopeManageableBy($actor)`. A super admin may manage anyone
  but other super admins. Anyone else: users of their own client who belong to their active organization and to no
  organization the actor isn't in (so one organization's admin can't take over a user's access to another), and who
  are neither a super admin nor the client's owner. Admins in the same organization may still manage each other.
- An admin's own password is changed on the profile page (current password required), never through the admin reset.
- An admin reset revokes the user's API (Sanctum) tokens and logs who reset whom. "Don't touch the mobile apps" means
  don't change their endpoints or response shapes; invalidating a stale token is what a reset is for.
- The first-login page is only for agents who must still set their password, and its route name no longer clashes
  with the forgot-password reset.

**Commit 1, passwords (committed, "Security: password reset restricted to the admin's own organization"):**
- `User::scopeManageableBy()` and `User::mustChangePassword()` (`actor_id` 4 and `flag` 1, shared with
  `AfterAuthentication`).
- `PUT /change-password/{id}` (`OrderController@changePassword`, still gated by Orders/update; moving it to the
  Users module needs an RBAC migration, so it's backlog): the caller's own id is refused ("use your profile page");
  the target loads through the rule, else 404; `password` is now `required|min:8|confirmed` (an empty form used to
  500); the user's API tokens are deleted; one `notice` log line with `actor_id` and `user_id`. The target's web
  sessions also end on their next request: `AuthenticateSession` is in the `web` group and the password hash changed.
- `PUT /user-profile/password` (`user-profile.password`, `UserProfileController@updatePassword`): any logged-in user,
  own account only, `current_password` required, new password confirmed, at least 8 characters, different from the
  current one. The user stays signed in (`AuthenticateSession` stores the new hash at the end of that request); their
  other web sessions end. The profile page's form posts here; the shared `user.partials.profile-password` modal shows
  a "Current Password" field only when the caller passes `current_password`.
- `/password/change` (`PasswordChangeController`): users who don't have to change their password are sent to the
  profile page. Its POST is now named `password.change.update`, so `route('password.update')` is the forgot-password
  reset again (`auth/passwords/reset.blade.php` posts to `/password/reset`). Email reset still needs mail configured.
  The dead Breeze partial `profile/partials/update-password-form.blade.php` (its `/profile` routes point at a
  `ProfileController` that `routes/web.php` doesn't import) also uses `password.update`; left alone.
- Test `tests/Feature/PasswordManagementTest.php`, 8 tests, 42 assertions, all pass (full suite: OK, 54 tests,
  392 assertions; the own-password test also checks the user is still signed in afterwards): a reset inside the
  organization works and revokes the token and logs; a user in another client, in a sibling organization, shared with
  an organization the admin isn't in, and the super admin are all 404 with the password unchanged; another admin
  can't reset the client owner; an admin's own id is refused; an agent changes their own password (wrong current
  password and same-as-current are rejected); the first-login page turns away a normal user and still works for a
  flagged agent; `password.update` is `/password/reset` and the reset form posts there. Against the old code
  (9 files swapped back temporarily) 7 of 8 fail; the flagged-agent case passes before and after.

**Commit 2, users and roles (committed 2026-10-06, "Security: user and role management restricted to the admin's own
organization"):**
- `Role::scopeAssignableBy($actor)`: the caller's client's roles, never the super admin role (custom roles have a
  null `actor_id`, which counts as allowed); a user with no client gets none.
- `UserController`: `show`, `edit` and `update` load the user through `manageableBy` (404 otherwise; `update` no
  longer uses route-model binding). `store` and `update` validate `role_id` and `role_id_hidden` against the
  assignable roles; when neither is sent, the current role is kept.
- Role drop-downs (`user/form.blade.php`, `user/edituserform.blade.php`) now list the controller's `$roles`
  (`Role::assignableBy`), so the drop-down and the server check share one rule. **They were already correct before
  this change; don't "re-fix" them.** Both forms used to build their own list with `Role::dropdown(client_id)`: the
  caller's client's roles only, so the super admin role (no client) and other clients' roles never appeared. The
  controller's own `$roles` was computed but never rendered. Today both lists hold the same roles; the change is
  only so they can't drift apart. The security fix is the server-side validation, and that's what the test proves;
  the test deliberately has no drop-down check, because one couldn't tell the old code from the new.
- `RoleController@update`: the role loads through the caller's client (404 otherwise), as `edit` does. A posted
  `permission_id` is only written when the row belongs to this role and module; a new row is only created when the
  permission type belongs to the module.
- Test `tests/Feature/UserRoleManagementTest.php`, 6 tests, 48 assertions, all pass (full suite before the
  drop-down change: OK, 60 tests, 442 assertions). Against the old code (a clean export of
  `0d3c2f1` with the new test, so the working tree wasn't touched) 5 of 6 fail; the one that passes is "admin can
  still view and edit a user in their organization", which is meant to pass on both. The tests: show, edit and update of a
  user in another client, in a sibling organization, and of the super admin are 404 and change nothing; editing a
  user in the organization still works; a second admin trying to give themselves the super admin role is refused
  (role and actor unchanged); the client owner trying the same is a 404; the super admin role and another client's
  role are refused on edit (also via `role_id_hidden` alone) and on create; a role edit saves its own row but leaves
  forged admin and super admin rows untouched, and another client's role is a 404.
- Found while building it (pre-existing, not fixed): `RoleController@destroy` has `if($role->is_system=1)`, an
  assignment, so no role can ever be deleted; `User::update_user` reads `phone_no1`, `phone_no2` and `description`
  without defaults (the edit form always sends them, so only a hand-made request hits the 500).

**Commit 2 plan, as approved:**
- `UserController@update`, `show` and `edit` load the user through `manageableBy` (404 otherwise).
- `store` and `update` accept only assignable roles: the caller's client's roles, never the super admin role
  (`actor_id` 1). The create/edit role drop-downs use the same list.
- `RoleController@update`: 404 for another client's role; only permission rows that belong to the role being edited
  are written.
- Tests: an organization A admin can't view or edit a user in organization B or the super admin; the super admin
  role and another client's role are rejected on create and edit; a forged permission-row id leaves that row
  unchanged.
- Not in this work (reported only): delete user (broken), deactivate (no such feature), custom roles holding
  platform modules, the agent-approval role, `POST /api/register` (web focus).
