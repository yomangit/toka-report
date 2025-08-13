<div>
    <x-input-daterange id="rangeDate" placeholder="date-range" />
    <div wire:ignore id="kondisiCharts"></div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Date range picker
    flatpickr("#rangeDate", {
        mode: 'range'
        , dateFormat: "d-m-Y"
        , onChange: function(dates) {
            if (dates.length === 2) {
                let start = dates[0];
                let end = dates[1];

                let tglMulai = start.getFullYear() + '-' +
                    String(start.getMonth() + 1).padStart(2, '0') + '-' +
                    String(start.getDate()).padStart(2, '0');

                let tglAkhir = end.getFullYear() + '-' +
                    String(end.getMonth() + 1).padStart(2, '0') + '-' +
                    String(end.getDate()).padStart(2, '0');

                @this.set('tglMulai', tglMulai);
                @this.set('tglAkhir', tglAkhir);
            }
        }
    });

    function shortenLabels(labels) {
        return labels.map(label =>
            label.length > 20 ? label.slice(0, 20) + '…' : label
        );
    }

    function generateColors(length) {
        const colorList = ['#FF4560', '#008FFB', '#00E396', '#FEB019', '#775DD0', '#FF66C3', '#546E7A', '#26a69a', '#D10CE8'];
        return Array.from({
            length
        }, (_, i) => colorList[i % colorList.length]);
    }

    // Init chart kosong
    const kondisiChart = new ApexCharts(document.querySelector("#kondisiCharts"), {
        chart: {
            type: 'bar'
            , height: 350
        }
        , series: [{
            name: 'Jumlah'
            , data: []
        }]
        , xaxis: {
            categories: []
        }
        , plotOptions: {
            bar: {
                borderRadius: 4
                , distributed: true
            }
        }
    });
    kondisiChart.render();

    // Listener Livewire event
    Livewire.on('kondisiChartUpdated', (newData) => {
        const data = newData; // di Livewire 3 biasanya langsung data array, tapi kadang terbungkus
        if (!Array.isArray(data)) {
            console.warn("Data chart tidak valid:", data);
            return;
        }
        const newLabels = newData.map(item => item.label);
        const newCounts = newData.map(item => item.count);

        kondisiChart.updateOptions({
            xaxis: {
                categories: shortenLabels(newLabels)
            }
            , colors: generateColors(newLabels.length)
        });

        kondisiChart.updateSeries([{
            name: 'Jumlah'
            , data: newCounts
        }]);
    });

</script>
@endpush
