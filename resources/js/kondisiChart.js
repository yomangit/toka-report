import ApexCharts from "apexcharts";

// Inisialisasi awal kosong
let chart;

function renderChart(labels, counts) {
    const shortLabels = labels.map(label => label.length > 20 ? label.slice(0, 20) + '…' : label);

    const options = {
        chart: {
            type: 'bar',
            height: 350
        },
        colors: labels.map((_, i) => {
            const colorList = ['#FF4560', '#008FFB', '#00E396', '#FEB019', '#775DD0', '#FF66C3', '#546E7A', '#26a69a', '#D10CE8'];
            return colorList[i % colorList.length];
        }),
        series: [{
            name: 'Jumlah',
            data: counts
        }],
        title: {
            text: 'Kondisi Tidak Aman',
            align: 'center',
            style: {
                fontSize: '12px',
                fontWeight: 'bold',
                color: '#fb7185'
            }
        },
        xaxis: {
            categories: shortLabels,
            labels: {
                rotate: -45,
                style: {
                    fontSize: '9px'
                }
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                distributed: true
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.25,
                inverseColors: true,
                opacityFrom: 0.9,
                opacityTo: 1,
                stops: [50, 100]
            }
        }
    };

    const chartEl = document.querySelector("#kondisiChart");

    if (chart) {
        chart.updateOptions({
            series: [{
                name: 'Jumlah',
                data: counts
            }],
            xaxis: {
                categories: shortLabels
            }
        });
    } else if (chartEl) {
        chart = new ApexCharts(chartEl, options);
        chart.render();
    }
}

// Tunggu Livewire siap
document.addEventListener('livewire:load', () => {
    // Dengarkan event dari Livewire
    Livewire.on('kondisiChartUpdated', ({
        labels,
        counts
    }) => {
        renderChart(labels, counts);
    });
});
