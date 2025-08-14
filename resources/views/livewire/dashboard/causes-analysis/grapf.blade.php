<div>
     <x-input-daterange id="rangeDate-pie" placeholder='date-range' />
    <div id="chart-pie"style="height: 400px;"></div>
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
                   @this.set('tglMulai', tglMulai)
                   @this.set('tglAkhir', tglAkhir)
               } else {
                   @this.set('tglMulai', null)
                   @this.set('tglAkhir', null)
               }
           }
       });
       // end date range
    var dom = document.getElementById('chart-pie');
    var myChart = echarts.init(dom, null, {
        renderer: 'canvas'
        , useDirtyRect: false
    });
    var app = {};


    var option;

    option = {
        title: {
            text: 'Referer of a Website'
            , subtext: 'Fake Data'
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
            , data: [{
                    value: 1048
                    , name: 'Search Engine'
                }
                , {
                    value: 735
                    , name: 'Direct'
                }
                , {
                    value: 580
                    , name: 'Email'
                }
                , {
                    value: 484
                    , name: 'Union Ads'
                }
                , {
                    value: 300
                    , name: 'Video Ads'
                }
            ]
            , emphasis: {
                itemStyle: {
                    shadowBlur: 10
                    , shadowOffsetX: 0
                    , shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }]
    };

    if (option && typeof option === 'object') {
        myChart.setOption(option);
    }

    window.addEventListener('resize', myChart.resize);

</script>
@endpush
