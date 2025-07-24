<div>
    @vite(['resources/js/kondisichart.js'])
    <div id="kondisiCharts"></div>
    <script type="text/javascript">
        const labels = @json($labels);
        const counts = @json($counts);
        var chartKondisi = {
            series: [{
                name: 'Jumlah'
                , data: counts
            }]
            , chart: {
                height: 350
                , type: 'bar'
            , }
            , plotOptions: {
                bar: {
                    borderRadius: 10
                    , dataLabels: {
                        position: 'top', // top, center, bottom
                    }
                , }
            }
            , dataLabels: {
                enabled: true
                , formatter: function(val) {
                    return val + "%";
                }
                , offsetY: -20
                , style: {
                    fontSize: '12px'
                    , colors: ["#304758"]
                }
            },

            xaxis: {
                categories: labels
                , position: 'top'
                , axisBorder: {
                    show: false
                }
                , axisTicks: {
                    show: false
                }
                , crosshairs: {
                    fill: {
                        type: 'gradient'
                        , gradient: {
                            colorFrom: '#D8E3F0'
                            , colorTo: '#BED1E6'
                            , stops: [0, 100]
                            , opacityFrom: 0.4
                            , opacityTo: 0.5
                        , }
                    }
                }
                , tooltip: {
                    enabled: true
                , }
            }
            , yaxis: {
                axisBorder: {
                    show: false
                }
                , axisTicks: {
                    show: false
                , }
                , labels: {
                    show: false
                    , formatter: function(val) {
                        return val + "%";
                    }
                }

            }
            , title: {
                text: 'Monthly Inflation in Argentina, 2002'
                , floating: true
                , offsetY: 330
                , align: 'center'
                , style: {
                    color: '#444'
                }
            }
        };

    </script>

</div>
