<?php

namespace App\Livewire\Dashboard\Hrkondisibarchart;

use Livewire\Component;
use App\Models\HazardReport;
use Illuminate\Support\Facades\DB;

class KondisiGrapf extends Component
{
    public $tglMulai;
    public $tglAkhir;

    public function updated($property)
    {
        if (in_array($property, ['tglMulai', 'tglAkhir'])) {
            $this->loadChartData();
        }
    }

    public function loadChartData()
    {
        $user = auth()->user();

        $query = HazardReport::select('kondisitidakamen_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kondisitidakamen_id')
            ->groupBy('kondisitidakamen_id')
            ->with('kondisiTidakAman');

        if ($this->tglMulai && $this->tglAkhir) {
            $query->whereBetween('date', [$this->tglMulai, $this->tglAkhir]);
        }

        if ($user->hasRolePermit('administration')) {
            $reports = $query->get();
        } elseif ($user->hasRolePermit('auth') && $user->divisions()->exists()) {
            $divisionIds = $user->divisions->pluck('id')->toArray();
            $reports = $query->whereIn('division_id', $divisionIds)->get();
        } else {
            $reports = collect();
        }

        $labels = $reports->map(fn($item) => optional($item->kondisiTidakAman)?->name ?? 'Unknown')->toArray();
        $counts = $reports->pluck('total')->toArray();

        // Kirim event ke browser
        $this->dispatch('kondisiChartUpdated', collect($labels)->map(function ($label, $index) use ($counts) {
            return [
                'label' => $label,
                'count' => $counts[$index] ?? 0
            ];
        })->values()->toArray());
    }
    public $message = "Halo dari Livewire";

    public function sendToJs()
    {
        $this->dispatch('messageFromLivewire', $this->message);
    }
    public function render()
    {
        return view('livewire.dashboard.hrkondisibarchart.kondisi-grapf');
    }
}
