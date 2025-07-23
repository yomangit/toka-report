<div>
    <div id="kondisiBarChart"></div>

    <script type="module">
        import ApexCharts from 'apexcharts';

        const labels = @json($labels);
        const counts = @json($counts);

        const options = {
            chart: {
                type: 'bar',
                height: 350
            },
            series: [{
                name: 'Jumlah Laporan',
                data: counts
            }],
            xaxis: {
                categories: labels
            }
        };

        const chart = new ApexCharts(document.querySelector("#kondisiBarChart"), options);
        chart.render();
    </script>
</div>
