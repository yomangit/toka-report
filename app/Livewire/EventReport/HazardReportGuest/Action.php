<?php

namespace App\Livewire\EventReport\HazardReportGuest;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ActionHazard;
use App\Models\DocHazPelapor;
use App\Models\EventUserSecurity;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\toModerator as NotificationsToModerator;

class Action extends Component
{
    public $search_report_by = '';
    public $hiddenResponsibility = 'block';
    public $modal = 'modal';
    public $divider, $action_id, $orginal_due_date, $current_step;

    #[Validate]
    public $hazard_id, $responsible_role_id, $reference;
    public $responsibility, $responsibility_name;
    public $followup_action, $actionee_comment, $action_condition, $due_date, $completion_date;

    #[On('modalActionHazardNew')]
    public function modalActionHazardNew()
    {
        $this->openModal();
    }

    public function clickResponsibility()
    {
        $this->hiddenResponsibility = 'block';
    }

    public function reportedBy($id)
    {
        $user = User::find($id);
        $this->responsibility = $id;
        $this->responsibility_name = $user?->lookup_name ?? '';
        $this->hiddenResponsibility = 'hidden';
    }

    public function openModal()
    {
        $this->modal = ' modal-open';
    }

    public function closeModal()
    {
        $this->reset('followup_action', 'actionee_comment', 'action_condition', 'due_date', 'completion_date', 'modal');
    }

    public function render()
    {
        $this->divider = $this->action_id ? "Update Action" : "Add Action";

        return view('livewire.event-report.hazard-report-guest.action', [
            'Report_By' => User::searchNama(trim($this->responsibility_name))->limit(500)->get(),
        ]);
    }

    public function rules()
    {
        return [
            'responsibility_name' => ['nullable'],
            'followup_action'     => ['required'],
            'actionee_comment'    => ['nullable'],
            'action_condition'    => ['nullable'],
            'due_date'            => ['nullable'],
            'completion_date'     => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'followup_action.required' => 'Follow Up Action is required',
        ];
    }

    public function store()
    {
        $this->validate();

        DocHazPelapor::updateOrCreate(
            ['id' => $this->action_id],
            [
                'hazard_id'        => $this->hazard_id,
                'followup_action'  => $this->followup_action,
                'actionee_comment' => $this->actionee_comment,
                'action_condition' => $this->action_condition,
                'responsibility'   => $this->responsibility,
                'due_date'         => $this->due_date,
                'completion_date'  => $this->completion_date,
                'user_id', Auth::id(),
                'is_temporary' => true,
            ]
        );

        $this->sendAlert($this->action_id ? 'Data has been updated' : 'Data added Successfully!!');

        if (!$this->action_id) {
            $this->reset('followup_action', 'actionee_comment', 'action_condition', 'due_date', 'completion_date', 'responsibility_name');
        } else {
            $this->reset('modal');
        }

        // Kirim notifikasi ke Moderator jika role ID = 1
        if ($this->responsible_role_id == 1) {
            $this->notifyModerators();
        }
        $this->dispatch('actionHazard_created');
    }
    protected function sendAlert($message)
    {
        $this->dispatch('alert', [
            'text'            => $message,
            'duration'        => 3000,
            'destination'     => '/contact',
            'newWindow'       => true,
            'close'           => true,
            'backgroundColor' => 'linear-gradient(to right, #00b09b, #96c93d)',
        ]);
    }
    protected function notifyModerators()
    {
        $moderatorIds = EventUserSecurity::where('responsible_role_id', $this->responsible_role_id)
            ->where('user_id', '!=', Auth::id())
            ->pluck('user_id')
            ->toArray();

        $moderators = User::whereIn('id', $moderatorIds)->get();

        foreach ($moderators as $user) {
            $offerData = [
                'greeting'   => 'Hi ' . $user->lookup_name,
                'subject'    => 'Hazard Report ' . $this->reference,
                'line'       => $this->responsibility_name . ' has updated a hazard report action, please review',
                'line2'      => 'Please review this report',
                'line3'      => 'Thank you',
                'actionUrl'  => url("/eventReport/hazardReportDetail/{$this->hazard_id}"),
            ];

            Notification::send($user, new NotificationsToModerator($offerData));
        }
    }
}
