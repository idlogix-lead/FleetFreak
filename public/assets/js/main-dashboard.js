/*
 * Main dashboard (/dashboard) - resources/views/home_dashboard/main.blade.php
 *
 * Draws the charts and wires the small interactions. Chart data comes from the
 * JSON block #ffd-data: "real" is the controller data as before, "sample" is
 * $placeholderData. Chart.js is loaded by the layout; ApexCharts by the view.
 */
(function () {
    'use strict';

    var root = document.querySelector('.ffd');
    if (!root) {
        return;
    }

    var RIDE_LABELS = ['Total Rides', 'Pending Rides', 'Incomplete Rides', 'Completed Rides', 'Approved Rides', 'Unapproved Rides', 'Cancelled Rides'];
    var RIDE_COLORS = ['#4CAF50', '#FF9800', '#F44336', '#2196F3', '#9C27B0', '#FFC107', '#795548'];

    function readData() {
        var el = document.getElementById('ffd-data');
        try {
            return JSON.parse(el ? el.textContent : '{}') || {};
        } catch (e) {
            return {};
        }
    }

    function readTokens() {
        var style = getComputedStyle(root);
        var v = function (name) {
            return style.getPropertyValue(name).trim();
        };
        return {
            brand: v('--ffd-brand'),
            brandText: v('--ffd-brand-text'),
            brandMuted: v('--ffd-brand-muted'),
            lilac: v('--ffd-lilac'),
            amber: v('--ffd-amber'),
            surface: v('--ffd-surface'),
            border: v('--ffd-border'),
            grid: v('--ffd-grid'),
            text: v('--ffd-text'),
            text2: v('--ffd-text-2'),
            muted: v('--ffd-muted'),
            fontBody: v('--ffd-font-body'),
            fontMono: v('--ffd-font-mono'),
            dark: document.documentElement.classList.contains('dark-theme')
        };
    }

    function hexToRgba(hex, alpha) {
        var h = hex.replace('#', '');
        if (h.length === 3) {
            h = h.replace(/(.)/g, '$1$1');
        }
        var n = parseInt(h, 16);
        return 'rgba(' + ((n >> 16) & 255) + ',' + ((n >> 8) & 255) + ',' + (n & 255) + ',' + alpha + ')';
    }

    // Vertical fade under a line, as in the design's area charts.
    function areaGradient(hex, alpha) {
        return function (context) {
            var chart = context.chart;
            var area = chart.chartArea;
            if (!area) {
                return hexToRgba(hex, alpha);
            }
            var gradient = chart.ctx.createLinearGradient(0, area.top, 0, area.bottom);
            gradient.addColorStop(0.05, hexToRgba(hex, alpha));
            gradient.addColorStop(0.95, hexToRgba(hex, 0));
            return gradient;
        };
    }

    // Dashed grid, mono ticks, no axis line. Sets both Chart.js v4 and v3 keys.
    function axis(t, extra) {
        var base = {
            border: { display: false, dash: [3, 3] },
            grid: { color: t.grid, borderDash: [3, 3], drawBorder: false, drawTicks: false },
            ticks: { color: t.muted, padding: 8, font: { family: t.fontMono, size: 11 } }
        };
        return Object.assign(base, extra || {});
    }

    function tooltip(t) {
        return {
            backgroundColor: t.surface,
            borderColor: t.border,
            borderWidth: 1,
            titleColor: t.text,
            bodyColor: t.text2,
            padding: 10,
            cornerRadius: 10,
            boxWidth: 7,
            boxHeight: 7,
            usePointStyle: true,
            titleFont: { family: t.fontBody, size: 12, weight: '600' },
            bodyFont: { family: t.fontBody, size: 12 }
        };
    }

    // Circle legend; each label takes its series colour, as in the design.
    function legend(t, labelsFn) {
        return {
            position: 'bottom',
            onClick: function () {},
            labels: {
                usePointStyle: true,
                pointStyle: 'circle',
                boxWidth: 7,
                boxHeight: 7,
                pointStyleWidth: 7,
                padding: 16,
                font: { family: t.fontBody, size: 12 },
                generateLabels: labelsFn
            }
        };
    }

    function datasetLabels(chart) {
        return chart.data.datasets.map(function (ds, i) {
            var color = ds.legendColor || ds.borderColor || ds.backgroundColor;
            return {
                text: ds.label,
                fillStyle: color,
                strokeStyle: color,
                fontColor: color,
                lineWidth: 0,
                pointStyle: 'circle',
                datasetIndex: i
            };
        });
    }

    function baseOptions(t, extra) {
        return Object.assign({
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false }
        }, extra);
    }

    function canvas(id) {
        return document.getElementById(id);
    }

    /* ---------------------------------------------------------- sample charts */

    function drawMileage(t, m) {
        var el = canvas('ffd-chart-mileage');
        if (!el || !m) {
            return;
        }
        new Chart(el, {
            type: 'line',
            data: {
                labels: m.labels,
                datasets: [{
                    label: 'Actual km',
                    data: m.actual,
                    borderColor: t.brand,
                    legendColor: t.brandText,
                    borderWidth: 2,
                    backgroundColor: areaGradient(t.brand, 0.18),
                    fill: 'origin',
                    cubicInterpolationMode: 'monotone',
                    pointRadius: 0,
                    pointHoverRadius: 4
                }, {
                    label: 'Target km',
                    data: m.target,
                    borderColor: t.lilac,
                    borderWidth: 1.5,
                    borderDash: [4, 3],
                    backgroundColor: areaGradient(t.lilac, 0.10),
                    fill: 'origin',
                    cubicInterpolationMode: 'monotone',
                    pointRadius: 0,
                    pointHoverRadius: 4
                }]
            },
            options: baseOptions(t, {
                scales: {
                    x: axis(t),
                    y: axis(t, {
                        beginAtZero: true,
                        ticks: {
                            color: t.muted,
                            padding: 8,
                            font: { family: t.fontMono, size: 11 },
                            callback: function (v) { return (v / 1000).toFixed(0) + 'k'; }
                        }
                    })
                },
                plugins: { legend: legend(t, datasetLabels), tooltip: tooltip(t) }
            })
        });
    }

    function drawMaintenance(t, mc) {
        var el = canvas('ffd-chart-maintenance');
        if (!el || !mc) {
            return;
        }
        var bar = { barThickness: 14, borderRadius: { topLeft: 4, topRight: 4 }, borderSkipped: 'bottom' };
        new Chart(el, {
            type: 'bar',
            data: {
                labels: mc.labels,
                datasets: [
                    Object.assign({ label: 'Scheduled', data: mc.scheduled, backgroundColor: t.brand, legendColor: t.brandText }, bar),
                    Object.assign({ label: 'Unscheduled', data: mc.unscheduled, backgroundColor: t.brandMuted, legendColor: t.muted }, bar)
                ]
            },
            options: baseOptions(t, {
                scales: {
                    x: axis(t, { grid: { display: false, drawBorder: false } }),
                    y: axis(t, {
                        beginAtZero: true,
                        ticks: {
                            color: t.muted,
                            padding: 8,
                            font: { family: t.fontMono, size: 11 },
                            callback: function (v) { return '$' + (v / 1000).toFixed(1) + 'k'; }
                        }
                    })
                },
                plugins: { legend: legend(t, datasetLabels), tooltip: tooltip(t) }
            })
        });
    }

    function drawFleetStatus(t, rows) {
        var el = canvas('ffd-chart-fleet-status');
        if (!el || !rows) {
            return;
        }
        var tones = { brand: t.brand, lilac: t.lilac, amber: t.amber };
        new Chart(el, {
            type: 'doughnut',
            data: {
                labels: rows.map(function (r) { return r.name; }),
                datasets: [{
                    data: rows.map(function (r) { return r.value; }),
                    backgroundColor: rows.map(function (r) { return tones[r.tone] || t.brand; }),
                    borderWidth: 0,
                    spacing: 3,
                    hoverOffset: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '66%',
                plugins: { legend: { display: false }, tooltip: tooltip(t) }
            }
        });
    }

    /* ------------------------------------------------------------ real charts */

    // Same labels, values and status colours as the old dashboard's chart.
    function drawRides(t, values) {
        var el = canvas('ffd-chart-rides');
        if (!el || !values) {
            return;
        }
        new Chart(el, {
            type: 'line',
            data: {
                labels: RIDE_LABELS,
                datasets: [{
                    label: 'Ride Orders',
                    data: values,
                    borderColor: t.brand,
                    borderWidth: 2,
                    backgroundColor: areaGradient(t.brand, 0.12),
                    fill: 'origin',
                    cubicInterpolationMode: 'monotone',
                    pointBackgroundColor: RIDE_COLORS,
                    pointBorderColor: t.surface,
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: baseOptions(t, {
                scales: {
                    x: axis(t, {
                        ticks: {
                            color: t.muted,
                            padding: 8,
                            autoSkip: false,
                            maxRotation: 0,
                            font: { family: t.fontMono, size: 11 },
                            // "Pending Rides" -> two lines, so seven labels fit without rotating.
                            callback: function (value) { return RIDE_LABELS[value].split(' '); }
                        }
                    }),
                    y: axis(t, {
                        beginAtZero: true,
                        ticks: { color: t.muted, padding: 8, precision: 0, font: { family: t.fontMono, size: 11 } }
                    })
                },
                plugins: {
                    legend: legend(t, function () {
                        return RIDE_LABELS.map(function (label, i) {
                            return {
                                text: label,
                                fillStyle: RIDE_COLORS[i],
                                strokeStyle: RIDE_COLORS[i],
                                fontColor: t.text2,
                                lineWidth: 0,
                                pointStyle: 'circle'
                            };
                        });
                    }),
                    tooltip: tooltip(t)
                }
            })
        });
    }

    // Same series, categories and y axis as the old ApexCharts chart; restyled only.
    function drawVehicleBookings(t, bookings) {
        var el = document.getElementById('ffd-chart-vehicle-bookings');
        if (!el || typeof ApexCharts === 'undefined') {
            return;
        }
        bookings = bookings || [];
        var labelStyle = { fontFamily: t.fontMono, fontSize: '11px', colors: t.muted };
        var titleStyle = { fontFamily: t.fontBody, fontSize: '12px', fontWeight: 500, color: t.muted };
        new ApexCharts(el, {
            chart: {
                type: 'bar',
                height: 300,
                toolbar: { show: false },
                fontFamily: t.fontBody,
                foreColor: t.muted,
                background: 'transparent'
            },
            series: [{ name: 'Estimated Time (in hours)', data: bookings }],
            colors: [t.brand],
            plotOptions: { bar: { horizontal: false, columnWidth: '30%', endingShape: 'rounded' } },
            dataLabels: { enabled: false },
            grid: {
                borderColor: t.grid,
                strokeDashArray: 3,
                xaxis: { lines: { show: false } }
            },
            xaxis: {
                title: { text: 'Vehicles', style: titleStyle },
                categories: bookings.map(function (item) { return item.x + ' (' + item.vehiclesCount + ')'; }),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: labelStyle }
            },
            yaxis: {
                title: { text: 'Time (in hours)', style: titleStyle },
                min: 0,
                max: 12,
                tickAmount: 11,
                labels: {
                    style: labelStyle,
                    formatter: function (val) { return val.toFixed(0); }
                }
            },
            tooltip: { theme: t.dark ? 'dark' : 'light' }
        }).render();
    }

    function drawCharts() {
        var data = readData();
        var real = data.real || {};
        var sample = data.sample || {};
        var t = readTokens();

        if (typeof Chart !== 'undefined') {
            drawMileage(t, sample.mileage);
            drawMaintenance(t, sample.maintenanceCosts);
            drawFleetStatus(t, sample.fleetStatus);
            drawRides(t, real.rides);
        }
        drawVehicleBookings(t, real.vehicleBookings);
    }

    /* ----------------------------------------------------------- interactions */

    function wireTaskFilter() {
        var pills = root.querySelectorAll('[data-ffd-filter]');
        var rows = root.querySelectorAll('.ffd-task[data-priority]');
        Array.prototype.forEach.call(pills, function (pill) {
            pill.addEventListener('click', function () {
                var filter = pill.getAttribute('data-ffd-filter');
                Array.prototype.forEach.call(pills, function (p) {
                    var active = p === pill;
                    p.classList.toggle('is-active', active);
                    p.setAttribute('aria-pressed', active ? 'true' : 'false');
                });
                Array.prototype.forEach.call(rows, function (row) {
                    row.hidden = filter !== 'all' && row.getAttribute('data-priority') !== filter;
                });
            });
        });
    }

    function wireActions() {
        var refresh = root.querySelector('[data-ffd-action="refresh"]');
        if (refresh) {
            refresh.addEventListener('click', function () {
                window.location.reload();
            });
        }

        // Same behaviour as the old dashboard's Reset: clear the fields, hide the agent chip.
        var reset = document.getElementById('ffd-filter-reset');
        if (reset) {
            reset.addEventListener('click', function () {
                ['ffd-agent', 'ffd-date', 'ffd-to-date'].forEach(function (id) {
                    var field = document.getElementById(id);
                    if (field) {
                        field.value = '';
                    }
                });
                var chip = document.getElementById('ffd-agent-chip');
                if (chip) {
                    chip.hidden = true;
                }
            });
        }
    }

    wireTaskFilter();
    wireActions();

    // Draw after the web fonts load so canvas text uses DM Mono / Inter.
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(drawCharts, drawCharts);
    } else {
        drawCharts();
    }
})();
