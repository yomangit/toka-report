   <div>
       <div id="chart-container" style="height: 400px;"></div>
   </div>

   @push('scripts')
   <script src="https://echarts.apache.org/en/js/vendors/echarts/dist/echarts.min.js"></script>
   <script src="https://echarts.apache.org/en/js/vendors/echarts-gl/dist/echarts-gl.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

   <script>
       const data = JSON.parse('<?php echo $kondisi ?>');
       var dom = document.getElementById('chart-container');
       var myChart = echarts.init(dom);

       var option = {
           color: ['#4CAF50'], // warna batang
           xAxis: {
               type: 'category'
               , data: data.label
           }
           , yAxis: {
               type: 'value'
           }
           , series: [{
               data: data.count
               , type: 'bar'
               , itemStyle: {
                   color: (params) => {
                       // Warna dinamis per batang
                       const warna = ['#4CAF50', '#FFC107', '#F44336', '#2196F3'];
                       return warna[params.dataIndex % warna.length];
                   }
               }
               , label: {
                   show: true
                   , position: 'inside'
               }
           }]
       };


       myChart.setOption(option);
       window.addEventListener('resize', myChart.resize);

   </script>

   @endpush
