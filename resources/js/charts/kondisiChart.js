import ApexCharts from 'apexcharts';

let chart = null;

export function renderKondisiBarChart() {
    const el = document.querySelector('#kondisiBarChart');
    if (!el) return;

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
            categories: [],
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        title: {
            text: 'Kondisi Tidak Aman',
            style: {
                fontSize: '16px'
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                distributed: true
            }
        }
    };

    chart = new ApexCharts(el, options);
    chart.render();
}

// Update via Livewire event
document.addEventListener('update-kondisi-chart', (e) => {
    const { labels, counts } = e.detail;

    if (!chart) return;

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
