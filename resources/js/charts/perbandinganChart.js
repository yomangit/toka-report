import ApexCharts from 'apexcharts';

let perbandinganChart = null;

export function renderPerbandinganChart() {
    const el = document.querySelector('#perbandinganPieChart');
    if (!el) return;

    const labels = JSON.parse(el.dataset.labels || "[]");
    const counts = JSON.parse(el.dataset.counts || "[]");

    const options = {
        chart: {
            type: 'pie',
            height: 350
        },
        labels: labels,
        series: counts,
        title: {
            text: 'Perbandingan Kondisi vs Tindakan Tidak Aman',
            align: 'center'
        }
    };

    perbandinganChart = new ApexCharts(el, options);
    perbandinganChart.render();
}

// ✅ Update chart saat event dikirim dari Livewire
document.addEventListener('update-perbandingan-chart', (e) => {
    const { labels, counts } = e.detail;

    if (!perbandinganChart) return;

    perbandinganChart.updateOptions({ labels });
    perbandinganChart.updateSeries(counts);
});

// ✅ Emit Livewire setiap 10 detik
document.addEventListener('livewire:load', () => {
    renderPerbandinganChart();

    setInterval(() => {
        window.Livewire.emit('refreshPerbandinganChart');
    }, 10000); // 10 detik
});
