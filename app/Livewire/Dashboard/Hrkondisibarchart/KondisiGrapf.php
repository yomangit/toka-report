<?php

namespace App\Livewire\Dashboard\Hrkondisibarchart;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\HazardReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KondisiGrapf extends Component
{
    public $labels = [];
    public $counts = [];
    public $kondisi;
    public $tglMulai;
    public $tglAkhir;

    protected $listeners = ['hazardChartShouldRefresh' => 'loadChartData'];
    public function updated($property)
    {
        if (in_array($property, ['tglMulai', 'tglAkhir'])) {
            $this->loadChartData();
        }
    }
    public function mount()
    {
        $user = Auth::user();
        $query = HazardReport::join('kondisitidakamen', 'hazard_reports.kondisitidakamen_id', '=', 'kondisitidakamen.id')
            ->select('kondisitidakamen.name as label', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kondisitidakamen_id')
            ->groupBy('kondisitidakamen.name');

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

        $data = [
            'label' => $reports->pluck('label')->toArray(),
            'count' => $reports->pluck('total')->toArray()
        ];
        $this->kondisi = json_encode($data);
    }
    #[On('chartUpdated')]
    public function loadChartData()
    {
        $user = Auth::user();
        $query = HazardReport::join('kondisitidakamen', 'hazard_reports.kondisitidakamen_id', '=', 'kondisitidakamen.id')
            ->select('kondisitidakamen.name as label', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kondisitidakamen_id')
            ->groupBy('kondisitidakamen.name');

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

        $data = [
            'label' => $reports->pluck('label')->toArray(),
            'count' => $reports->pluck('total')->toArray()
        ];
        $this->kondisi = json_encode($data);
        $this->dispatch('berhasilUpdate',$this->kondisi);
    }
    public function render()
    {
        $this->loadChartData();

        return view('livewire.dashboard.hrkondisibarchart.kondisi-grapf');
    }
}
