<div>
    @vite(['resources/js/kondisichart.js'])
    <div id="kondisiChart"></div>
    <script>
        // Passing PHP Livewire data ke JS
        const labels = @json($labels);
        const counts = @json($counts);

        console.log('Labels:', labels);
        console.log('Counts:', counts);

        var chartOptions = {
            series: [{
                data: [21, 22, 10, 28, 16, 21, 13, 30]
            }]
            , chart: {
                height: 350
                , type: 'bar'
                , events: {
                    click: function(chart, w, e) {
                        // console.log(chart, w, e)
                    }
                }
            }
            , colors: colors
            , plotOptions: {
                bar: {
                    columnWidth: '45%'
                    , distributed: true
                , }
            }
            , dataLabels: {
                enabled: false
            }
            , legend: {
                show: false
            }
            , xaxis: {
                categories: [
                    ['John', 'Doe']
                    , ['Joe', 'Smith']
                    , ['Jake', 'Williams']
                    , 'Amber'
                    , ['Peter', 'Brown']
                    , ['Mary', 'Evans']
                    , ['David', 'Wilson']
                    , ['Lily', 'Roberts']
                , ]
                , labels: {
                    style: {
                        colors: colors
                        , fontSize: '12px'
                    }
                }
            }
        };

    </script>

</div>
