<div>
    @vite(['resources/js/kondisichart.js'])
    <div id="kondisiCharts"></div>
    <script type="text/javascript">
        const labels = @json($labels);
        const counts = @json($counts);

        const shortLabels = labels.map(label => label.length > 10 ? label.slice(0, 10) + '…' : label);

        const chartKondisi = {
            chart: {
                type: 'bar'
                , height: 350
            }
            , colors: labels.map((_, i) => {
                const colorList = ['#FF4560', '#008FFB', '#00E396', '#FEB019', '#775DD0', '#FF66C3', '#546E7A', '#26a69a', '#D10CE8'];
                return colorList[i % colorList.length];
            })
            , series: [{
                name: 'Jumlah'
                , data: counts
            }]
            , xaxis: {
                categories: shortLabels
                , labels: {
                    rotate: -45
                }
            }
            , plotOptions: {
                bar: {
                    borderRadius: 4
                    , distributed: true
                }
            }
            , fill: {
                type: 'gradient'
                , gradient: {
                    shade: 'light'
                    , type: 'vertical'
                    , shadeIntensity: 0.25
                    , inverseColors: true
                    , opacityFrom: 0.9
                    , opacityTo: 1
                    , stops: [50, 100]
                }
            }
        };

    </script>

</div>
