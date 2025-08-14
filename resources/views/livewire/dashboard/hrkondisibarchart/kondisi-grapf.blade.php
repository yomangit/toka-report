   <div>
       <div id="chart-container" style="height: 400px;"></div>
   </div>

   @push('scripts')
   <script src="https://echarts.apache.org/en/js/vendors/echarts/dist/echarts.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
   <script>
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
               , data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
           }
           , yAxis: {
               type: 'value'
           }
           , series: [{
               data: [120, 200, 150, 80, 70, 110, 130]
               , type: 'bar'
           }]
       };


       if (option && typeof option === 'object') {
           myChart.setOption(option);
       }

       window.addEventListener('resize', myChart.resize);

   </script>
   @endpush
