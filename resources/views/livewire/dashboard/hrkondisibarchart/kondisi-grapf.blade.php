   <div>
       <x-notification />
       <x-input-daterange id="tanggal_range" placeholder='date-range' />

       {{-- Statistik Ringkas --}}
       <div class="w-full mt-4 shadow stats stats-vertical lg:stats-horizontal">

           {{-- Total Laporan --}}
           <div class="stat">
               <div class="stat-figure text-primary">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-bar-big-icon lucide-chart-bar-big">
                       <path d="M3 3v16a2 2 0 0 0 2 2h16" />
                       <rect x="7" y="13" width="9" height="4" rx="1" />
                       <rect x="7" y="5" width="12" height="4" rx="1" />
                   </svg>
               </div>
               <div class="stat-title">Total Laporan</div>
               <div class="stat-value text-primary">{{ $total_laporan }}</div>
               <div class="stat-desc">Semua laporan hazard</div>
           </div>

           {{-- Sedang Diproses --}}
           <div class="stat">
               <div class="stat-figure text-secondary">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wrench-icon lucide-wrench">
                       <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z" />
                   </svg>
               </div>
               <div class="stat-title">Sedang Diproses</div>
               <div class="stat-value text-secondary">
                   {{ $hazardByStatus['In Progress'] ?? 0 }}
               </div>
               <div class="stat-desc">Laporan aktif</div>
           </div>

           {{-- Submitted --}}
           <div class="stat">
               <div class="stat-figure text-info">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hourglass-icon lucide-hourglass">
                       <path d="M5 22h14" />
                       <path d="M5 2h14" />
                       <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22" />
                       <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2" />
                   </svg>
               </div>
               <div class="stat-title">Submitted</div>
               <div class="stat-value text-info">
                   {{ $hazardByStatus['Submitted'] ?? 0 }}
               </div>
               <div class="stat-desc">Menunggu diproses</div>
           </div>

           {{-- Closed --}}
           <div class="stat">
               <div class="stat-figure text-success">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-check-icon lucide-book-check">
                       <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" />
                       <path d="m9 9.5 2 2 4-4" />
                   </svg>
               </div>
               <div class="stat-title">Selesai</div>
               <div class="stat-value text-success">
                   {{ $hazardByStatus['Closed'] ?? 0 }}
               </div>
               <div class="stat-desc">Laporan selesai</div>
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
       // 🎨 Fungsi untuk menghasilkan warna berbeda-beda otomatis
       function generateColor(index, total) {
           // Gunakan lingkaran warna (HSL)
           const hue = (index * (360 / total)) % 360; // bagi rata keliling 360°
           return `hsl(${hue}, 65%, 55%)`; // saturasi & lightness agar tetap cerah
       }
       // Buat array warna sesuai jumlah data
       const warnaOtomatisdivisi = divisi.count.map(() => getRandomColor());
       const warnaOtomatis = data.count.map(() => getRandomColor());
       const warnaOtomatistta = data_tta.count.map(() => getRandomColor());
       const warnaOtomatispie = data_pie.count.map(() => getRandomColor());

       //     ====== DIVISI ======
       var dom_divisi = document.getElementById('chart-divisi');
       var myChart_divisi = echarts.init(dom_divisi);
       var option_divisi;
       option_divisi = {
           title: {
               text: 'World Population'
           }
           , tooltip: {
               trigger: 'axis'
               , axisPointer: {
                   type: 'shadow'
               }
           }
           , legend: {}
           , xAxis: {
               type: 'value'
               , boundaryGap: [0, 0.01]
           }
           , yAxis: {
               type: 'category'
               , data: divisi.label
               , axisLabel: {
                   interval: 0
                   , fontSize: 9
                   , formatter: function(value) {
                       // daftar mapping nama panjang -> singkatan
                       const mapping = {
                           "Contractor-PT. Samudera Mulia Abadi": "SMA"
                           , "Contractor-PT. Geopersada Mulia Abadi": "GMA"
                           , "Contractor-PT. Karya Utama Service": "KUS"
                           , "Contractor-PT. Manado Karya Anugerah": "MKA"
                           , "Contractor-PT. Macmahon Indonesia": "Macmahon"
                           , "Contractor-PT. Tou Maesa Sejahtera": "TMS"
                           , "Contractor-PT. PSI Drilling Service": "PSI"
                           , "Contractor-PT. Mining Technical Service": "MTS"
                           , "Contractor-PT. Mining Tech Service": "MTS"
                           , "Contractor-PT. Mandara Fasilitas Indonesia": "MFI"
                           // tambahkan lainnya di sini
                       };

                       // cari dan ganti substring yang cocok
                       for (let key in mapping) {
                           if (value.includes(key)) {
                               value = value.replace(key, mapping[key]);
                           }
                       }

                       // opsional: batasi panjang total jika masih kepanjangan
                       const maxLength = 30;
                       if (value.length > maxLength) {
                           value = value.substring(0, maxLength) + '...';
                       }

                       return value;
                   }
               }
           }

           , series: [{
                   name: divisi.year
                   , type: 'bar'
                   , data: divisi.count
                   , itemStyle: {
                       color: function(params) {
                           // Gunakan warna dinamis berdasarkan posisi bar
                           return generateColor(params.dataIndex, divisi.count.length);
                       }
                       , borderRadius: [0, 6, 6, 0]
                   }
               }

           ]
       };
       myChart_divisi.setOption(option_divisi);

       Livewire.on('berhasilUpdateDivisi', event => {
           let payload_divisi = JSON.parse(event); // ini parse JSON dari PHP
           myChart_divisi.setOption({
               yAxis: {
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
