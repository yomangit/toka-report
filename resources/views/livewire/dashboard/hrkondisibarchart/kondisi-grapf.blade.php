   <div>
       <x-notification />
       <x-input-daterange id="tanggal_range" placeholder='date-range' />

       <div class="shadow stats">
           <div class="stat">
               <div class="stat-figure text-primary">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                   </svg>
               </div>
               <div class="stat-title">Total Likes</div>
               <div class="stat-value text-primary">25.6K</div>
               <div class="stat-desc">21% more than last month</div>
           </div>

           <div class="stat">
               <div class="stat-figure text-secondary">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                   </svg>
               </div>
               <div class="stat-title">Page Views</div>
               <div class="stat-value text-secondary">2.6M</div>
               <div class="stat-desc">21% more than last month</div>
           </div>

           <div class="stat">
               <div class="stat-figure text-secondary">
                   <div class="avatar online">
                       <div class="w-16 rounded-full">
                           <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                       </div>
                   </div>
               </div>
               <div class="stat-value">86%</div>
               <div class="stat-title">Tasks done</div>
               <div class="stat-desc text-secondary">31 tasks remaining</div>
           </div>
       </div>

       <div class="mt-2 mt-4 card card-border bg-base-300">
           <div wire:ignore id="chart-divisi" style="height: 400px;"></div>
       </div>
       <div class="grid gap-4 mt-4 md:grid-cols-2">
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
       const data_tta = JSON.parse('<?php echo $tindakan ?>');
       const data_pie = JSON.parse('<?php echo $pie ?>');

       function getRandomColor() {
           return '#' + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0');
       }
       // Buat array warna sesuai jumlah data
       const warnaOtomatisdivisi = divisi.count.map(() => getRandomColor());
       const warnaOtomatis = data.count.map(() => getRandomColor());
       const warnaOtomatistta = data_tta.count.map(() => getRandomColor());
       const warnaOtomatispie = data_pie.count.map(() => getRandomColor());

       //     ====== DIVISI ======
       var dom_divisi = document.getElementById('chart-divisi');
       var myChart_divisi = echarts.init(dom_divisi);

       var option_divisi = {
           title: {
               text: 'Laporan Hazard Per Divisi'
               , left: 'center'
           }
           , tooltip: {
               trigger: 'item'
           }
           , legend: {
               selectedMode: true
           }
           , dataZoom: [{
               type: 'slider'
               , show: true
               , xAxisIndex: 0
               , start: 0
               , end: 30 // tampilkan 30% pertama, bisa digeser
           }]
           , xAxis: {
               type: 'category'
               , data: divisi.label
               , axisLabel: {
                   interval: 0
                   , fontSize: 9
                   , formatter: function(value) {
                       // daftar mapping nama panjang ke singkatan
                       const mapping = {
                           "Samudera Mulia Abadi": "SMA"
                           , "Geopersada Mulia Abadi": "GMA"
                           , "Karya Utama Service": "KUS"
                           , "Manado Karya Anugerah": "MKA"
                           , "Macmahon Indonesia": "Macmahon"
                           , "Tou Maesa Sejahtera": "TMS"
                           , "PSI Drilling Service": "PSI"
                           , "Mining Technical Service": "MTS"
                           , "Mining Tech Service": "MTS"
                           , "Mandara Fasilitas Indonesia": "MFI"
                           // tinggal tambah lagi di sini kalau ada
                       };

                       // 1. Hilangkan kata "Contractor"
                       value = value.replace(/Contractor/gi, "").trim();

                       // 2. Loop semua mapping dan ganti
                       Object.keys(mapping).forEach(function(key) {
                           let regex = new RegExp(key, "gi"); // cari case-insensitive
                           value = value.replace(regex, mapping[key]);
                       });

                       // 3. Pecah berdasarkan strip, biar rapi kebawah
                       return value.split(/\s*-\s*/).join("\n");
                   }
               }

           }
           , yAxis: {
               type: 'value'
           }
           , series: [{
               data: divisi.count
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
       myChart_divisi.setOption(option_divisi);

       Livewire.on('berhasilUpdateDivisi', event => {
           let payload_divisi = JSON.parse(event); // ini parse JSON dari PHP
           myChart_divisi.setOption({
               xAxis: {
                   data: payload_divisi.label
               }
               , series: [{
                   data: payload_divisi.count
               }]
           });
       });
       //    ===== KTA =====
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
           , color: warnaOtomatispie
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
                   name: 'Cause Analysis'
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
       const charts = [{
               dom: dom_divisi
               , chart: myChart_divisi
           }
           , {
               dom: dom
               , chart: myChart
           }
           , {
               dom: dom_tta
               , chart: myChart_tta
           }
           , {
               dom: dom_ie
               , chart: pieChart
           }
       , ];

       charts.forEach(item => {
           if (item.dom && item.chart) {
               new ResizeObserver(() => {
                   item.chart.resize();
               }).observe(item.dom);
           }
       });

   </script>
   @endpush
