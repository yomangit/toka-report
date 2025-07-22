import ApexCharts from 'apexcharts';

let divisionChartInstance = null;

export function renderDivisionChart() {
    const el = document.querySelector('#divisionChart');
    if (!el) return;

    const labels = JSON.parse(el.dataset.labels || "[]");
    const counts = JSON.parse(el.dataset.counts || "[]");
    const colors = JSON.parse(el.dataset.colors || "[]");

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
        colors: colors,
        plotOptions: {
            bar: {
                distributed: true,
                borderRadius: 4,
                horizontal: false
            }
        },
        title: {
            text: 'Laporan Hazard per Divisi',
            align: 'center'
        }
    };

    divisionChartInstance = new ApexCharts(el, options);
    divisionChartInstance.render();
}

// Update chart saat Livewire kirim event
document.addEventListener('update-division-chart', (e) => {
    const el = document.querySelector('#divisionChart');
    if (!el) return;

    const { labels, counts, colors } = e.detail;

    if (!divisionChartInstance) {
        renderDivisionChart();
        return;
    }

    divisionChartInstance.updateOptions({
        xaxis: { categories: labels },
        colors: colors
    });

    divisionChartInstance.updateSeries([{
        name: 'Jumlah Laporan',
        data: counts
    }]);
});
