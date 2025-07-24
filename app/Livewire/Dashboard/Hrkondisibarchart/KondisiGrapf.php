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

        $this->labels = $reports->map(fn($item) => optional($item->kondisiTidakAman)?->name ?? 'Unknown')->toArray();
        $this->counts = $reports->pluck('total')->toArray();
        // Pastikan tidak mengirim event kalau kosong
        if (!empty($this->labels) && !empty($this->counts)) {
            $this->dispatch('kondisiChartUpdated', [
                'labels' => $this->labels,
                'counts' => $this->counts
            ]);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.hrkondisibarchart.kondisi-grapf');
    }
}
