<div wire:init="loadChartData" wire:poll.10s="loadChartData">
    <div id="kondisiChart" wire:ignore></div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        let kondisiChart;
        const chartEl = document.querySelector("#kondisiChart");

        function initChart(labels, counts) {
            if (kondisiChart) {
                kondisiChart.destroy(); // 🔥 Penting: Hapus chart lama dulu
            }

            const options = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Jumlah',
                    data: counts
                }],
                xaxis: {
                    categories: labels
                }
            };

            kondisiChart = new ApexCharts(chartEl, options);
            kondisiChart.render();
        }

        // Pertama kali render chart
        initChart(@json($labels), @json($counts));

        // Update chart saat Livewire component re-render
        Livewire.hook('message.processed', () => {
            initChart(@json($labels), @json($counts));
        });
    });
</script>
@endpush

