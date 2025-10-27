<?php

namespace App\Livewire\Dashboard\Hrkondisibarchart;

use Carbon\Carbon;
use App\Mail\TestEmail;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\HazardReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class KondisiGrapf extends Component
{
    public $labels = [];
    public $counts = [];
    public $hazardByStatus = [];
    public $divisi;
    public $kondisi;
    public $tindakan;
    public $pie;
    public $total_laporan;
    public $rangeDate;
    public $tglMulai;
    public $tglAkhir;
    public $to;

    public function mount()
    {

        $this->divisiUp();
        $this->kondisiTidakAman();
        $this->tindakanTidakAman();
        $this->updatePerbandinganData();
    }

    #[On('chartUpdated')]
    public function divisiUp()
    {
        $user = auth()->user();
        $this->total_laporan = HazardReport::whereBetween('date', [array($this->tglMulai), array($this->tglAkhir)])->count('date');
        $query = HazardReport::select('division_id', DB::raw('count(*) as total'))->with('division')->groupBy('division_id');
        if ($this->tglMulai && $this->tglAkhir) {
            $query->whereBetween('date', [array($this->tglMulai), array($this->tglAkhir)]);
        }
        if ($user->hasRolePermit('administration')) {
            // Admin bisa lihat semua laporan
            $reports = $query->get();
             $this->total_laporan = $reports->count('total');
            $statuses = ['Submitted', 'In Progress', 'Pending', 'Closed', 'Cancelled'];
            foreach ($statuses as $status) {
                $this->hazardByStatus[$status] = $reports->whereHas('WorkflowDetails.Status', function ($q) use ($status) {
                    $q->where('status_name', $status);
                })->count();
            }
        } elseif ($user->hasRolePermit('auth') && $user->divisions()->exists()) {
            // Hanya user yang punya relasi dengan division_user
            $divisionIds = $user->divisions->pluck('id')->toArray();
            $reports = $query->whereIn('division_id', $divisionIds)->get();
        } else {
            // User tanpa relasi division_user tidak bisa lihat laporan
            $reports = collect();
        }



        $year = Carbon::now()->year;
        $label = $reports->map(fn($r) => optional($r->division)?->formatWorkgroupName() ?? 'Unknown')->toArray();
        $count = $reports->pluck('total')->toArray();
        $divisi = [
            'year' => $year,
            'label' => $label,
            'count' => $count
        ];
        $this->divisi = json_encode($divisi);
        $this->dispatch('berhasilUpdateDivisi', $this->divisi);
    }
    #[On('chartUpdated')]
    public function kondisiTidakAman()
    {
        $user = auth()->user();
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
    public function tindakanTidakAman()
    {
        $user = auth()->user();
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
        $this->dispatch('berhasilUpdate_tta', $this->tindakan);
    }
    #[On('chartUpdated')]
    public function updatePerbandinganData()
    {
        $user = auth()->user();
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
        $this->divisiUp();
        $this->kondisiTidakAman();
        $this->tindakanTidakAman();
        return view('livewire.dashboard.hrkondisibarchart.kondisi-grapf');
    }
}
