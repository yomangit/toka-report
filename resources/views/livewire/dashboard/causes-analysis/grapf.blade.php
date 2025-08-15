<div>
    <x-input-daterange id="rangeDate-pie" placeholder='date-range' />
    <div id="chart-pie" style="height: 400px;"></div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://echarts.apache.org/en/js/vendors/echarts/dist/echarts.min.js"></script>
<script>
    // Date range
    flatpickr("#rangeDate-pie", {
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
                @this.set('tglMulai_pie', tglMulai)
                @this.set('tglAkhir_pie', tglAkhir)
            } else {
                @this.set('tglMulai_pie', null)
                @this.set('tglAkhir_pie', null)
            }
        }
    });
    // end date range
    setInterval(() => Livewire.dispatch('chartUpdatedPie'), 3000);
    const data = JSON.parse('<?php echo $pie ?>');
    const chartData = data.label.map((label, index) => ({
        value: data.count[index]
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

    window.addEventListener('resize', pieChart.resize);

</script>
@endpush
