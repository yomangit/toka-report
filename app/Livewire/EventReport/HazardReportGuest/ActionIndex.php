<?php

namespace App\Livewire\EventReport\HazardReportGuest;

use App\Models\Approval;
use DateTime;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\HazardReport;
use Livewire\WithPagination;
use App\Models\DocHazPelapor;

class ActionIndex extends Component
{
    use WithPagination;
    public $search = '';
    public $hazard_id, $task_being_done, $tgl, $current_step,$token,$user;
    protected $listeners = [
        'DocHazPelapor_created' => 'render',
    ];
    public function mount($token,$tgl){
        $this->token =$token;
        $this->tgl = $tgl;
       
    }
    public function render()
    {

        return view('livewire.event-report.hazard-report-guest.action-index', [
            'DocHazPelapor' => Approval::where('new_data->token','like','%'. $this->token.'%')->paginate(20)
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
