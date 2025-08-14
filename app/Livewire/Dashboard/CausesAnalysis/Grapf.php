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
    public $tglMulai;
    public $tglAkhir;
    protected $listeners = ['refreshPerbandinganChart' => 'updatePerbandinganData'];

    public function mount()
    {
        $this->updatePerbandinganData(); // inisialisasi pertama
    }
    public function updated($property)
    {
        if (in_array($property, ['tglMulai', 'tglAkhir'])) {
            $this->loadChartData();
        }
    }
    public function updatePerbandinganData()
    {
        $user = Auth::user();
        $totalKondisi = HazardReport::whereNotNull('kondisitidakamen_id');
        $totalTindakan = HazardReport::whereNotNull('tindakantidakamen_id');

        if ($this->tglMulai && $this->tglAkhir) {
            $totalKondisi->whereBetween('date', [$this->tglMulai, $this->tglAkhir]);
            $totalTindakan->whereBetween('date', [$this->tglMulai, $this->tglAkhir]);
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
