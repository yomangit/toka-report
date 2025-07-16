<?php

namespace App\Livewire\EventReport\HazardReportGuest;

use DateTime;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\DocHazPelapor;
use App\Models\HazardReport;
use Livewire\WithPagination;

class ActionIndex extends Component
{
    use WithPagination;
    public $search = '';
    public $hazard_id, $task_being_done, $orginal_due_date, $current_step;
    protected $listeners = [
        'DocHazPelapor_created' => 'render',
    ];

    public function render()
    {
        return view('livewire.event-report.hazard-report-guest.action-index', [
            'DocHazPelapor' => DocHazPelapor::withoutGlobalScope('not-approved')
                ->whereNull('approved_at')
                ->paginate(20)
        ]);
    }

    public function updateData($id)
    {
        $this->dispatch('action_hazard_update', $id);
    }
    public function delete($id)
    {
        $deleteFile = DocHazPelapor::whereId($id);
        $this->dispatch(
            'alert',
            [
                'text' => "Deleted Data Successfully!!",
                'duration' => 3000,
                'destination' => '/contact',
                'newWindow' => true,
                'close' => true,
                'backgroundColor' => "linear-gradient(to right, #f97316, #ef4444)",
            ]
        );
        $deleteFile->delete();
    }
}
