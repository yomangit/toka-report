<div>
    <div wire:init="loadChartData" wire:poll.3s="loadChartData">
        <div wire:ignore id="kondisiCharts"></div>
    </div>

   @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script type="text/javascript">
        const labels = @json($labels);
        const counts = @json($counts);

        const shortLabels = labels.map(label => label.length > 20 ? label.slice(0, 20) + '…' : label);

        const chartKondisi = {
            chart: {
                type: 'bar'
                , height: 350
            }
            , colors: labels.map((_, i) => {
                const colorList = ['#FF4560', '#008FFB', '#00E396', '#FEB019', '#775DD0', '#FF66C3', '#546E7A', '#26a69a', '#D10CE8'];
                return colorList[i % colorList.length];
            })
            , series: [{
                name: 'Jumlah'
                , data: counts
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
                categories: shortLabels
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
        var kondisiChart = new ApexCharts(
            document.querySelector("#kondisiCharts")
            , chartKondisi
        );
        kondisiChart.render();
        // Livewire event listener for realtime update
        Livewire.on('kondisiChartUpdated', (event) => {
            console.log(event);
            
            const updatedLabels = event.labels;
            const updatedCounts = event.counts;
            const updatedShortLabels = updatedLabels.map(label =>
                label.length > 20 ? label.slice(0, 20) + '…' : label
            );

            kondisiChart.updateOptions({
                xaxis: {
                    categories: updatedShortLabels
                }
                , colors: updatedLabels.map((_, i) => {
                    const colorList = ['#FF4560', '#008FFB', '#00E396', '#FEB019', '#775DD0', '#FF66C3', '#546E7A', '#26a69a', '#D10CE8'];
                    return colorList[i % colorList.length];
                })
            });

            kondisiChart.updateSeries([{
                name: 'Jumlah'
                , data: updatedCounts
            }]);
        });

    </script>
   @endpush


</div>
