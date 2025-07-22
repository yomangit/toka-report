<?php

namespace App\Livewire\Dashboard\CausesAnalysis;

use Livewire\Component;
use App\Models\HazardReport;

class Grapf extends Component
{
    protected $listeners = ['refreshPerbandinganChart' => 'updatePerbandinganData'];

    public function mount()
    {
        $this->updatePerbandinganData(); // inisialisasi pertama
    }

    public function updatePerbandinganData()
    {
        $totalKondisi = HazardReport::whereNotNull('kondisitidakamen_id')->count();
        $totalTindakan = HazardReport::whereNotNull('tindakantidakamen_id')->count();

        $this->dispatch('update-perbandingan-chart', [
            'labels' => ['Kondisi Tidak Aman', 'Tindakan Tidak Aman'],
            'counts' => [$totalKondisi, $totalTindakan],
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.causes-analysis.grapf');
    }
}
