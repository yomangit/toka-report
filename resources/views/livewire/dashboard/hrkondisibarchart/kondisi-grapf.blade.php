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
           tooltip: {}
           , visualMap: {
               max: Math.max(...data.count)
               , inRange: {
                   color: ['#4CAF50', '#FFC107', '#F44336'] // gradasi warna
               }
           }
           , xAxis3D: {
               type: 'category'
               , data: data.label
           }
           , yAxis3D: {
               type: 'category'
               , data: ['Jumlah'] // bisa 1 kategori karena ini bar 3D tunggal
           }
           , zAxis3D: {
               type: 'value'
           }
           , grid3D: {
               boxWidth: 200
               , boxDepth: 50
               , viewControl: {
                   projection: 'perspective'
               }
           }
           , series: [{
               type: 'bar3D'
               , data: data.label.map((label, i) => [label, 'Jumlah', data.count[i]])
               , shading: 'color'
               , label: {
                   show: true
                   , formatter: '{c}'
               }
               , itemStyle: {
                   opacity: 0.9
               }
           }]
       };

       myChart.setOption(option);
       window.addEventListener('resize', myChart.resize);

   </script>

   @endpush
