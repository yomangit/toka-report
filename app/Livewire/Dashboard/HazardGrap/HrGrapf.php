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
        $this->updateDivisionChartData(); // Panggil saat pertama kali render
    }
    public function updateDivisionChartData()
   {
        $user = auth()->user();

        $query = HazardReport::select('division_id', DB::raw('count(*) as total'))
            ->with('division')
            ->groupBy('division_id');

        if ($user->hasRolePermit('administration')) {
            $reports = $query->get();
        } elseif ($user->hasRolePermit('auth') && $user->divisions()->exists()) {
            $divisionIds = $user->divisions->pluck('id')->toArray();
            $reports = $query->whereIn('division_id', $divisionIds)->get();
        } else {
            $reports = collect();
        }

        $this->divisionLabels = $reports->map(fn($r) => optional($r->division)?->formatWorkgroupName() ?? 'Unknown')->toArray();
        $this->divisionCounts = $reports->pluck('total')->toArray();

        $stringToColor = fn($string) => sprintf("#%06X", crc32($string) & 0xFFFFFF);
        $this->divisionColors = array_map($stringToColor, $this->divisionLabels);

        // Trigger event ke frontend
        $this->dispatch('update-division-chart', [
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
