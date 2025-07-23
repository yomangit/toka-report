import ApexCharts from 'apexcharts';

let chart = null;

export function renderKondisiBarChart() {
    const el = document.querySelector('#kondisiBarChart');
    document.addEventListener('update-kondisi-chart', (e) => {
        const {
            labels,
            counts
        } = e.detail;
        if (!chart) return;
        if (!el) return;

        const options = {
            chart: {
                type: 'bar',
                height: 350,
            },
            series: [{
                name: 'Jumlah Laporan',
                data: counts
            }],
            xaxis: {
                categories: labels,
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
    });
}

// Update via Livewire event
