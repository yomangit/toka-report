<div>
    <div wire:init="loadChartData" wire:poll.5s="loadChartData">
        <div wire:ignore id="chartContainer"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    let chart;

    function initChart(labels, counts) {
        const options = {
            chart: { type: 'bar', height: 350 },
            series: [{
                name: 'Jumlah',
                data: counts
            }],
            xaxis: {
                categories: labels.map(label =>
                    label.length > 20 ? label.slice(0, 20) + '…' : label
                ),
                labels: { rotate: -45 }
            },
            colors: labels.map((_, i) => {
                const baseColors = ['#FF4560', '#008FFB', '#00E396', '#FEB019'];
                return baseColors[i % baseColors.length];
            }),
            plotOptions: {
                bar: { distributed: true, borderRadius: 4 }
            }
        };

        chart = new ApexCharts(document.querySelector("#chartContainer"), options);
        chart.render();
    }

    window.addEventListener('livewire:load', () => {
        initChart(@json($labels), @json($counts));
    });

    window.addEventListener('chartDataUpdated', (event) => {
        const data = event.detail;

        if (!data || !data.labels || !data.counts) {
            console.warn('Data kosong atau rusak:', data);
            return;
        }

        chart.updateOptions({
            xaxis: {
                categories: data.labels.map(label =>
                    label.length > 20 ? label.slice(0, 20) + '…' : label
                )
            },
            colors: data.labels.map((_, i) => {
                const baseColors = ['#FF4560', '#008FFB', '#00E396', '#FEB019'];
                return baseColors[i % baseColors.length];
            })
        });

        chart.updateSeries([{
            name: 'Jumlah',
            data: data.counts
        }]);
    });
</script>
@endpush
