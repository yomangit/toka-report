<?php

namespace App\Livewire\Dashboard\Hrkondisibarchart;

use Livewire\Component;
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
        $query = HazardReport::select(
            'kondisitidakamen_id',
            DB::raw('COUNT(*) as total')
        )
            ->join('kondisitidakamen', 'hazard_reports.kondisitidakamen_id', '=', 'kondisitidakamen.id')
            ->whereNotNull('kondisitidakamen_id')
            ->groupBy('kondisitidakamen_id', 'kondisitidakamen.name');

        if ($this->tglMulai && $this->tglAkhir) {
            $query->whereBetween('date', [$this->tglMulai, $this->tglAkhir]);
        }

        if ($user->hasRolePermit('administration')) {
            $reports = $query->get(['kondisitidakamen.name as label', DB::raw('COUNT(*) as total')]);
        } elseif ($user->hasRolePermit('auth') && $user->divisions()->exists()) {
            $divisionIds = $user->divisions->pluck('id')->toArray();
            $reports = $query->whereIn('division_id', $divisionIds)
                ->get(['kondisitidakamen.name as label', DB::raw('COUNT(*) as total')]);
        } else {
            $reports = collect();
        }

        // output array untuk chart
        $data['label'] = $reports->pluck('label');
        $data['count'] = $reports->pluck('total');
        dd($data);
    }
    public function loadChartData()
    {
        $user = Auth::user();

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
            $reports = collect(); // kosong
        }

        $this->labels = $reports->map(fn($item) => optional($item->kondisiTidakAman)?->name ?? 'Unknown')->toArray();
        $this->counts = $reports->pluck('total')->toArray();
        $counts = $this->counts;
        $this->dispatch(
            'kondisiChartUpdated',
            collect($this->labels)->map(fn($label, $index) => [
                'label' => $label,
                'count' => $counts[$index] ?? 0
            ])->values()->all()
        );
    }
    public function render()
    {
        $this->loadChartData();

        return view('livewire.dashboard.hrkondisibarchart.kondisi-grapf');
    }
}
