import ApexCharts from "apexcharts";
const chartEl = document.querySelector("#kondisiChart");
let chart;
chart = new ApexCharts(chartEl,chartKondisi);
chart.render();