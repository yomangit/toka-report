<?php

namespace App\Livewire\Dashboard\CausesAnalysis;

use Livewire\Component;
use App\Models\HazardReport;
use Illuminate\Support\Facades\Auth;

class Grapf extends Component
{
    public $labels = [];
    public $counts = [];
    public $pie;
    public $tglMulai_pie;
    public $tglAkhir_pie;
    protected $listeners = ['refreshPerbandinganChart' => 'updatePerbandinganData'];

    public function mount()
    {
        $this->updatePerbandinganData(); // inisialisasi pertama
    }
    public function updated($property)
    {
        if (in_array($property, ['tglMulai_pie', 'tglAkhir_pie'])) {
            $this->loadChartData();
        }
    }
    public function updatePerbandinganData()
    {
        $user = Auth::user();
        $totalKondisi = HazardReport::whereNotNull('kondisitidakamen_id');
        $totalTindakan = HazardReport::whereNotNull('tindakantidakamen_id');

        if ($this->tglMulai_pie && $this->tglAkhir_pie) {
            $totalKondisi->whereBetween('date', [array($this->tglMulai_pie), array($this->tglAkhir_pie)]);
            $totalTindakan->whereBetween('date', [array($this->tglMulai_pie), array($this->tglAkhir_pie)]);
        }

        if ($user->hasRolePermit('administration')) {
            $kondisi = $totalKondisi->count();
            $tindakan = $totalTindakan->count();
        } elseif ($user->hasRolePermit('auth') && $user->divisions()->exists()) {
            $divisionIds = $user->divisions->pluck('id')->toArray();
            $kondisi = $totalKondisi->whereIn('division_id', $divisionIds)->count();
            $tindakan = $totalTindakan->whereIn('division_id', $divisionIds)->count();
        } else {
            $kondisi = collect();
            $tindakan = collect();
        }
        $data = [
            'label' => ['Kondisi Tidak Aman', 'Tindakan Tidak Aman'],
            'count' => [$kondisi, $tindakan]
        ];
        $this->pie = json_encode($data);
       
    }

    public function render()
    {
        return view('livewire.dashboard.causes-analysis.grapf');
    }
}
