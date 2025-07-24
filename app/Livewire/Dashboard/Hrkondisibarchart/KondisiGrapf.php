<?php

namespace App\Livewire\Dashboard\Hrkondisibarchart;

use Livewire\Component;
use App\Models\HazardReport;
use Illuminate\Support\Facades\DB;

class KondisiGrapf extends Component
{
    public $labels = [];
    public $counts = [];

    protected $listeners = ['hazardChartShouldRefresh' => 'loadChartData'];

    public function loadChartData()
    {
        $user = auth()->user();

        $query = HazardReport::select('kondisitidakamen_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kondisitidakamen_id')
            ->groupBy('kondisitidakamen_id')
            ->with('kondisiTidakAman');

        if ($user->hasRolePermit('administration')) {
            $reports = $query->get();
        } elseif ($user->hasRolePermit('auth') && $user->divisions()->exists()) {
            $divisionIds = $user->divisions->pluck('id')->toArray();
            $reports = $query->whereIn('division_id', $divisionIds)->get();
        } else {
            $reports = collect(); // kosong
        }

        $this->labels = $reports->map(fn($item) => optional($item->kondisiTidakAman)?->name ?? 'Unknown')->toArray();
        $this->counts = $reports->pluck('total')->toArray();
        dd($this->counts);
        // Kirim event ke browser jika datanya valid
        if (!empty($this->labels) && !empty($this->counts) && count($this->labels) === count($this->counts)) {
            $this->dispatch('kondisiChartUpdated', [
                'labels' => array_values($this->labels),
                'counts' => array_values($this->counts)
            ]);
        }
    }

    public function render()
    {
        $this->loadChartData();

        return view('livewire.dashboard.hrkondisibarchart.kondisi-grapf', [
            'labels' => $this->labels,
            'counts' => $this->counts
        ]);
    }
}
