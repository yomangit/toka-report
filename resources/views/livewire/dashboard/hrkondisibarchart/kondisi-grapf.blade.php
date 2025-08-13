<div wire:init="loadChartData">
    <x-input-daterange id="rangeDate" placeholder="date-range" />
    <div wire:ignore id="kondisiCharts"></div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // === DATE RANGE PICKER ===
    flatpickr("#rangeDate", {
        mode: 'range',
        dateFormat: "d-m-Y",
        onChange: function(dates) {
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

    // === APEXCHARTS INITIAL DATA ===
    const initialLabels = @json($labels);
    const initialCounts = @json($counts);

    function shortenLabels(labels) {
        return labels.map(label =>
            label.length > 20 ? label.slice(0, 20) + '…' : label
        );
    }

    function generateColors(length) {
        const colorList = ['#FF4560', '#008FFB', '#00E396', '#FEB019', '#775DD0', '#FF66C3', '#546E7A', '#26a69a', '#D10CE8'];
        return Array.from({ length }, (_, i) => colorList[i % colorList.length]);
    }

    const chartKondisi = {
        chart: { type: 'bar', height: 350 },
        series: [{ name: 'Jumlah', data: initialCounts }],
        colors: generateColors(initialLabels.length),
        title: {
            text: 'Kondisi Tidak Aman',
            align: 'center',
            style: { fontSize: '12px', fontWeight: 'bold', color: '#fb7185' }
        },
        xaxis: {
            categories: shortenLabels(initialLabels),
            labels: { rotate: -45, style: { fontSize: '09px' } }
        },
        plotOptions: { bar: { borderRadius: 4, distributed: true } },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light', type: 'vertical', shadeIntensity: 0.25,
                inverseColors: true, opacityFrom: 0.9, opacityTo: 1,
                stops: [50, 100]
            }
        }
    };

    const kondisiChart = new ApexCharts(document.querySelector("#kondisiCharts"), chartKondisi);
    kondisiChart.render();

    // === LISTEN FOR LIVEWIRE EVENT TO UPDATE CHART ===
    document.addEventListener('livewire:kondisiChartUpdated', function (event) {
        const newData = event.detail;
        const newLabels = newData.map(item => item.label);
        const newCounts = newData.map(item => item.count);

        kondisiChart.updateOptions({
            xaxis: { categories: shortenLabels(newLabels) },
            colors: generateColors(newLabels.length)
        });

        kondisiChart.updateSeries([{ name: 'Jumlah', data: newCounts }]);
    });
</script>
@endpush
