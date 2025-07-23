let kondisiChart = null;

export function renderKondisiBarChart() {
    const el = document.querySelector('#kondisiBarChart');
    if (!el) return;

    // Hapus chart sebelumnya kalau ada
    if (window.kondisiChart) {
        window.kondisiChart.destroy();
    }

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

    window.kondisiChart = new ApexCharts(el, options);
    window.kondisiChart.render();
}


// Update dari Livewire
document.addEventListener('update-kondisi-chart', (e) => {
    const { labels, counts } = e.detail;
 console.log('[Livewire update] chart data:', e.detail);
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
