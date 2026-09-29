# Main dashboard and app shell: design tokens and Phase 7 checklist

The redesigned main dashboard at `/dashboard` (`DashboardController@indexNew`) follows the Figma Make file
"FleetFreak Final Design – HomePage". `/` (`DashboardController@index`, `home_dashboard/dashboard.blade.php`)
is unchanged and is the fallback. The same design's sidebar and top bar are applied app-wide; see
[App shell](#app-shell-sidebar-and-top-bar).

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
- ApexCharts 3.19 is loaded locally by the view. That version has no bar `borderRadius`, so rounded bars use `endingShape: 'rounded'`.
- Fonts come from Google Fonts: DM Sans, Inter and DM Mono.

---

## Tokens

The tokens are CSS custom properties on `.ffd`. The design brand `#63205f` is mapped to the repo's existing plum `#640D5F`,
with `#9B3496` as its hover colour. The dark values apply under `html.dark-theme`. The design has no dark variant, so they
follow the surfaces in Synadmin's `dark-theme.css`.

### Colour

| Token | Light | Dark | Used for |
|---|---|---|---|
| `--ffd-brand` | `#640D5F` | same | primary buttons, active pill, chart series 1, avatars |
| `--ffd-brand-hover` | `#9B3496` | same | primary hover, focus ring |
| `--ffd-brand-text` | `#640D5F` | `#d4a0d0` | brand-coloured text on pale backgrounds |
| `--ffd-brand-pale` | `#f5eef5` | `rgba(155,52,150,.18)` | icon tiles, pills, tags, table header, soft buttons |
| `--ffd-brand-muted` | `#e8d5e7` | `rgba(155,52,150,.40)` | chart series 2 (bars), soft button hover |
| `--ffd-bg` | `#f8f6f8` | `#1e1e1e` | page background |
| `--ffd-surface` | `#ffffff` | `#171717` | cards |
| `--ffd-subtle` | `#faf8fa` | `#1f1d1f` | list items, stat blocks, row hover |
| `--ffd-border` | `#ede5ed` | `rgba(255,255,255,.08)` | card borders |
| `--ffd-grid` | `#f0e8f0` | `rgba(255,255,255,.06)` | chart grid, dividers, inner borders |
| `--ffd-border-hover` | `#d4a0d0` | `rgba(212,160,208,.45)` | hover border on items |
| `--ffd-text` | `#1c111b` | `#e4e5e6` | primary text, values |
| `--ffd-text-2` | `#4a2e48` | `#d2c7d1` | labels |
| `--ffd-text-body` | `#6b5069` | `#b5a8b4` | body copy |
| `--ffd-muted` | `#937a92` | `#9a8b99` | subtitles, ticks, table headers |
| `--ffd-faint` | `#bda8bc` | `#7d707c` | SAMPLE tag, notes |
| `--ffd-lilac` | `#b07aae` | same | chart accent (target line, Idle) |
| `--ffd-amber` | `#f0a500` | same | chart accent, warning dot |
| `--ffd-pos` / `--ffd-pos-bg` | `#059669` / `#ecfdf5` | same / `rgba(5,150,105,.16)` | positive badge, success |
| `--ffd-neg` / `--ffd-neg-bg` | `#e53e3e` / `#fff5f5` | same / `rgba(229,62,62,.16)` | negative badge, danger, overdue |
| `--ffd-warn` / `--ffd-warn-bg` | `#b7791f` / `#fffbeb` | same / `rgba(183,121,31,.20)` | warning status pill |
| `--ffd-prio-high/medium/low` | `#e53e3e` / `#f6ad55` / `#48bb78` | same | Next Actions row accent |
| `--ffd-sample-border` | `#d4c0d3` | `rgba(255,255,255,.22)` | SAMPLE tag border, list scrollbar |

The Rides Order Status chart keeps its existing status point colours: `#4CAF50 #FF9800 #F44336 #2196F3 #9C27B0 #FFC107 #795548`.

### Typography

| Role | Font | Size / weight | Extra |
|---|---|---|---|
| KPI and stat value | DM Sans | 26px / 700 | letter-spacing −0.5px |
| Card title | DM Sans | 15px / 700 | |
| Item title (insights, lists) | DM Sans | 13px / 600 | |
| Label | Inter | 13px / 500 | |
| Body, subtitle | Inter | 12px / 400 | |
| Sub-label, list sub | Inter | 11–11.5px | |
| Section label | DM Mono | 11px / 500 | uppercase, 0.08em |
| Table header | DM Mono | 10px / 500 | uppercase, 0.06em |
| Axis ticks, numbers | DM Mono | 11–13px / 500 | |
| SAMPLE tag | DM Mono | 9.5px / 500 | uppercase, 0.08em |

### Spacing, radius, shadow

| Token / rule | Value |
|---|---|
| Page padding | 28px 28px 56px (16px sides below 768px) |
| KPI grid gap / tile padding | 14px / 20px 22px |
| Panel grid gap / panel padding | 16px / 22px 24px |
| Row spacing | 22px |
| `--ffd-radius-card` | 14px (cards, tiles) |
| `--ffd-radius-md` | 10px (icon tiles, items, chips) |
| `--ffd-radius-sm` | 8px (buttons, task rows) |
| `--ffd-radius-xs` | 6px (filter pills); 5px tags; 7px table header |
| `--ffd-radius-pill` | 99px (badges, dots, avatars) |
| `--ffd-shadow-hover` | `0 8px 32px rgba(100,13,95,.10)` and a −2px lift (KPI tiles) |
| `--ffd-shadow-item` | `0 2px 12px rgba(100,13,95,.06)` (items, stat blocks) |

At rest, cards have no shadow; they use a 1px `--ffd-border` instead.

### Breakpoints

The design defines none. These are the ones in use:

| Viewport | KPI tiles | Main + side rows | Maintenance / Fleet Status / Next Actions | Status panels |
|---|---|---|---|---|
| ≥ 1400px | 6 across | `1fr 340px` | `1fr 300px 340px` | 3 across |
| 1200–1399px | 3 across | `1fr 340px` | chart full width, then 2 across | 3 across |
| 768–1199px | 3 across | stacked | chart full width, then 2 across | 3 across |
| < 768px | 2 across | stacked | stacked | stacked |
| < 480px | 1 across | stacked | stacked | stacked |

---

## Component map

**R** means the component shows real data, wired exactly as on the old dashboard. **S** means it shows sample data from `$placeholderData` and is tagged SAMPLE.
Agents and other non-admin roles see only the filter row, Rides Order Status and Customers, with no sample components.
The page title, date and Refresh are in the app shell's top bar.

| Component | R/S | Data source |
|---|---|---|
| Filter chips, Filter panel | R | `$agentName`, request `agent` / `date` / `to_date` |
| KPI: Total Vehicles | S | `$placeholderData['kpis']['total_vehicles']` |
| KPI: Active Vehicles, value | R | `Vehicle::countByStatus()['active']` |
| KPI: Active Vehicles, badge and sub-label | S | `$placeholderData['kpis']['active_vehicles']` |
| KPI: In Maintenance | S | `$placeholderData['kpis']['in_maintenance']` |
| KPI: Total Drivers, value | R | `$drivers->count()` (the existing collection, no new query) |
| KPI: Total Drivers, badge and sub-label | S | `$placeholderData['kpis']['total_drivers']` |
| KPI: Monthly Revenue | S | `$placeholderData['kpis']['monthly_revenue']` |
| KPI: Overdue Tasks | S | `$placeholderData['kpis']['overdue_tasks']` |
| Vehicle Status | R | `Vehicle::countByStatus()` |
| Vehicle Assignment | R | `$incompleteOrdersWithDriver`, `$approvedOrders`, `$incompleteOrderWithoutDriver` |
| Payment | R | `OrderDetail::vehivcle_details()` |
| Fleet Mileage vs Target | S | `$placeholderData['mileage']` (Chart.js) |
| Fleet Insights | S | `$placeholderData['insights']` |
| Maintenance Costs | S | `$placeholderData['maintenance_costs']` (Chart.js) |
| Fleet Status (donut) | S | `$placeholderData['fleet_status']` (Chart.js) |
| Next Actions | S | `$placeholderData['next_actions']` |
| Rides Order Status | R | `$totalOrders` … `$cancelOrders` (admin) or `$agent*Orders` (agent) (Chart.js) |
| Top Agents | R | `$topAgents` |
| Today Vehicle Assignment | R | `$vehicleBookings` (ApexCharts) |
| Drivers | R | `$drivers` |
| Vehicle Details | R | `$vehicle_assignments` |
| Upcoming Busy Rides | R | `$orderDetailsWithVehicle` |
| Customers (agent) | R | `$agentallcustomer` |

`countByStatus()` and `vehivcle_details()` are called once each; the old view called them three times and twice.

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
  `agent_unread_notifications()`). **Unapproved agents badge:** `Partner::unapprovedAgentsCount()`. Both badges are hidden at 0.
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
| `--ffs-faint` / `--ffs-sample-border` | `#bda8bc` / `#d4c0d3` | `#7d707c` / `rgba(255,255,255,.22)` | SAMPLE tag |
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

Each item is done when the value comes from a real, organization-scoped source, its key has been removed from
`$placeholderData`, and its SAMPLE tag (and `data-ffd-sample` attribute) has been removed from the markup. The last item on
the list removes the `$placeholderData` block and the "design placeholder data" note.

| # | Component | `$placeholderData` key | Real data needed | Candidate source today |
|---|---|---|---|---|
| 1 | KPI Total Vehicles | `kpis.total_vehicles.value` | Count of the organization's vehicles | `Vehicle` (the sum of `countByStatus()`, or a scoped count) |
| 2 | KPI In Maintenance | `kpis.in_maintenance.value` | Vehicles currently in maintenance | `VehicleDashboardController`: `maintainence_vehicles_count` |
| 3 | KPI Monthly Revenue | `kpis.monthly_revenue.value` | Revenue for the current month, in the organization's currency | `FinancialDashboardController`: `total_revenue` / `revenuechartData` |
| 4 | KPI Overdue Tasks | `kpis.overdue_tasks.value` | Count of overdue items | inspections overdue and route-permit expiry (`VehicleDashboardController`), license and CNIC expiry (`DriverDashboardController`) |
| 5 | KPI change badges (all six) | `kpis.*.change`, `kpis.*.positive` | The same KPI for the previous period, and the delta | new: a period-over-period comparison |
| 6 | KPI sub-labels (all six) | `kpis.*.sub` | Utilization %, share of fleet, "vs last month" wording | derived from 1, 2 and 5, plus active vehicles |
| 7 | Fleet Mileage vs Target | `mileage` | Monthly km per organization, and a monthly target | `order_lines.ride_start_mileage` / `ride_end_mileage` (strings, per ride). **No target source exists yet.** |
| 8 | Maintenance Costs | `maintenance_costs` | Monthly maintenance cost, split into scheduled and unscheduled | `invoices` where `document_type = 'maintenance'`, plus their lines. **No scheduled/unscheduled flag exists yet.** |
| 9 | Fleet Status donut | `fleet_status` | Vehicle counts for Active, Idle and Maintenance | needs an agreed definition of "Idle"; `VehicleDashboardController`: `vehicle_busy` / `vehicle_rides_busy_count`, and maintenance count (2) |
| 10 | Fleet Insights | `insights` | Generated recommendations (title, body, tag) | **No insights engine exists.** "View All Insights" needs a target page. |
| 11 | Next Actions | `next_actions` | Tasks with priority, category, title and due date; Gen. Invoice and Schedule actions | inspections due, expiries, unpaid invoices. **No task model exists.** The buttons need target routes. |
| 12 | Top-bar search (app shell) | none: markup in `layouts/header.blade.php` | Organization-scoped search across vehicles, drivers and tasks, with a results view | `GET /customer/search`, `/agent/search`, `/driver/search` (partner-name lookups for select2 pickers). **No vehicle, task or cross-entity search exists.** Done when the input loses `readonly` and the SAMPLE tag is removed. |

The inert sample controls are "View All Insights", "Gen. Invoice" and "Schedule". Each has `aria-disabled="true"` and a
"Sample — available in Phase 7" tooltip. The Next Actions priority filter already works, on the client side.
