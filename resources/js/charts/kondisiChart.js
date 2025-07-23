import ApexCharts from 'apexcharts';

let chart = null;

export function renderKondisiBarChart(labels, counts) {
    const el = document.querySelector("#kondisiBarChart");

    if (!el) return;

    const options = {
        chart: {
            type: 'bar',
            height: 300,
            toolbar: { show: false },
        },
        series: [{
            name: 'Jumlah',
            data: counts,
        }],
        xaxis: {
            categories: labels,
        },
        colors: ['#10b981'],
    };

    // Destroy previous chart if exists
    if (chart) {
        chart.destroy();
    }

    chart = new ApexCharts(el, options);
    chart.render();
}
