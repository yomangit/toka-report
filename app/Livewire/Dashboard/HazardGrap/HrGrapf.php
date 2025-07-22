<?php

namespace App\Livewire\Dashboard\HazardGrap;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\HazardReport;
use Illuminate\Support\Facades\DB;

class HrGrapf extends Component
{
    public $divisionLabels = [];
    public $divisionCounts = [];
    public $divisionColors = [];

    public function mount()
    {
        $user = auth()->user();

        $query = HazardReport::select('division_id', DB::raw('count(*) as total'))
            ->with('division')
            ->groupBy('division_id');
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
        $this->divisionLabels = $reports->map(fn($r) => optional($r->division)?->formatWorkgroupName() ?? 'Unknown')->toArray();
        $this->divisionCounts = $reports->pluck('total')->toArray();
        // Fungsi untuk generate warna berdasarkan nama
        $stringToColor = function ($string) {
            $hash = crc32($string);
            return sprintf("#%06X", $hash & 0xFFFFFF);
        };

        $this->divisionColors = array_map(function ($label) use ($stringToColor) {
            return $stringToColor($label);
        }, $this->divisionLabels);
    }
    #[On('hazardChartShouldRefresh')]
   public function refreshDivisionChart()
{
    $user = auth()->user();

    $query = HazardReport::select('division_id', DB::raw('count(*) as total'))
        ->with('division')
        ->groupBy('division_id');

    if ($user->hasRolePermit('administration')) {
        $reports = $query->get();
    } elseif ($user->hasRolePermit('auth') && $user->divisions()->exists()) {
        $divisionIds = $user->divisions->pluck('id')->toArray();
        $reports = $query->whereIn('division_id', $divisionIds)->get();
    } else {
        $reports = collect();
    }

    $divisionLabels = $reports->map(fn($r) => optional($r->division)?->formatWorkgroupName() ?? 'Unknown')->toArray();
    $divisionCounts = $reports->pluck('total')->toArray();

    $stringToColor = fn($string) => sprintf("#%06X", crc32($string) & 0xFFFFFF);
    $divisionColors = array_map($stringToColor, $divisionLabels);

    // Kirim data ke JavaScript (frontend)
    $this->dispatchBrowserEvent('update-division-chart', [
        'labels' => $divisionLabels,
        'series' => $divisionCounts,
        'colors' => $divisionColors,
    ]);
}


    public function render()
    {
        return view('livewire.dashboard.hazard-grap.hr-grapf')->extends('base.index', ['header' => 'Dashboard Hazard', 'title' => 'Dashboard Hazard'])->section('content');
    }
}
