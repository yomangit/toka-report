import ApexCharts from "apexcharts";

var kondisiChart = new ApexCharts(
    document.querySelector("#kondisiCharts"),
    chartKondisi
);
kondisiChart.render();

// Livewire event listener for realtime update
window.addEventListener('livewire:kondisiChartUpdated', (event) => {
    const updatedLabels = event.detail.labels;
    const updatedCounts = event.detail.counts;
    const updatedShortLabels = updatedLabels.map(label => label.length > 20 ? label.slice(0, 20) + '…' : label);

    kondisiChart.updateOptions({
        xaxis: {
            categories: updatedShortLabels
        },
        colors: updatedLabels.map((_, i) => {
            const colorList = ['#FF4560', '#008FFB', '#00E396', '#FEB019', '#775DD0', '#FF66C3', '#546E7A', '#26a69a', '#D10CE8'];
            return colorList[i % colorList.length];
        }),
    });

    kondisiChart.updateSeries([{
        name: 'Jumlah',
        data: updatedCounts
    }]);
});
