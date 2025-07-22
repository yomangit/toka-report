<?php

namespace App\Livewire\Dashboard\Hrkondisibarchart;


use Livewire\Component;
use App\Models\HazardReport;
use Illuminate\Support\Facades\DB;

class KondisiGrapf extends Component
{
    public $labels = [];
    public $counts = [];

    public function mount()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $data = HazardReport::select('kondisitidakamen_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kondisitidakamen_id')
            ->groupBy('kondisitidakamen_id')
            ->with('kondisiTidakAman')
            ->get();

        $this->labels = $data->map(fn($item) => optional($item->kondisiTidakAman)?->nama ?? 'Unknown')->toArray();
        $this->counts = $data->pluck('total')->toArray();
    }
    public function render()
    {
        return view('livewire.dashboard.hrkondisibarchart.kondisi-grapf');
    }
}
