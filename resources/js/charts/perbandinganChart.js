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
            height: 350,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            },
            dropShadow: {
                enabled: true,
                top: 2,
                left: 2,
                blur: 4,
                opacity: 0.2
            }
        },
        labels: labels,
        series: counts,
        title: {
            text: 'Perbandingan Kondisi vs Tindakan Tidak Aman',
            align: 'center'
        },
        colors: ['#00bcd4', '#ff5722'], // 🎨 Ganti warna sesuai selera
        fill: {
            type: 'gradient',
        },
        legend: {
            position: 'bottom'
        }
    };

    perbandinganChart = new ApexCharts(el, options);
    perbandinganChart.render();
}

document.addEventListener('update-perbandingan-chart', (e) => {
    const { labels, counts } = e.detail;
    if (!perbandinganChart) return;

    perbandinganChart.updateOptions({
        labels: labels,
        colors: ['#00bcd4', '#ff5722'], // gunakan warna konsisten
    });
    perbandinganChart.updateSeries(counts);
});
