<div>
    <div id="kondisiBarChart" wire:poll.10s></div>

    <script type="module">
        import { renderKondisiBarChart } from '/resources/js/charts/renderKondisiBarChart.js';

        // Ambil data dari Livewire property
        const labels = @json($labels);
        const counts = @json($counts);

        // Delay render untuk pastikan element sudah siap
        setTimeout(() => {
            renderKondisiBarChart(labels, counts);
        }, 300);

        // Optional: event listener dari Livewire untuk update manual
        window.addEventListener('updateKondisiChart', event => {
            renderKondisiBarChart(event.detail.labels, event.detail.counts);
        });
    </script>
</div>
