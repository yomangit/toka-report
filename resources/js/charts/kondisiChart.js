import ApexCharts from 'apexcharts';

let kondisiChart = null;

export function renderKondisiBarChart() {
    const el = document.querySelector('#kondisiBarChart');
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
        title: {
            text: 'Laporan per Kondisi Tidak Aman',
            align: 'center',
            style: {
                fontSize: '14px'
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 4,
                distributed: true
            }
        },
        colors: ['#3f51b5', '#00bcd4', '#8bc34a', '#ff9800', '#e91e63']
    };

    kondisiChart = new ApexCharts(el, options);
    kondisiChart.render();
}
