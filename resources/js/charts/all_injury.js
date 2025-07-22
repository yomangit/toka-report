import ApexCharts from "apexcharts";


// resources/js/charts/all_injury.js

document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.chartData === 'undefined') {
        console.warn("chartData tidak ditemukan di window scope.");
        return;
    }

    const chartOptions = {
        series: [
            { name: 'LTI', type: 'column', data: window.chartData.LTI },
            { name: 'MTI', type: 'column', data: window.chartData.MTI },
            { name: 'RDI', type: 'column', data: window.chartData.RDI },
            { name: 'FAI', type: 'column', data: window.chartData.FAI },
            { name: 'LTIFR', type: 'line', data: window.chartData.LTIFR },
            { name: 'LTIFR Target', type: 'line', data: window.chartData.LTIFR_Target },
        ],
        chart: {
            height: 350,
            type: 'line',
            stacked: false
        },
        zoom: {
            enabled: false
        },
        colors: ['#8A0100', '#B89242', '#F1F500', '#006F26', '#F50400', '#8079C7'],
        stroke: {
            width: [1, 1, 1, 1, 3, 4],
            dashArray: [0, 0, 0, 0, 0, 4],
            curve: 'smooth'
        },
        title: {
            text: 'All Injury VS LTIFR (24MMA)',
            align: 'center',
            style: {
                fontSize: '12px',
                fontWeight: 'bold',
                color: '#fb7185'
            },
        },
        xaxis: {
            categories: window.chartData.months
        },
        yaxis: {
            title: {
                text: 'Points',
            }
        },
        tooltip: {
            fixed: {
                enabled: true,
                position: 'topLeft',
                offsetY: 30,
                offsetX: 60
            },
        },
        legend: {
            horizontalAlign: 'center',
            offsetX: 40
        }
    };

    const chartEl = document.querySelector("#all_injury_vs_ltifr");
    if (chartEl) {
        const chart = new ApexCharts(chartEl, chartOptions);
        chart.render();
    } else {
        console.warn("#all_injury_vs_ltifr element not found");
    }
});
