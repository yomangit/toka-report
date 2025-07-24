import ApexCharts from "apexcharts";
const chartEl = document.querySelector("#kondisiChart");
let chart;
chart = new ApexCharts(chartEl,chartKondisi);
chart.render();

window.addEventListener('kondisiChartUpdated', event => {
    const { labels, counts } = event.detail;
    chart.updateOptions({
        series: [{ name: 'Jumlah', data: counts }],
        xaxis: { categories: labels }
    });
});