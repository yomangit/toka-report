<div wire:init="loadChartData" wire:poll.5s="loadChartData">
    @vite(['resources/js/kondisichart.js'])
    <div wire:ignore id="kondisiCharts"></div>
    {{-- <script type="text/javascript">
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
            }],

            title: {
                text: 'Kondisi Tidak Aman'
                , align: 'center'
                , style: {
                    fontSize: '12px'
                    , fontWeight: 'bold'
                    , fontFamily: undefined
                    , color: '#fb7185'
                }
            , }
            , xaxis: {
                categories: shortLabels
                , labels: {
                    rotate: -45
                    , style: {
                        fontSize: '09px' // 👈 Ukuran kecil (ubah sesuai kebutuhan)
                    }
                , }
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

    </script> --}}
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", () => {
            let chart;
            const chartEl = document.querySelector("#kondisiChart");

            function renderChart(labels, counts) {
                const shortLabels = labels.map(label => label.length > 20 ? label.slice(0, 20) + '…' : label);
                const colors = labels.map((_, i) => {
                    const colorList = ['#FF4560', '#008FFB', '#00E396', '#FEB019', '#775DD0', '#FF66C3', '#546E7A', '#26a69a', '#D10CE8'];
                    return colorList[i % colorList.length];
                });

                const options = {
                    chart: {
                        type: 'bar'
                        , height: 350
                    }
                    , colors: colors
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
                                fontSize: '9px'
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

                if (chart) {
                    chart.destroy(); // Hancurkan chart lama sebelum render baru
                }

                chart = new ApexCharts(chartEl, options);
                chart.render();
            }

            // Render pertama kali saat halaman selesai dimuat
            renderChart(@json($labels), @json($counts));

            // Update chart ketika Livewire memproses data baru
            Livewire.hook('message.processed', () => {
                renderChart(@json($labels), @json($counts));
            });
        });

    </script>

</div>
