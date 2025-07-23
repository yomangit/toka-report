<?php

namespace App\Livewire\Dashboard\Hrkondisibarchart;


use Livewire\Component;
use App\Models\HazardReport;
use Illuminate\Support\Facades\DB;

class KondisiGrapf extends Component
{
    public $labels = [];
    public $counts = [];
    protected $listeners = ['refreshKondisiChart' => 'loadChartData'];
    public function mount()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $user = auth()->user();
        $query = HazardReport::select('kondisitidakamen_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kondisitidakamen_id')
            ->groupBy('kondisitidakamen_id')
            ->with('kondisiTidakAman');
        if ($user->hasRolePermit('administration')) {
            // Admin bisa lihat semua laporan
            $reports = $query->get();
        } elseif ($user->hasRolePermit('auth') && $user->divisions()->exists()) {
            // Hanya user yang punya relasi dengan division_user
            $divisionIds = $user->divisions->pluck('id')->toArray();
            $reports = $query->whereIn('division_id', $divisionIds)->get();
        } else {
            // User tanpa relasi division_user tidak bisa lihat laporan
            $reports = collect();
        }
        $labels = $reports->map(fn($item) => optional($item->kondisiTidakAman)?->name ?? 'Unknown')->toArray();
        $counts = $reports->pluck('total')->toArray();

        // Dispatch ke JS
        $this->dispatch('update-kondisi-chart', [
            'labels' => $labels,
            'counts' => $counts,
        ]);

    }
    public function render()
    {
        return view('livewire.dashboard.hrkondisibarchart.kondisi-grapf');
    }
}
