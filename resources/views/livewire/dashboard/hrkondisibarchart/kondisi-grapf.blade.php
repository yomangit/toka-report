<div wire:init="loadChartData" >
    <x-input-daterange id="rangeDate" wire:model.live='rangeDate' placeholder='date-range' />
    <div wire:ignore id="kondisiCharts"></div>
</div>

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#rangeDate", {
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
            }
        }
    });

</script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    Livewire.on('kondisiChartUpdated', (chartData) => {
        // Pastikan data valid
        if (!Array.isArray(chartData) || !Array.isArray(chartData[0])) {
            console.error("Format data chart tidak sesuai:", chartData);
            return;
        }

        const kondisiData = chartData[0];

        const initialLabels = kondisiData.map(item => item.label ?? '');
        const initialCounts = kondisiData.map(item => item.count ?? 0);

        // Kalau chart sudah ada → hapus dulu biar nggak double render
        if (window.kondisiChartInstance) {
            window.kondisiChartInstance.destroy();
        }

        const chartKondisi = {
            chart: {
                type: 'bar',
                height: 350
            },
            series: [{
                name: 'Jumlah',
                data: initialCounts
            }],
            colors: generateColors(initialLabels.length),
            title: {
                text: 'Kondisi Tidak Aman',
                align: 'center',
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold',
                    color: '#fb7185'
                }
            },
            xaxis: {
                categories: shortenLabels(initialLabels),
                labels: {
                    rotate: -45,
                    style: { fontSize: '09px' }
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    distributed: true
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.25,
                    inverseColors: true,
                    opacityFrom: 0.9,
                    opacityTo: 1,
                    stops: [50, 100]
                }
            }
        };

        // Simpan instance chart di window biar bisa di-destroy nanti
        window.kondisiChartInstance = new ApexCharts(
            document.querySelector("#kondisiCharts"),
            chartKondisi
        );
        window.kondisiChartInstance.render();
    });
</script>

@endpush
