<?php

namespace App\Livewire\Dashboard\HazardGrap;

use Livewire\Component;
use App\Models\HazardReport;
use Illuminate\Support\Facades\DB;

class HrGrapf extends Component
{
    public $divisionLabels = [];
public $divisionCounts = [];

public function mount()
{
    $reports = HazardReport::select('division_id', DB::raw('count(*) as total'))
        ->with('division')
        ->groupBy('division_id')
        ->get();

    $this->divisionLabels = $reports->map(fn($r) => optional($r->division)?->formatWorkgroupName() ?? 'Unknown')->toArray();
    $this->divisionCounts = $reports->pluck('total')->toArray();
}
    public function render()
    {
        return view('livewire.dashboard.hazard-grap.hr-grapf')->extends('base.index', ['header' => 'Dashboard Hazard', 'title' => 'Dashboard Hazard'])->section('content');
    }
}
