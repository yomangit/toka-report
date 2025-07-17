<?php

namespace App\Livewire\Dashboard\HazardGrap;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\HazardReport;
use Illuminate\Support\Facades\DB;

class HrGrapf extends Component
{
    public $divisionLabels = [];
    public $divisionCounts = [];
    public $divisionColors = [];

    public function mount()
    {
        $reports = HazardReport::select('division_id', DB::raw('count(*) as total'))
            ->with('division')
            ->groupBy('division_id')
            ->get();

        $this->divisionLabels = $reports->map(fn($r) => optional($r->division)?->formatWorkgroupName() ?? 'Unknown')->toArray();
        $this->divisionCounts = $reports->pluck('total')->toArray();
        // Fungsi untuk generate warna berdasarkan nama
        $stringToColor = function ($string) {
            $hash = crc32($string);
            return sprintf("#%06X", $hash & 0xFFFFFF);
        };

        $this->divisionColors = array_map(function ($label) use ($stringToColor) {
            return $stringToColor($label);
        }, $this->divisionLabels);
    }
    #[On('hazardChartShouldRefresh')]
    public function refreshChart()
    {
        $reports = HazardReport::select('division_id', DB::raw('count(*) as total'))
            ->with('division')
            ->groupBy('division_id')
            ->get();

        $this->divisionLabels = $reports->map(fn($r) => optional($r->division)?->formatWorkgroupName() ?? 'Unknown')->toArray();
        $this->divisionCounts = $reports->pluck('total')->toArray();

        $stringToColor = function ($string) {
            $hash = crc32($string);
            return sprintf("#%06X", $hash & 0xFFFFFF);
        };

        $this->divisionColors = array_map(fn($label) => $stringToColor($label), $this->divisionLabels);

        $this->dispatchBrowserEvent('update-division-chart', [
            'labels' => $this->divisionLabels,
            'counts' => $this->divisionCounts,
            'colors' => $this->divisionColors,
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.hazard-grap.hr-grapf')->extends('base.index', ['header' => 'Dashboard Hazard', 'title' => 'Dashboard Hazard'])->section('content');
    }
}
