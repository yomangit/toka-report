// resources/js/charts/renderInjuryChart.js
import ApexCharts from 'apexcharts';

/**
 * Render All Injury vs LTIFR chart
 * @param {string} selector - CSS selector for the chart container
 * @param {object} chartData - Data object for ApexCharts (LTI, MTI, etc.)
 */
export function renderInjuryChart(selector, chartData) {
    if (!chartData) {
        console.warn("Chart data tidak tersedia.");
        return;
    }

    const options = {
        series: [
            { name: 'LTI', type: 'column', data: chartData.LTI },
            { name: 'MTI', type: 'column', data: chartData.MTI },
            { name: 'RDI', type: 'column', data: chartData.RDI },
            { name: 'FAI', type: 'column', data: chartData.FAI },
            { name: 'LTIFR', type: 'line', data: chartData.LTIFR },
            { name: 'LTIFR Target', type: 'line', data: chartData.LTIFR_Target },
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
            categories: chartData.months
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

    const chartEl = document.querySelector(selector);
    if (chartEl) {
        const chart = new ApexCharts(chartEl, options);
        chart.render();
    } else {
        console.warn(`Element '${selector}' tidak ditemukan`);
    }
}
