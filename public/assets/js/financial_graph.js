$(function () {
	"use strict";
	
	// chart1
	var options = {
		series: [{
			name: 'Revenue',
			data: revenuechartData.map(item => item.revenue)
		}],
		chart: {
			foreColor: '#9a9797',
			type: 'area',
			height: 380,
			zoom: {
				enabled: false
			},
			toolbar: {
				show: false
			},
			dropShadow: {
				enabled: false,
				top: 3,
				left: 14,
				blur: 4,
				opacity: 0.10,
			}
		},
		stroke: {
			width: 4,
			curve: 'smooth'
		},
		xaxis: {
			categories: revenuechartData.map(item => item.month),
		},
		dataLabels: {
			enabled: false
		},
		fill: {
			type: 'gradient',
			gradient: {
				shade: 'light',
				gradientToColors: ['#8833ff'],
				shadeIntensity: 1,
				type: 'vertical',
				opacityFrom: 0.8,
				opacityTo: 0.3,
				//stops: [0, 100, 100, 100]
			},
		},
		colors: ["#8833ff"],
		yaxis: {
		  labels: {
			formatter: function (value) {
			  return value + "$";
			}
		  },
		},
		markers: {
			size: 4,
			colors: ["#8833ff"],
			strokeColors: "#fff",
			strokeWidth: 2,
			hover: {
				size: 7,
			}
		},
		grid: {
		   show: true,
		   borderColor: '#ededed',
		   strokeDashArray: 4,
		}
	};
	var chart = new ApexCharts(document.querySelector("#chart1"), options);
	chart.render();

	Highcharts.chart('chart7', {
		chart: {
			type: 'variablepie',
			height: 330,
			styledMode: true
		},
		credits: {
			enabled: false
		},
		title: {
			text: 'Total Expenses by Category'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		accessibility: {
			point: {
				valueSuffix: '%'
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.percentage:.1f} %'
				}
			}
		},
		series: [{
			minPointSize: 10,
			innerSize: '65%',
			zMin: 0,
			name: 'Traffic',
			data: otherchartData,
		}]
	});


	// chart 9

	var fuelOptions = {
        series: [{
            name: 'Fuel Expenses',
            data: cost_expenses.map(item => Math.abs(item.fuel_expense)),
        }],
        chart: {
            foreColor: '#9ba7b2',
            type: 'bar',
            height: 280,
            toolbar: { show: false },
        },
        plotOptions: {
            bar: { horizontal: false, columnWidth: '25%' }
        },
        dataLabels: { enabled: false },
        colors: ['#0dcaf0'],
        xaxis: { categories: cost_expenses.map(item => item.month) },
        fill: { opacity: 1 },
        tooltip: {
            y: { formatter: (val) => "$" + val }
        }
    };
    var chartFuel = new ApexCharts(document.querySelector("#chartFuel"), fuelOptions);
    chartFuel.render();

	var maintenanceOptions = {
        series: [{
            name: 'Maintenance Expenses',
            data: cost_expenses.map(item => Math.abs(item.maintenance_expense)),
        }],
        chart: {
            foreColor: '#9ba7b2',
            type: 'bar',
            height: 280,
            toolbar: { show: false },
        },
        plotOptions: {
            bar: { horizontal: false, columnWidth: '25%' }
        },
        dataLabels: { enabled: false },
        colors: ['#4bcc56'],
        xaxis: { categories: cost_expenses.map(item => item.month) },
        fill: { opacity: 1 },
        tooltip: {
            y: { formatter: (val) => "$" + val }
        }
    };
    var chartMaintenance = new ApexCharts(document.querySelector("#chartMaintenance"), maintenanceOptions);
    chartMaintenance.render();

    //  chart for cost per mile:

	Highcharts.chart('costPerMileChart', {
		chart: {
			type: 'line',
			backgroundColor: {
				linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
				stops: [
					[0, 'rgba(255, 255, 255, 1)'],
					[1, 'rgba(240, 240, 240, 1)']
				]
			},
			shadow: {
				color: 'rgba(0, 0, 0, 0.2)',
				offsetX: 2,
				offsetY: 2,
				width: 10
			}
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: ['Apr \'21', 'May \'21', 'Jun \'21', 'Jul \'21', 'Aug \'21', 'Sep \'21'],
			title: {
				text: null
			},
			gridLineWidth: 1,
			gridLineColor: '#F0F0F0',
			lineColor: '#007BFF',
			lineWidth: 2
		},
		yAxis: {
			title: {
				text: 'Cost Per Mile',
				style: {
					color: '#333',
					fontWeight: 'bold'
				}
			},
			min: 0,
			gridLineWidth: 1,
			gridLineColor: '#F0F0F0',
			lineColor: '#007BFF',
			lineWidth: 2
		},
		tooltip: {
			backgroundColor: 'rgba(255, 255, 255, 0.9)',
			borderColor: '#007BFF',
			borderRadius: 10,
			borderWidth: 2,
			shadow: true,
			useHTML: true,
			formatter: function() {
				return `<b>${this.series.name}</b><br>${this.x}: ${this.y}`;
			}
		},
		legend: {
			align: 'center',
			verticalAlign: 'bottom',
			layout: 'horizontal',
			itemStyle: {
				color: '#333',
				fontWeight: 'bold'
			},
			itemHoverStyle: {
				color: '#007BFF'
			}
		},
		plotOptions: {
			line: {
				dataLabels: {
					enabled: true,
					format: '{y}',
					style: {
						color: '#333',
						fontWeight: 'bold',
						textOutline: 'none'
					}
				},
				enableMouseTracking: true
			}
		},
		series: [{
			name: 'Cost/Mi',
			data: [0, 1, 3, 1.5, 3.2, null],
			color: '#007BFF',
			lineWidth: 3,
			marker: {
				enabled: true,
				radius: 5,
				symbol: 'circle'
			},
			dashStyle: 'Solid'
		}, {
			name: 'Forecast',
			data: [null, null, null, null, null, 2],
			color: '#FF5733',
			lineWidth: 3,
			marker: {
				enabled: true,
				radius: 5,
				symbol: 'diamond'
			},
			dashStyle: 'Dash'
		}],
		responsive: {
			rules: [{
				condition: {
					maxWidth: 500
				},
				chartOptions: {
					legend: {
						align: 'center',
						verticalAlign: 'bottom',
						layout: 'horizontal'
					}
				}
			}]
		}
	});

    //  chart for others cost:

	var otherscost = {
        series: [{
            name: 'Others Cost',
            data: othersCostExpenseTotalCount.costs,
        }],
        chart: {
            foreColor: '#9ba7b2',
            type: 'bar',
            height: 280,
            toolbar: { show: false },
        },
        plotOptions: {
            bar: { horizontal: false, columnWidth: '25%' }
        },
        dataLabels: { enabled: false },
        colors: ['#f5162c'],
        xaxis: { categories: othersCostExpenseTotalCount.months },
        fill: { opacity: 1 },
        tooltip: {
            y: { formatter: (val) => "$" + val }
        }
    };
    var otherCost = new ApexCharts(document.querySelector("#otherCost"), otherscost);
    otherCost.render();
	// Highcharts.chart('otherCost', {
	// 	chart: {
	// 		type: 'column'
	// 	},
	// 	title: {
	// 		text: 'Monthly Expenses'
	// 	},
	// 	xAxis: {
	// 		categories: othersCostExpenseTotalCount.months, // Use months from the JSON data
	// 		crosshair: true
	// 	},
	// 	yAxis: {
	// 		min: 0,
	// 		title: {
	// 			text: 'Total Expense'
	// 		}
	// 	},
	// 	series: [{
	// 		name: 'Expense',
	// 		data: othersCostExpenseTotalCount.costs // Use costs from the JSON data
	// 	}]
	// });

	
});