<div>
    <div id="kondisiBarChart"></div>

    <script>
        import ApexCharts from 'apexcharts';

        const labels = JSON.parse('<?php echo $labels; ?>');
        const counts = JSON.parse('<?php echo $counts; ?>');

        const options = {
            chart: {
                type: 'bar'
                , height: 350
            }
            , series: [{
                name: 'Jumlah Laporan'
                , data: counts
            }],

            title: {
                text: 'Laporan per Kondisi Tidak Aman'
                , align: 'center'
                , style: {
                    fontSize: '10px'
                }
            }
            , xaxis: {
                categories: labels
                , labels: {
                    style: {
                        fontSize: '10px'
                    }
                    , formatter: function(value) {
                        return value.length > 15 ? value.substring(0, 12) + '…' : value;
                    }
                , }
            , }
            , plotOptions: {
                bar: {
                    horizontal: false
                    , borderRadius: 4
                    , distributed: true
                }
            }
            , colors: ['#3f51b5', '#00bcd4', '#8bc34a', '#ff9800', '#e91e63']
        };

        const chart = new ApexCharts(document.querySelector("#kondisiBarChart"), options);
        chart.render();

    </script>
</div>
