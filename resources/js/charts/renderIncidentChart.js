// resources/js/charts/renderIncidentChart.js
import ApexCharts from 'apexcharts';

/**
 * Render Incident Chart
 * @param {string} selector - Target selector for the chart container
 * @param {object} data - Data for the incident chart
 */
export function renderIncidentChart(selector, data) {
    if (!data || typeof data !== 'object') {
        console.warn("Incident data tidak valid.");
        return;
    }

    const options = {
        series: [{
            name: 'Jumlah Insiden',
            data: data.values, // asumsi: array angka
        }],
        chart: {
            type: 'bar',
            height: 400
        },
        title: {
            text: 'Statistik Insiden Bulanan',
            align: 'center',
            style: {
                fontSize: '14px',
                fontWeight: 'bold',
                color: '#1f2937'
            }
        },
        xaxis: {
            categories: data.labels, // asumsi: array bulan
            title: {
                text: 'Bulan'
            }
        },
        yaxis: {
            title: {
                text: 'Jumlah'
            }
        },
        colors: ['#f97316'],
        tooltip: {
            y: {
                formatter: val => `${val} insiden`
            }
        }
    };

    const el = document.querySelector(selector);
    if (el) {
        const chart = new ApexCharts(el, options);
        chart.render();
    } else {
        console.warn(`Element "${selector}" tidak ditemukan.`);
    }
}
