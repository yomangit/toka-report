   <div>
       <x-input-daterange id="tanggal_range" placeholder='date-range' />
       <div class="mt-4 card card-border bg-base-300">
           <div wire:ignore id="chart-divisi" style="height: 400px;"></div>
       </div>
       <div class="grid gap-4 md:grid-cols-2">
           <div class="card card-border bg-base-300 ">
               <div wire:ignore id="chart_kondisi" style="height: 400px;"></div>
           </div>
           <div class="card card-border bg-base-300 ">
               <div wire:ignore id="chart_tindakan" style="height: 400px;"></div>
           </div>
       </div>
       <div class="mt-4 card card-border bg-base-300">
           <div wire:ignore id="chart-pie" style="height: 400px;"></div>
       </div>
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
       const divisi = JSON.parse('<?php echo $divisi ?>');
       const data = JSON.parse('<?php echo $kondisi ?>');
       console.log(divisi);
       console.log(data);
       
       const data_tta = JSON.parse('<?php echo $tindakan ?>');

       function getRandomColor() {
           return '#' + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0');
       }
       // Buat array warna sesuai jumlah data
       const warnaOtomatisdivisi = divisi.count.map(() => getRandomColor());
       const warnaOtomatis = data.count.map(() => getRandomColor());
       const warnaOtomatistta = data_tta.count.map(() => getRandomColor());
       var dom = document.getElementById('chart_kondisi');
       var myChart = echarts.init(dom);

       var option = {
           title: {
               text: 'Kondisi Tidak Aman'
               , left: 'center'
           }
           , tooltip: {
               trigger: 'item'
           }
           , legend: {
               selectedMode: true
           }
           , xAxis: {
               type: 'category'
               , data: data.label
               , axisLabel: {
                   interval: 0, // tampilkan semua label
                   formatter: function(value) {
                       //    return value.length > 20 ? value.slice(0, 20) + '...' : value;
                       return value.split(" ").join("\n");
                   }
               }
           }
           , yAxis: {
               type: 'value'
           }
           , series: [{
               data: data.count
               , type: 'bar'
               , itemStyle: {
                   color: (params) => warnaOtomatis[params.dataIndex]
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
       //    ===== TTA =====
       var dom_tta = document.getElementById('chart_tindakan');
       var myChart_tta = echarts.init(dom_tta);

       var option_tta = {
           title: {
               text: 'Tindakkan Tidak Aman'
               , left: 'center'
           }
           , tooltip: {
               trigger: 'item'
           }
           , legend: {
               selectedMode: true
           }
           , xAxis: {
               type: 'category'
               , data: data_tta.label
               , axisLabel: {
                   interval: 0, // tampilkan semua label
                   formatter: function(value) {
                       //    return value.length > 20 ? value.slice(0, 20) + '...' : value;
                       return value.split(" ").join("\n");
                   }
               }
           }
           , yAxis: {
               type: 'value'
           }
           , series: [{
               data: data_tta.count
               , type: 'bar'
               , itemStyle: {
                   color: (params) => warnaOtomatistta[params.dataIndex]
               }
               , label: {
                   show: true
                   , position: 'inside'
               }
           }]
       };
       myChart_tta.setOption(option_tta);

       Livewire.on('berhasilUpdate_tta', event => {
           let tta = JSON.parse(event); // ini parse JSON dari PHP
           myChart_tta.setOption({
               xAxis: {
                   data: tta.label
               }
               , series: [{
                   data: tta.count
               }]
           });
       });
       // ===== PIE CHART =====
       const data_pie = JSON.parse('<?php echo $pie ?>');
       const chartData = data_pie.label.map((label, index) => ({
           value: data_pie.count[index]
           , name: label
       }));
       const seriesName = chartData.map(d => d.label).join(', ');
       console.log(chartData);

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
               name: 'Cause Analysis'
               , type: 'pie'
               , radius: '50%'
               , data: chartData
               , label: {
                   formatter: '{d}%' // menampilkan persentase
               }
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
       Livewire.on('berhasilUpdatePie', event => {
           let payload = JSON.parse(event); // ini parse JSON dari PHP
           const chartData = payload.label.map((label, index) => ({
               value: payload.count[index]
               , name: label
           }));
           pieChart.setOption({
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
           myChart_tta.resize();
       });

   </script>
   @endpush
