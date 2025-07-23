let kondisiChart = null;

export function renderKondisiBarChart() {
    const el = document.querySelector('#kondisiBarChart');
    if (!el) return;

    const labels = JSON.parse(el.dataset.labels || "[]");
    const counts = JSON.parse(el.dataset.counts || "[]");

    const options = {
        chart: {
            type: 'bar',
            height: 350,
        },
        series: [{
            name: 'Jumlah Laporan',
            data: counts,
        }],
        xaxis: {
            categories: labels,
        },
        title: {
            text: 'Kondisi Tidak Aman',
        },
    };

    kondisiChart = new ApexCharts(el, options);
    kondisiChart.render();
}

// Update dari Livewire
document.addEventListener('update-kondisi-chart', (e) => {
    const { labels, counts } = e.detail;

    if (!kondisiChart) return;

    kondisiChart.updateOptions({
        xaxis: {
            categories: labels
        }
    });

    kondisiChart.updateSeries([{
        name: 'Jumlah Laporan',
        data: counts
    }]);
});
