<div>
    {{-- @vite(['resources/js/charts/kondisiChart.js']) --}}
    <div wire:init="loadChartData">
        <div id="kondisiChart" wire:ignore></div>
    </div>
    <script>
        // Passing PHP Livewire data ke JS
        const labels = @json($labels);
        const counts = @json($counts);

        console.log('Labels:', labels);
        console.log('Counts:', counts);

        const chartOptions = {
            chart: {
                type: 'bar'
                , height: 350
            }
            , series: [{
                name: 'Jumlah'
                , data: counts
            }]
            , xaxis: {
                categories: labels
            }
        };

    </script>

</div>
