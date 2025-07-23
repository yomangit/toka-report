<div>
    <div id="kondisiBarChart"></div>

    <script>

        const labels = {!! json_encode($labels) !!};
        const counts = {!! json_encode($counts) !!};
        window.renderKondisiChart(labels, counts);
    </script>
</div>
