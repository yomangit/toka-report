<?php

namespace App\Livewire\Dashboard\Hrkondisibarchart;

use Livewire\Component;
use App\Models\HazardReport;
use Illuminate\Support\Facades\DB;

class KondisiGrapf extends Component
{
    public $labels = [];
    public $counts = [];
    public $kondisi;

    public function mount()
    {
        $this->loadChartData(); // Initial load
    }

    public function loadChartData()
    {
        $user = auth()->user();

        $query = HazardReport::select(
            'kondisitidakamen_id',
            DB::raw('COUNT(*) as total'),
            'kondisiTidakAman.name as kondisi_name'
        )
            ->whereNotNull('kondisitidakamen_id')
            ->join('kondisiTidakAman', 'hazard_reports.kondisitidakamen_id', '=', 'kondisiTidakAman.id')
            ->groupBy('kondisitidakamen_id', 'kondisiTidakAman.name');

        if ($user->hasRolePermit('administration')) {
            $reports = $query->get();
        } elseif ($user->hasRolePermit('auth') && $user->divisions()->exists()) {
            $divisionIds = $user->divisions->pluck('id')->toArray();
            $reports = $query->whereIn('division_id', $divisionIds)->get();
        } else {
            $reports = collect();
        }

        $this->labels = $reports->pluck('kondisi_name')->toArray();
        $this->counts = $reports->pluck('total')->toArray();
        $this->dispatch('kondisiChartUpdated', [
            'labels' => $this->labels,
            'counts' => $this->counts,
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.hrkondisibarchart.kondisi-grapf');
    }
}
