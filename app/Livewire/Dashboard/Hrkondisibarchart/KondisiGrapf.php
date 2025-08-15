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
    public $tindakan;
    public $pie;
    public $rangeDate;
    public $tglMulai;
    public $tglAkhir;


    public function mount()
    {
        $user = Auth::user();
        // Kondisi Tidak Aman
        $query = HazardReport::join('kondisitidakamen', 'hazard_reports.kondisitidakamen_id', '=', 'kondisitidakamen.id')
            ->select('kondisitidakamen.name as label', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kondisitidakamen_id')
            ->groupBy('kondisitidakamen.name');

        if ($this->tglMulai && $this->tglAkhir) {
            $query->whereBetween('date', [array($this->tglMulai), array($this->tglAkhir)]);
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
        // Tindakan Tidak Aman
        $query_tta = HazardReport::join('tindakantidakamen', 'hazard_reports.tindakantidakamen_id', '=', 'tindakantidakamen_id.id')
            ->select('tindakantidakamen.name as label', DB::raw('COUNT(*) as total'))
            ->whereNotNull('tindakantidakamen_id')
            ->groupBy('tindakantidakamen.name');

        if ($this->tglMulai && $this->tglAkhir) {
            $query_tta->whereBetween('date', [array($this->tglMulai), array($this->tglAkhir)]);
        }
        if ($user->hasRolePermit('administration')) {
            $reports_tta = $query_tta->get();
        } elseif ($user->hasRolePermit('auth') && $user->divisions()->exists()) {
            $divisionIds = $user->divisions->pluck('id')->toArray();
            $reports_tta = $query_tta->whereIn('division_id', $divisionIds)->get();
        } else {
            $reports_tta = collect();
        }
        $data_tta = [
            'label' => $reports_tta->pluck('label')->toArray(),
            'count' => $reports_tta->pluck('total')->toArray()
        ];
        $this->tindakan = json_encode($data_tta);

        // Pie chart
        $totalKondisi = HazardReport::whereNotNull('kondisitidakamen_id');
        $totalTindakan = HazardReport::whereNotNull('tindakantidakamen_id');

        if ($this->tglMulai && $this->tglAkhir) {
            $totalKondisi->whereBetween('date', [array($this->tglMulai), array($this->tglAkhir)]);
            $totalTindakan->whereBetween('date', [array($this->tglMulai), array($this->tglAkhir)]);
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
    #[On('chartUpdated')]
    #[On('hazardChartShouldRefresh')]
    public function kondisiTidakAman()
    {
        $user = Auth::user();
        $query = HazardReport::join('kondisitidakamen', 'hazard_reports.kondisitidakamen_id', '=', 'kondisitidakamen.id')
            ->select('kondisitidakamen.name as label', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kondisitidakamen_id')
            ->groupBy('kondisitidakamen.name');

        if ($this->tglMulai && $this->tglAkhir) {
            $query->whereBetween('date', [array($this->tglMulai), array($this->tglAkhir)]);
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
        // Kirim ke JS
        $this->dispatch('berhasilUpdate', $this->kondisi);
    }
    #[On('chartUpdated')]
    #[On('hazardChartShouldRefresh')]
    public function tindakanTidakAman()
    {
        $user = Auth::user();
        $query = HazardReport::join('tindakantidakamen', 'hazard_reports.tindakantidakamen_id', '=', 'tindakantidakamen.id')
            ->select('tindakantidakamen.name as label', DB::raw('COUNT(*) as total'))
            ->whereNotNull('tindakantidakamen_id')
            ->groupBy('tindakantidakamen.name');

        if ($this->tglMulai && $this->tglAkhir) {
            $query->whereBetween('date', [array($this->tglMulai), array($this->tglAkhir)]);
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
        $this->tindakan = json_encode($data);
        // Kirim ke JS
        $this->dispatch('berhasilUpdatetta', $this->tindakan);
    }
    #[On('chartUpdated')]
    #[On('hazardChartShouldRefresh')]
    public function updatePerbandinganData()
    {
        $user = Auth::user();
        $totalKondisi = HazardReport::whereNotNull('kondisitidakamen_id');
        $totalTindakan = HazardReport::whereNotNull('tindakantidakamen_id');

        if ($this->tglMulai && $this->tglAkhir) {
            $totalKondisi->whereBetween('date', [array($this->tglMulai), array($this->tglAkhir)]);
            $totalTindakan->whereBetween('date', [array($this->tglMulai), array($this->tglAkhir)]);
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
        $this->dispatch('berhasilUpdatePie',   $this->pie);
    }
    public function render()
    {
        return view('livewire.dashboard.hrkondisibarchart.kondisi-grapf');
    }
}
