import ApexCharts from 'apexcharts';

export function renderDivisionChart() {
    const el = document.querySelector('#divisionChart');
    if (!el) return;

    const labels = JSON.parse(el.dataset.labels || "[]");
    const counts = JSON.parse(el.dataset.counts || "[]");
    const color = JSON.parse(el.dataset.colors || "[]");

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
        colors: color,
        plotOptions: {
            bar: {
                distributed: true, // ✅ INI BAGIAN PENTINGNYA
                borderRadius: 4,
                horizontal: false
            }
        },
        title: {
            text: 'Laporan Hazard per Divisi',
            align: 'center'
        }
    };

    const chart = new ApexCharts(el, options);
    chart.render();
}
