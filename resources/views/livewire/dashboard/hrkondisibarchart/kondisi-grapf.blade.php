   <div>
       <div id="chartKondisi" style="height: 400px;"></div>
   </div>

   @push('scripts')
   <script src="https://echarts.apache.org/en/js/vendors/echarts/dist/echarts.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
   <script>
       // Date range
       document.addEventListener('livewire:navigated', function() {
           let chartDom = document.getElementById('chartKondisi');
           let myChart = echarts.init(chartDom);

           let option = {
               title: {
                   text: 'Laporan Hazard per Kondisi'
               }
               , tooltip: {}
               , xAxis: {
                   type: 'category'
                   , data: []
               }
               , yAxis: {
                   type: 'value'
               }
               , series: [{
                   type: 'bar'
                   , data: []
               }]
           };

           myChart.setOption(option);

           // Event listener di Livewire 3
           Livewire.on('updateChart', (payload) => {
               myChart.setOption({
                   xAxis: {
                       data: payload.data.map(v => v.label)
                   }
                   , series: [{
                       data: payload.data.map(v => v.count)
                   }]
               });
           });
       });
   </script>
   @endpush
