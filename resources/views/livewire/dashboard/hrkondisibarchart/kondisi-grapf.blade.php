   <div>
       <div id="chart-container" style="height: 400px;"></div>
   </div>

   @push('scripts')
   <script src="https://echarts.apache.org/en/js/vendors/echarts/dist/echarts.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
   <script>
       const data = JSON.parse('<?php echo $kondisi ?>');
       var dom = document.getElementById('chart-container');
       var myChart = echarts.init(dom, null, {
           renderer: 'canvas'
           , useDirtyRect: false
       });
       var app = {};


       var option;

       option = {
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
               , label: {
                   show: true
                   , position: 'inside'
               }
           , }],

       };


       if (option && typeof option === 'object') {
           myChart.setOption(option);
       }

       window.addEventListener('resize', myChart.resize);

   </script>
   @endpush
