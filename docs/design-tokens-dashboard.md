# Main dashboard and app shell: design tokens and Phase 7 checklist

The redesigned main dashboard at `/dashboard` (`DashboardController@indexNew`) follows the Figma Make file
"FleetFreak Final Design – HomePage". `/` (`DashboardController@index`, `home_dashboard/dashboard.blade.php`)
is unchanged and is the fallback. Since 2026-10-01, `/dashboard` shows only what the design shows: five KPIs,
then Fleet Mileage vs Target with Fleet Insights, then Maintenance Costs, Fleet Status and Next Actions (see
[Removed from /dashboard](#removed-from-dashboard-2026-10-01) for what was taken out). The same design's sidebar and
top bar are applied app-wide; see [App shell](#app-shell-sidebar-and-top-bar).

| Piece | File |
|---|---|
| View | `resources/views/home_dashboard/main.blade.php` |
| Styles | `public/assets/css/main-dashboard.css` |
| Scripts | `public/assets/js/main-dashboard.js` |
| Sample data | the `$placeholderData` block at the top of `main.blade.php` |

**Scoping rule.** Everything sits under a `.ffd` root element, and every class uses the `ffd-` prefix. The one rule outside
`.ffd` is `.page-content:has(> .ffd) { padding: 0 }`, which only matches the page that contains `.ffd`. The view uses
no inline CSS or JS. Chart data reaches the script through one JSON block, `<script type="application/json" id="ffd-data">`.

**Libraries.**
- Chart.js comes from the layout's CDN tag (currently resolves to 4.5.1). The script also sets the v3 option keys.
- Fonts come from Google Fonts: DM Sans, Inter and DM Mono.
- `main-dashboard.js` is loaded for admins only; the agent view has no charts.
- The view links both files with `?v=<file modified time>`, so a changed file is never served from an old browser cache.

---

## Tokens

The tokens are CSS custom properties on `.ffd`. The design brand `#63205f` is mapped to the repo's existing plum `#640D5F`,
with `#9B3496` as its hover colour. The dark values apply under `html.dark-theme`. The design has no dark variant, so they
follow the surfaces in Synadmin's `dark-theme.css`.

### Colour

| Token | Light | Dark | Used for |
|---|---|---|---|
| `--ffd-brand` | `#640D5F` | same | primary buttons, active priority pill, chart series 1 |
| `--ffd-brand-hover` | `#9B3496` | same | primary hover, focus ring |
| `--ffd-brand-text` | `#640D5F` | `#d4a0d0` | brand-coloured text on pale backgrounds |
| `--ffd-brand-pale` | `#f5eef5` | `rgba(155,52,150,.18)` | icon tiles, pills, tags, task list header, soft buttons |
| `--ffd-brand-muted` | `#e8d5e7` | `rgba(155,52,150,.40)` | chart series 2 (bars), soft button hover |
| `--ffd-bg` | `#f8f6f8` | `#1e1e1e` | page background |
| `--ffd-surface` | `#ffffff` | `#171717` | cards |
| `--ffd-subtle` | `#faf8fa` | `#1f1d1f` | insight items, task row hover |
| `--ffd-border` | `#ede5ed` | `rgba(255,255,255,.08)` | card borders |
| `--ffd-grid` | `#f0e8f0` | `rgba(255,255,255,.06)` | chart grid, dividers, inner borders |
| `--ffd-border-hover` | `#d4a0d0` | `rgba(212,160,208,.45)` | hover border on items |
| `--ffd-text` | `#1c111b` | `#e4e5e6` | primary text, values |
| `--ffd-text-2` | `#4a2e48` | `#d2c7d1` | labels |
| `--ffd-text-body` | `#6b5069` | `#b5a8b4` | body copy |
| `--ffd-muted` | `#937a92` | `#9a8b99` | subtitles, ticks, task list header |
| `--ffd-faint` | `#bda8bc` | `#7d707c` | SAMPLE chip text |
| `--ffd-lilac` | `#b07aae` | same | chart accent (target line, Idle) |
| `--ffd-amber` | `#f0a500` | same | chart accent (Maintenance in the donut) |
| `--ffd-pos` / `--ffd-pos-bg` | `#059669` / `#ecfdf5` | same / `rgba(5,150,105,.16)` | positive change badge |
| `--ffd-neg` / `--ffd-neg-bg` | `#e53e3e` / `#fff5f5` | same / `rgba(229,62,62,.16)` | negative change badge, overdue task |
| `--ffd-prio-high/medium/low` | `#e53e3e` / `#f6ad55` / `#48bb78` | same | Next Actions row accent |
| `--ffd-sample-border` | `#d4c0d3` | `rgba(255,255,255,.22)` | SAMPLE chip border |

### Typography

| Role | Font | Size / weight | Extra |
|---|---|---|---|
| KPI value | DM Sans | 26px / 700 | letter-spacing −0.5px |
| Card title | DM Sans | 15px / 700 | |
| Insight title | DM Sans | 13px / 600 | |
| Label | Inter | 13px / 500 | |
| Body, subtitle | Inter | 12px / 400 | |
| Sub-label | Inter | 11–11.5px | |
| Section label | DM Mono | 11px / 500 | uppercase, 0.08em |
| Task list header | DM Mono | 10px / 500 | uppercase, 0.06em |
| Axis ticks, numbers | DM Mono | 11–12px / 500 | |
| SAMPLE chip | DM Mono | 9.5px / 500 | uppercase, 0.08em |

### Spacing, radius, shadow

| Token / rule | Value |
|---|---|
| Page padding | 28px 28px 56px (16px sides below 768px) |
| KPI grid gap / tile padding | 14px / 20px 22px (18px 16px when the dashboard is under 1100px wide; 16px under 600px) |
| Panel grid gap / panel padding | 16px / 22px 24px |
| Row spacing | 22px |
| `--ffd-radius-card` | 14px (cards, tiles) |
| `--ffd-radius-md` | 10px (icon tiles, insight items) |
| `--ffd-radius-sm` | 8px (buttons, task rows) |
| `--ffd-radius-xs` | 6px (priority pills); 5px tags; 7px task list header; 4px SAMPLE chip |
| `--ffd-radius-pill` | 99px (badges, dots) |
| `--ffd-shadow-hover` | `0 8px 32px rgba(100,13,95,.10)` and a −2px lift (KPI tiles) |
| `--ffd-shadow-item` | `0 2px 12px rgba(100,13,95,.06)` (insight items on hover) |

At rest, cards have no shadow; they use a 1px `--ffd-border` instead.

### Breakpoints

The design defines none. The ones in use are **container queries on the `.ffd` element**, not window media queries. Browser zoom, display scaling and
the sidebar (open, collapsed to a rail, or a drawer below 1025px) all change how much room the dashboard has, and the
layout follows that room. With the sidebar open, the dashboard's content is about 300px narrower than the window.

| Dashboard content width | Window, sidebar open | KPI tiles | Mileage + Insights | Maintenance / Fleet Status / Next Actions |
|---|---|---|---|---|
| ≥ 1100px | ≥ ~1400px | 5 across (design) | `1fr 340px` (design) | `1fr 300px 340px` (design) |
| 920–1099px | ~1220–1400px | 5 across, tighter tile padding | `1fr 340px` | design columns. The side cards may narrow to 250px / 310px, so the row holds down to 872px. |
| 872–919px | ~1175–1220px | 3 across (3 + 2) | `1fr 340px` | design columns (narrowed) |
| 760–871px | ~1060–1175px | 3 across | `1fr 340px` | chart full width, then 2 across |
| 600–759px | (drawer, ~650–830px window) | 3 across | stacked | chart full width, then 2 across |
| 380–599px | | 2 across | stacked | stacked |
| < 380px | phones | 1 across | stacked | stacked |

Examples: a 1440 or 1366 window shows the design layout. So does a 1280 window with the sidebar open (content about
977px), and a 1024 window, where the sidebar is a drawer (content about 950px).

---

## Component map

**R** means real data. **S** means sample data from `$placeholderData`. The page title, date and Refresh are in the
app shell's top bar.

**SAMPLE marking: one chip per card.** Every card that holds any sample value carries exactly one `.ffd-sample` chip
and one `data-ffd-sample` attribute on the card element (10 cards today). The chip sits at the right end of the
card's top row:
- KPI tiles: just left of the change badge.
- Panels, Next Actions included: the right end of the header. Next Actions' priority pills sit on their own row below
  the header. The design puts them beside the title, but in a 340px card they don't fit.

The chip's tooltip says which parts of that card are samples. A card loses its chip once none of its values are samples.

Admins see these ten cards, in the design's order:

| Component | R/S | Data source |
|---|---|---|
| KPI: Total Vehicles, value | R | `Vehicle::countByStatus()`: `active + inactive` (sold excluded; `vehicles.is_status` is a required enum of active, inactive or sold) |
| KPI: Active Vehicles, value | R | `Vehicle::countByStatus()['active']` |
| KPI: In Maintenance, value | S | `$placeholderData['kpis']['in_maintenance']`. No real source: vehicles have no maintenance status (see the checklist, item 2). |
| KPI: Total Drivers, value | R | `$drivers->count()` (the existing collection, no new query) |
| KPI: Monthly Revenue, value | S | `$placeholderData['kpis']['monthly_revenue']` |
| KPI change badges and sub-labels (all five) | S | `$placeholderData['kpis'][…]['change' / 'positive' / 'sub']` |
| Fleet Mileage vs Target | S | `$placeholderData['mileage']` (Chart.js) |
| Fleet Insights | S | `$placeholderData['insights']` |
| Maintenance Costs | S | `$placeholderData['maintenance_costs']` (Chart.js) |
| Fleet Status (donut) | S | `$placeholderData['fleet_status']` (Chart.js). The design's "148 total vehicles" subtitle is left out on purpose (see Known limitations). |
| Next Actions | S | `$placeholderData['next_actions']` |

`countByStatus()` is called once. Agents and other non-admin roles see a single card linking to `/` instead (see Known
limitations). Super admins never reach this view: `indexNew` returns their own dashboard first.

---

## Removed from /dashboard (2026-10-01)

The client asked for `/dashboard` to show only what the Figma design shows. These components were removed from
`main.blade.php`. Every one of them still exists on `/` (`home_dashboard/dashboard.blade.php`, unchanged), so Phase 7 can
decide what to bring back. The line numbers refer to `dashboard.blade.php`.

| Removed from `/dashboard` | Data it showed | Equivalent on `/` |
|---|---|---|
| Filter row: agent chip, date-range chip, Filter button and its panel (agent, from date, to date) | request `agent` / `date` / `to_date` (it only filtered the rides components below) | header Filter button and offcanvas `#offcanvasExample` (l. 34–100); the agent picker is admin-only |
| KPI "Overdue Tasks" | sample (`kpis.overdue_tasks`) | none. The design defines a sixth KPI but renders only five (`kpis.slice(0, 5)`). Candidate sources stay in the checklist below (item 4). |
| Vehicle Status | `Vehicle::countByStatus()` (active / inactive / sold) | admin "Vehicle Status" card (l. ~121). The counts still feed the Total Vehicles and Active Vehicles KPIs. |
| Vehicle Assignment | `$incompleteOrdersWithDriver`, `$approvedOrders`, `$incompleteOrderWithoutDriver` | admin "Vehicle Assignment" card, linking to driver assignments (l. ~165) |
| Payment | `OrderDetail::vehivcle_details()` (paid / unpaid) | admin "Payment" card (l. ~203) |
| Rides Order Status (line chart) | `$totalOrders` … `$cancelOrders` | admin "Rides Order Status", `#rideOrderChart` (l. ~238) |
| Top Agents | `$topAgents` | admin "Top Agents" (l. ~385) |
| Today Vehicle Assignment (ApexCharts) | `$vehicleBookings` | admin "Today Vehicle Assignment" (l. ~277) |
| Drivers list | `$drivers` | admin "Drivers" (l. ~424). The count still feeds the Total Drivers KPI. |
| Vehicle Details table | `$vehicle_assignments` | admin "Vehicle Details" (l. ~314) |
| Upcoming Busy Rides table | `$orderDetailsWithVehicle` | admin "Upcoming Busy Rides (Next 7 Days)" (l. ~501) |
| Agent view: Rides Order Status and Customers | `$agenttotalOrders` … `$agentcancelOrders`, `$agentallcustomer` | agent branch: "Rides Order Status" (l. ~260) and "Customers" (l. ~461) |
| "Rides & assignments" section label; page-level "design placeholder data until Phase 7" note | none | n/a. The note is replaced by one SAMPLE chip per card. |

`DashboardController@indexNew` still passes all of this data to the view (see the backlog below), so restoring a
component is a markup-only change.

---

## Known limitations and backlog

- **No agent version.** `/dashboard` is an admin fleet view, and the design has nothing for agents. Agents and
  other non-admin roles get one card that links to `/`, where their rides and customers are. If the client wants an
  agent version of this page, it needs designing first.
- **Fleet Status has no subtitle on purpose.** The design shows "148 total vehicles" under the donut. Next to the
  real Total Vehicles KPI, a second, different total would look like a bug, so it's left out. The card's SAMPLE
  chip says the donut's figures are design placeholders unrelated to the KPIs above. That also covers the legend's
  "Active" figure, which differs from the real Active Vehicles KPI. Restore the subtitle when the donut gets real
  data (checklist item 9).
- **Backlog: `DashboardController@indexNew` computes data the page no longer shows.** It is still a copy of `index`
  and passes every variable the removed components used: rides counts, top agents, bookings, assignments, upcoming
  rides, and the agent ledger. It's kept on purpose: some components may come back (see the table above), and the
  unused queries cost little. Trim it once Phase 7 settles what `/dashboard` shows.

---

## App shell: sidebar and top bar

Every page that extends `layouts.app` gets the design's sidebar and top bar.

| Piece | File |
|---|---|
| Sidebar | `resources/views/layouts/nav.blade.php` |
| Top bar, user menu, notifications | `resources/views/layouts/header.blade.php` |
| Styles | `public/assets/css/app-shell.css` (linked from `nav.blade.php`, so it loads before the sidebar paints) |
| Scripts | `public/assets/js/app-shell.js` (linked from `header.blade.php`, deferred) |

**Scoping rule.** Classes use the `ffs-` prefix. Tokens are `--ffs-*` custom properties on `:root`. Their dark values
apply under `html.dark-theme`, and the sidebar's dark values also apply under `html.semi-dark`. The Synadmin hooks keep
their classes and ids (`.sidebar-wrapper`, `.topbar`, `#menu` / `.metismenu`, `.toggle-icon`, `.mobile-toggle-menu`), so
`app.js` still drives collapse, hover-expand, the mobile menu, active-link marking and metisMenu. Menu rules go through
`#menu` to outrank the metisMenu rules in `app.css` and the theme files, and use `!important` only where those rules do.

**Unchanged:**
- the role menu pipeline in `nav.blade.php` (permissions, then items, then `layouts.partials.menu`) and the position sort;
- `layouts/app.blade.php` (scripts and FCM token registration);
- the geometry: 230px sidebar, a 70px rail from 1025px, an off-canvas drawer below that, and a 60px top bar.

**Behaviour:**
- **Title:** `@section('title')`, else the active breadcrumb in `$breadcrumbs`, else the dashboard name by route, else
  "FleetFreak". A generic active crumb (Create, Edit, Show) gets its parent crumb in front, as in "Ride · Create".
  **Date:** `now()->format('l, j F Y')`.
- **Refresh:** only on the four dashboard routes (`dashboard`, `vehicle_dashboard`, `driver_dashboard`, `financial_dashboard`).
- **Bell badge:** the number of unread notifications the offcanvas lists (`admin_unread_notifications()` or
  `agent_unread_notifications()`). It is hidden at 0.
- **Commented out (2026-10-01, on request), still in `header.blade.php`:**
  - the admin tiles Unapproved Agents (with its `#notificationCount` badge) and Vehicle Locations;
  - the Create Ride tile;
  - the SAMPLE chip beside the search box.

  The pages stay reachable from the sidebar menu. The count query for the agents badge
  (`Partner::unapprovedAgentsCount()`) is commented out inside the same block as its tile, so it no longer runs on every
  admin page. Uncommenting that one block restores both.
- **Themes:** light, dark and semi-dark are supported. The header and sidebar colour options (`headercolor1-8`,
  `sidebarcolor1-8`) are retired and the design's surfaces win. Their classes, the `/update-header` and `/update-sidebar`
  endpoints and the user columns are untouched.
- **`header.css`** is still loaded, because pages use its global rules (`.list-group-item`, `.dropdown-item:hover`, `.navbar-expand-lg`).

### Tokens

| Token | Light | Dark | Used for |
|---|---|---|---|
| `--ffs-brand` | `#640D5F` | same | logo band, search box, Refresh, avatar initial |
| `--ffs-brand-hover` | `#9B3496` | same | Refresh hover |
| `--ffs-brand-text` | `#640D5F` | `#d4a0d0` | icon tiles, active tab, focus outlines |
| `--ffs-brand-pale` | `#f5eef5` | `rgba(155,52,150,.18)` | icon tiles, user chip, tab track, notification icon |
| `--ffs-brand-paler` | `#faf5fa` | `rgba(155,52,150,.10)` | user-menu item hover, tab counts |
| `--ffs-tile-hover` | `#ecdfec` | `rgba(155,52,150,.30)` | icon tile hover |
| `--ffs-surface` | `#ffffff` | `#171717` | top bar, user menu, offcanvas |
| `--ffs-border` | `#ede5ed` | `rgba(255,255,255,.08)` | borders |
| `--ffs-text` | `#1c111b` | `#e4e5e6` | title, user name |
| `--ffs-text-body` | `#6b5069` | `#b5a8b4` | menu items, notification text |
| `--ffs-muted` | `#937a92` | `#9a8b99` | date, company, times |
| `--ffs-faint` / `--ffs-sample-border` | `#bda8bc` / `#d4c0d3` | `#7d707c` / `rgba(255,255,255,.22)` | search SAMPLE tag (its markup is commented out for now) |
| `--ffs-badge` | `#e53e3e` | same | count badges |

The sidebar has its own set. Dark and semi-dark share the dark values.

| Token | Light | Dark and semi-dark |
|---|---|---|
| `--ffs-nav-bg` | `#ffffff` | `#171717` |
| `--ffs-nav-border` | `#ede5ed` | `rgba(255,255,255,.08)` |
| `--ffs-nav-text` | `#6b5069` | `#b5a8b4` |
| `--ffs-nav-strong` (hover, active, dot) | `#640D5F` | `#e9c6e6` |
| `--ffs-nav-hover-bg` | `#faf5fa` | `rgba(255,255,255,.05)` |
| `--ffs-nav-active-bg` (active pill) | `#f5eef5` | `rgba(155,52,150,.24)` |
| `--ffs-nav-label` (NAVIGATION) | `#bda8bc` | `#7d707c` |
| `--ffs-nav-foot-bg` (Collapse button) | `#faf5fa` | `rgba(255,255,255,.04)` |
| `--ffs-nav-scroll` (thin scrollbar) | `#e8d5e7` | `rgba(255,255,255,.16)` |

### Typography

| Role | Font | Size / weight | Extra |
|---|---|---|---|
| Page title | DM Sans | 20px / 700 | letter-spacing −0.4px; 17px below 768px |
| Date | Inter | 12px / 400 | |
| NAVIGATION label | DM Mono | 9.5px / 500 | uppercase, 0.1em |
| Menu item | Inter | 13.5px / 400 | children 13px; 600 when active |
| User name / company | DM Sans / Inter | 13px / 600, 11px / 400 | |
| Search placeholder | Inter | 13px / 400 | |
| Collapse button | Inter | 12px / 600 | |

### Breakpoints

| Viewport | Sidebar | Top bar |
|---|---|---|
| ≥ 1200px | 230px, collapsible to a 70px rail (hover expands it) | title, search, actions |
| 1025–1199px | same | search hidden |
| 768–1024px | off-canvas drawer, opened by the menu button | title, actions |
| < 768px | drawer | user chip shows only the avatar; Refresh shows only its icon |
| < 576px | drawer | date hidden |

---

## Phase 7 checklist: placeholder components

Each item is done when the value comes from a real, organization-scoped source and its key has been removed from
`$placeholderData`. A card keeps its one SAMPLE chip (and its `data-ffd-sample` attribute) until none of its values
are samples. Then remove both, and update the chip's tooltip text in the meantime. The last item on the list removes
the `$placeholderData` block.

Numbers are kept stable, because other documents refer to them.

| # | Component | `$placeholderData` key | Real data needed | Candidate source today |
|---|---|---|---|---|
| 1 | KPI Total Vehicles | — | **Done (2026-10-01):** `countByStatus()` active + inactive | — |
| 2 | KPI In Maintenance | `kpis.in_maintenance.value` | Vehicles currently in maintenance | **No vehicle status for it.** `VehicleDashboardController`'s `maintainence_vehicles_count` counts *completed maintenance invoices in a date range*, which is a different metric. Needs a definition first (open maintenance jobs? a vehicle status?). |
| 3 | KPI Monthly Revenue | `kpis.monthly_revenue.value` | Revenue for the current month, in the organization's currency | `FinancialDashboardController`: `total_revenue` / `revenuechartData` |
| 4 | KPI Overdue Tasks | — | **Dropped (2026-10-01):** not on the design page (the design renders five KPIs). If it comes back: a count of overdue items. | inspections overdue and route-permit expiry (`VehicleDashboardController`), license and CNIC expiry (`DriverDashboardController`) |
| 5 | KPI change badges (all five) | `kpis.*.change`, `kpis.*.positive` | The same KPI for the previous period, and the delta | new: a period-over-period comparison |
| 6 | KPI sub-labels (all five) | `kpis.*.sub` | Utilization %, share of fleet, "vs last month" wording | derived from 1, 2 and 5, plus active vehicles |
| 7 | Fleet Mileage vs Target | `mileage` | Monthly km per organization, and a monthly target | `order_lines.ride_start_mileage` / `ride_end_mileage` (strings, per ride). **No target source exists yet.** |
| 8 | Maintenance Costs | `maintenance_costs` | Monthly maintenance cost, split into scheduled and unscheduled | `invoices` where `document_type = 'maintenance'`, plus their lines. **No scheduled/unscheduled flag exists yet.** |
| 9 | Fleet Status donut | `fleet_status` | Vehicle counts for Active, Idle and Maintenance | needs an agreed definition of "Idle"; `VehicleDashboardController`: `vehicle_busy` / `vehicle_rides_busy_count`, and maintenance count (2). When real, restore the design's "N total vehicles" subtitle. |
| 10 | Fleet Insights | `insights` | Generated recommendations (title, body, tag) | **No insights engine exists.** "View All Insights" needs a target page. |
| 11 | Next Actions | `next_actions` | Tasks with priority, category, title and due date; Gen. Invoice and Schedule actions | inspections due, expiries, unpaid invoices. **No task model exists.** The buttons need target routes. |
| 12 | Top-bar search (app shell) | none: markup in `layouts/header.blade.php` | Organization-scoped search across vehicles, drivers and tasks, with a results view | `GET /customer/search`, `/agent/search`, `/driver/search` (partner-name lookups for select2 pickers). **No vehicle, task or cross-entity search exists.** Done when the input loses `readonly`. Its SAMPLE tag is already commented out (2026-10-01, on request), so the box is still a read-only placeholder, just no longer labelled. |

The inert sample controls are "View All Insights", "Gen. Invoice" and "Schedule". Each has `aria-disabled="true"` and a
"Sample — available in Phase 7" tooltip. The Next Actions priority filter already works, on the client side.
