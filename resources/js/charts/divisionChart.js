import ApexCharts from 'apexcharts';

export function renderDivisionChart() {
    const el = document.querySelector('#divisionChart');
    if (!el) return;

    const labels = JSON.parse(el.dataset.labels || "[]");
    const counts = JSON.parse(el.dataset.counts || "[]");

    const options = {
        chart: {
            type: 'bar',
            height: 350
        },
        series: [{
            name: 'Jumlah Laporan',
            data: counts
        }],
        xaxis: {
            categories: labels
        },
        colors: ['#3B82F6'],
        title: {
            text: 'Laporan Hazard per Divisi',
            align: 'center'
        }
    };

    const chart = new ApexCharts(el, options);
    chart.render();
}