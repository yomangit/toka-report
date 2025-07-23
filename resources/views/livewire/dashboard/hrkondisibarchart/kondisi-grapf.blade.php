<div wire:poll.10s>
    <div id="barChart" wire:ignore></div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        let chart;

        document.addEventListener('livewire:load', () => {
            const options = {
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
            };
            chart = new ApexCharts(document.querySelector("#barChart"), options);
            chart.render();

            // Dengarkan event dari Livewire
            Livewire.on('chartDataUpdated', (data) => {
                chart.updateOptions({
                    xaxis: {
                        categories: data.labels
                    },
                    series: [{
                        name: 'Jumlah',
                        data: data.counts
                    }]
                });
            });
        });
    </script>
@endpush
