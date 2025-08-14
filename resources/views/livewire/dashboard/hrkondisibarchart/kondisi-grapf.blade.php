<div wire:init="loadChartData">
    <x-input-daterange id="rangeDate" placeholder='date-range' />
    <div wire:ignore id="kondisiCharts"></div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Date range
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
    // end date range
    setInterval(() => Livewire.dispatch('chartUpdated'), 3000);
    const data = JSON.parse('<?php echo $kondisi ?>');

    function shortenLabels(labels) {
        return labels.map(label =>
            label.length > 20 ? label.slice(0, 20) + '…' : label
        );
    }
    const chartKondisi = {
        chart: {
            type: 'bar'
            , height: 350
        }
        , series: [{
            name: 'Jumlah'
            , data: data.count
        }]
        , title: {
            text: 'Kondisi Tidak Aman'
            , align: 'center'
            , style: {
                fontSize: '12px'
                , fontWeight: 'bold'
                , color: '#fb7185'
            }
        }
        , xaxis: {
            categories: shortenLabels(data.label)
            , labels: {
                rotate: -45
                , style: {
                    fontSize: '09px'
                }
            }
        }
        , plotOptions: {
            bar: {
                borderRadius: 4
                , distributed: true
            }
        }
        , fill: {
            type: 'gradient'
            , gradient: {
                shade: 'light'
                , type: 'vertical'
                , shadeIntensity: 0.25
                , inverseColors: true
                , opacityFrom: 0.9
                , opacityTo: 1
                , stops: [50, 100]
            }
        }
    };
    const kondisiChart = new ApexCharts(document.querySelector("#kondisiCharts"), chartKondisi);
    kondisiChart.render();
    Livewire.on('berhasilUpdate', jsonString => {
        const apexData = JSON.parse(jsonString);

         const newchartKondisi = {
        chart: {
            type: 'bar'
            , height: 350
        }
        , series: [{
            name: 'Jumlah'
            , data: apexData.count
        }]
        , title: {
            text: 'Kondisi Tidak Aman'
            , align: 'center'
            , style: {
                fontSize: '12px'
                , fontWeight: 'bold'
                , color: '#fb7185'
            }
        }
        , xaxis: {
            categories: shortenLabels(apexData.label)
            , labels: {
                rotate: -45
                , style: {
                    fontSize: '09px'
                }
            }
        }
        , plotOptions: {
            bar: {
                borderRadius: 4
                , distributed: true
            }
        }
        , fill: {
            type: 'gradient'
            , gradient: {
                shade: 'light'
                , type: 'vertical'
                , shadeIntensity: 0.25
                , inverseColors: true
                , opacityFrom: 0.9
                , opacityTo: 1
                , stops: [50, 100]
            }
        }
    };
    const newkondisiChart = new ApexCharts(document.querySelector("#kondisiCharts"), newchartKondisi);
    newkondisiChart.render();
    });

</script>
@endpush
