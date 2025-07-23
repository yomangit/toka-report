import ApexCharts from 'apexcharts';

let chart = null;

export function renderKondisiBarChart() {
    const el = document.querySelector('#kondisiBarChart');
    if (!el) return;

    // Inisialisasi kosong
    const options = {
        chart: {
            type: 'bar',
            height: 350,
        },
        series: [{
            name: 'Jumlah Laporan',
            data: []
        }],
        xaxis: {
            categories: []
        },
        title: {
            text: 'Kondisi Tidak Aman'
        }
    };

    chart = new ApexCharts(el, options);
    chart.render();
}

// Realtime update via Livewire dispatch
document.addEventListener('update-kondisi-chart', (e) => {
    if (!chart) return;

    const { labels, counts } = e.detail;

    chart.updateOptions({
        xaxis: {
            categories: labels
        }
    });

    chart.updateSeries([{
        name: 'Jumlah Laporan',
        data: counts
    }]);
});
