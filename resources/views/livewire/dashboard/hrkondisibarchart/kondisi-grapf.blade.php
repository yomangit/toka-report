<div wire:init="loadChartData" wire:poll.10s="loadChartData">
    <div id="kondisiChart" wire:ignore></div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("livewire:load", () => {
        const chartEl = document.querySelector("#kondisiChart");

        let kondisiChart = new ApexCharts(chartEl, {
            chart: {
                type: 'bar',
                height: 350
            },
            series: [{
                name: 'Jumlah',
                data: @json($counts)
            }],
            xaxis: {
                categories: @json($labels)
            }
        });

        kondisiChart.render();

        // Perbarui chart ketika Livewire me-refresh
        Livewire.hook('message.processed', (message, component) => {
            kondisiChart.updateOptions({
                series: [{
                    data: @json($counts)
                }],
                xaxis: {
                    categories: @json($labels)
                }
            });
        });
    });
</script>
@endpush
