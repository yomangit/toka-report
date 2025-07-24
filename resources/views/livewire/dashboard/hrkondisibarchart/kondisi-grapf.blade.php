<div>
    <div wire:init="loadChartData" wire:poll.3s="loadChartData">
        <div wire:ignore id="kondisiCharts"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script type="text/javascript">
    const initialLabels = @json($labels ?? []);
    const initialCounts = @json($counts ?? []);

    function shortenLabels(labels) {
        return labels.map(label =>
            label.length > 20 ? label.slice(0, 20) + '…' : label
        );
    }

    function generateColors(length) {
        const colorList = ['#FF4560', '#008FFB', '#00E396', '#FEB019', '#775DD0', '#FF66C3', '#546E7A', '#26a69a', '#D10CE8'];
        return Array.from({ length }, (_, i) => colorList[i % colorList.length]);
    }

    const chartKondisi = {
        chart: {
            type: 'bar',
            height: 350
        },
        series: [{
            name: 'Jumlah',
            data: initialCounts
        }],
        colors: generateColors(initialLabels.length),
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
            categories: shortenLabels(initialLabels),
            labels: {
                rotate: -45,
                style: {
                    fontSize: '09px'
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

    const kondisiChart = new ApexCharts(
        document.querySelector("#kondisiCharts"),
        chartKondisi
    );
    kondisiChart.render();

    window.addEventListener('kondisiChartUpdated', (event) => {
        const data = event.detail;

        if (!data || !Array.isArray(data.labels) || !Array.isArray(data.counts)) {
            console.warn('Data tidak valid:', data);
            return;
        }

        const updatedLabels = data.labels;
        const updatedCounts = data.counts;

        kondisiChart.updateOptions({
            xaxis: {
                categories: shortenLabels(updatedLabels)
            },
            colors: generateColors(updatedLabels.length)
        });

        kondisiChart.updateSeries([{
            name: 'Jumlah',
            data: updatedCounts
        }]);
    });
</script>
@endpush
