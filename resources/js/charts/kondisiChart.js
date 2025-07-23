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

        title: {
            text: 'Laporan per Kondisi Tidak Aman',
            align: 'center',
            style: {
                fontSize: '10px'
            }
        },
        xaxis: {
            categories: labels,
            labels: {
                style: {
                    fontSize: '10px'
                },
                formatter: function (value) {
                    return value.length > 15 ? value.substring(0, 12) + '…' : value;
                },
            },
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
// 🔄 Listen Livewire event
document.addEventListener('update-kondisi-chart', (e) => {
    if (!kondisiChart) return;

    const { labels, counts } = e.detail;
 console.log('Updating chart:', e.detail);
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