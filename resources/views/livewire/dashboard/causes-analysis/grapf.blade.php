<div>
    <div
    id="perbandinganPieChart"
    data-labels='@json(["Kondisi Tidak Aman", "Tindakan Tidak Aman"])'
    data-counts='@json([$totalKondisi ?? 0, $totalTindakan ?? 0])'
    class="w-full h-96"
></div>
</div>
