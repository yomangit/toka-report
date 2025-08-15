   <div>
       <x-input-daterange id="tanggal_range" placeholder='date-range' />
       <div wire:ignore id="chart_kondisi" style="height: 400px;"></div>
       <div wire:ignore id="chart-pie" style="height: 400px;"></div>

   </div>
   @push('scripts')
   <script src="https://echarts.apache.org/en/js/vendors/echarts/dist/echarts.min.js"></script>
   <script src="https://echarts.apache.org/en/js/vendors/echarts-gl/dist/echarts-gl.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
   <script>
       // Date range
       flatpickr("#tanggal_range", {
           mode: 'range'
           , dateFormat: "d-m-Y", //defaults to "F Y"
           onChange: function(dates) {
               if (dates.length === 2) {

                   var start = new Date(dates[0]);
                   var end = new Date(dates[1]);

                   var year = start.getFullYear();
                   var month = start.getMonth() + 1;
                   var dt = start.getDate();

                   if (dt < 10) {
                       dt = '0' + dt;
                   }
                   if (month < 10) {
                       month = '0' + month;
                   }
                   var year2 = end.getFullYear();
                   var month2 = end.getMonth() + 1;
                   var dt2 = end.getDate();

                   if (dt2 < 10) {
                       dt2 = '0' + dt2;
                   }
                   if (month2 < 10) {
                       month2 = '0' + month2;
                   }

                   // var tglMulai = year + '-' + month + '-' + dt;
                   // var tglAkhir = year2 + '-' + month2 + '-' + dt2;

                   var tglMulai = year + '-' + month + '-' + dt;
                   var tglAkhir = year2 + '-' + month2 + '-' + dt2;
                   @this.set('tglMulai', tglMulai)
                   @this.set('tglAkhir', tglAkhir)
               } else {
                   @this.set('tglMulai', null)
                   @this.set('tglAkhir', null)
               }
           }
       });
       // end date range
       setInterval(() => Livewire.dispatch('chartUpdated'), 1000);
       const data = JSON.parse('<?php echo $kondisi ?>');
       var dom = document.getElementById('chart_kondisi');
       var myChart = echarts.init(dom);

       var option = {

           tooltip: {
               trigger: 'item'
           }
           , legend: {
               selectedMode: true
           }
           , xAxis: {
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

       Livewire.on('berhasilUpdate', event => {
           let payload = JSON.parse(event); // ini parse JSON dari PHP
           myChart.setOption({
               xAxis: {
                   data: payload.label
               }
               , series: [{
                   data: payload.count
               }]
           });
       });
       // ===== PIE CHART =====
       const data_pie = JSON.parse('<?php echo $pie ?>');
       const chartData = data_pie.label.map((label, index) => ({
           value: data_pie.count[index]
           , name: label
       }));
       var dom_ie = document.getElementById('chart-pie');
       var pieChart = echarts.init(dom_ie, null, {
           renderer: 'canvas'
           , useDirtyRect: false
       });
       var app = {};


       var option_pie;
       option_pie = {
           title: {
               text: 'Leading Indicator Cause Analysis'
               , left: 'center'
           }
           , tooltip: {
               trigger: 'item'
           }
           , legend: {
               orient: 'vertical'
               , left: 'left'
           }
           , series: [{
               name: 'Access From'
               , type: 'pie'
               , radius: '50%'
               , data: chartData
               , emphasis: {
                   itemStyle: {
                       shadowBlur: 10
                       , shadowOffsetX: 0
                       , shadowColor: 'rgba(0, 0, 0, 0.5)'
                   }
               }
           }]
       };

       if (option_pie && typeof option_pie === 'object') {
           pieChart.setOption(option_pie);
       }
       Livewire.on('berhasilUpdate', event => {
           const chartData = event.label.map((label, index) => ({
               value: event.count[index]
               , name: label
           }));
           myChart.setOption({
               series: [{
                   name: 'Access From'
                   , type: 'pie'
                   , radius: '50%'
                   , data: chartData
                   , emphasis: {
                       itemStyle: {
                           shadowBlur: 10
                           , shadowOffsetX: 0
                           , shadowColor: 'rgba(0, 0, 0, 0.5)'
                       }
                   }
               }]
           });
       });
       // ===== RESIZE HANDLER =====
       window.addEventListener('resize', () => {
           pieChart.resize();
           myChart.resize();
       });

   </script>
   @endpush
