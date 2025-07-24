<div>
    @vite(['resources/js/kondisichart.js'])
    <div id="kondisiCharts"></div>
    <script type="text/javascript">
        const labels = @json($labels);
        const counts = @json($counts);
        var chartKondisi = {
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
