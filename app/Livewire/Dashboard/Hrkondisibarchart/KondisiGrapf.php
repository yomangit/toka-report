<?php

namespace App\Livewire\Dashboard\Hrkondisibarchart;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\HazardReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KondisiGrapf extends Component
{
    public $data = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->data = HazardReport::selectRaw('kondisitidakamen_id, COUNT(*) as jumlah')
            ->groupBy('kondisitidakamen_id')
            ->get()
            ->map(fn($item) => [
                'label' => 'Kondisi ' . $item->kondisitidakamen_id,
                'count' => $item->jumlah
            ])
            ->toArray();

        // Kirim event ke JS (Livewire 3)
        $this->dispatch('updateChart', data: $this->data);
    }

    public function render()
    {
        

        return view('livewire.dashboard.hrkondisibarchart.kondisi-grapf');
    }
}
