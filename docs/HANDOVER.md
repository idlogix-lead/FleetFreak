# FleetFreak Re-Architecture — Session Handover (2026-09-24)

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
| 1 | Tenant/organization data isolation | DONE (commit `daf4ac3`) + follow-up WIP (see §3/§4) |
| 2 | Accounting hardening (posting rules, transactional safety, reversals) | not started |
| 3 | Asset core (`assets`/`asset_types` wrapper around vehicles) | not started |
| 4 | Maintenance generalization (plans, usage logs, work orders) | not started |
| 5 | Subscriptions (greenfield) | not started |
| 6 | Employee expenses (greenfield) | not started |
| 7 | Dashboards & reports | not started |

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
`php artisan test` (2-3 minutes; dominated by the full `DatabaseSeeder`,
which includes a 4.2 MB city seeder). Seeded web login: `admin@idl.pk` /
`00000000` (created by `RolePermissionSeeder`, `active_company_id = 1`).
PHPUnit 10.5 (was never installed before this program — `phpunit/phpunit`
is in require-dev).

---

## 2. Current state

- Branch: `moeen`. Commit history:
  `ff32402` first commit, `c734f18` bootstrap+docs, `721f238` Phase 0,
  `6e1ab1f` housekeeping, `daf4ac3` Phase 1.
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

---

## 3. Work in progress — six Phase 1 follow-up items (UNCOMMITTED)

The client asked for a verification report on six items before Phase 2 starts.
Status per item, with files touched:

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
   trait-scoped model, so all are scoped — except the two dead ones in §5),
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

4. **Isolation test extension — PARTIAL (the active work).**
   File: `tests/Feature/OrganizationIsolationTransactionsTest.php` (NEW).
   Model-level isolation for Order, OrderDetail, Invoice, PaymentHeader and
   AccountTransaction across two organizations: PASSING. Customer-export
   scoping assertion: written but not yet reached in the run. Ledger HTTP
   check: IN PROGRESS — see §4.

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

**Current `git status` (before this handover commit):**

```
 M .gitignore
 M app/Console/Commands/AccountingAudit.php
 M app/Http/Controllers/AccountController.php
 M app/Http/Controllers/Api/LedgerController.php
 M app/Http/Controllers/LedgerController.php
 M app/Models/VehicleClass.php
?? tests/Feature/OrganizationIsolationTransactionsTest.php
```

All of the above is Phase 1 follow-up work, verified green except item 4 —
the suite currently passes 15 of 16 tests.

---

## 4. Half-finished

**`tests/Feature/OrganizationIsolationTransactionsTest.php`** still contains
TEMPORARY DIAGNOSTIC CODE that must be removed before committing:
`DB::enableQueryLog()` plus a STDERR dump printing the ledger page's
`<tbody>` region (the last diagnostic added, not yet run).

What the debugging established so far (three REAL pre-existing bugs, all
fixed in this WIP in `LedgerController` and `Api\LedgerController`):

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
   `payment_headers.company_id`) per leg. Verified via query log: SQL and
   bindings are now correct and scoped.

**The remaining mystery**: with correct SQL and bindings, organization B's
`/ledgers` page returns 200 and contains NO cross-organization names (so
isolation holds), but `assertSee('Agent Org B')` fails — the expected row is
missing from the rendered page. The tbody dump was added to see what the
table actually renders.

**Next actions, in order:**
1. `php artisan test --filter=OrganizationIsolationTransactionsTest` and read
   the `=== LEDGER TBODY ===` output.
2. Resolve why the row is missing (suspects: the `OPN` opening-row special
   case in the blade, seeder-randomized ids changing fixture relationships,
   or the union's grouping interacting with the view).
3. Remove BOTH diagnostic blocks; restore clean assertions
   (`assertStatus(200)` plus `assertSee`/`assertDontSee` for each user).
4. Full suite green (expect 16/16), then commit the whole follow-up batch on
   `moeen` per the standing rule.

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
- **Dead ledger exports**: `app/Exports/AgentLedgerExport.php` and
  `app/Exports/DriverLedgerExport.php` import `App\Models\Ledger`, which does
  not exist — those export routes fatal if ever hit. Repair or remove in
  Phase 7.
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

---

## 6. Next steps for a fresh session

1. Read `docs/TECHNICAL_KNOWLEDGE_BASE.md`, then this document.
2. Finish §4: run the filtered test, read the tbody diagnostic, resolve the
   missing ledger row, strip the diagnostic code, get the full suite green.
3. Commit the follow-up batch (files listed in §3) on `moeen`.
4. Deliver the six-item follow-up report to the client (§3 is the substance;
   §5 lists the deferred items to mention).
5. Start **Phase 2 — accounting hardening** per the plan:
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
6. Keep the cadence: every phase = implement, verify, report, commit on
   `moeen`, stop for client go-ahead.
