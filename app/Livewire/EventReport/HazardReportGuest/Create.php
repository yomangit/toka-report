<?php

namespace App\Livewire\EventReport\HazardReportGuest;

use DateTime;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\{
    User,
    Division,
    Eventsubtype,
    HazardReport,
    LocationEvent,
    choseEventType,
    WorkflowDetail,
    TypeEventReport,
    EventUserSecurity
};
use App\Notifications\toModerator;
use Illuminate\Support\Facades\{
    Auth,
    Notification,
    Storage,
    Request
};
use Intervention\Image\Facades\Image;

class Create extends Component
{
    use WithFileUploads, WithPagination;

    // ====================
    // 🔘 Data Form Utama
    // ====================
    public $location_name, $location_id, $site_id, $company_involved;
    public $event_type_id, $sub_event_type_id, $task_being_done, $description;
    public $immediate_corrective_action, $suggested_corrective_action, $corrective_action_suggested;
    public $documentation, $fileUpload;
    public $date, $reference;

    // ====================
    // 🔘 Data Workgroup
    // ====================
    public $division_id, $workgroup_id, $workgroup_name, $select_divisi;
    public $parent_Company, $business_unit, $dept;

    // ====================
    // 🔘 Data Risiko
    // ====================
    public $risk_likelihood_id, $risk_likelihood_notes;
    public $risk_consequence_id, $risk_consequence_doc, $risk_probability_doc;

    // ====================
    // 🔘 Data Pelapor
    // ====================
    public $report_by, $report_byName, $report_by_nolist;
    public $report_to, $report_toName, $report_to_nolist;

    // ====================
    // 🔘 Validasi & Tampilan
    // ====================
    public $show_immidiate = 'yes';
    public $show = false;
    public $dropdownLocation = 'dropdown', $hidden = 'block';
    public $dropdownWorkgroup = 'dropdown', $hiddenWorkgroup = 'block';
    public $dropdownReportBy = 'dropdown', $hiddenReportBy = 'block';
    public $dropdownReportTo = 'dropdown', $hiddenReportTo = 'block';

    // ====================
    // 🔘 Field Tambahan
    // ====================
    public $alamat, $kondisi_tidak_aman, $tindakan_tidak_aman, $tindakkan_selanjutnya;
    public $workflow_detail_id, $workflow_template_id, $ResponsibleRole;
    public $TableRisk = [], $Event_type = [], $RiskAssessment = [], $EventSubType = [], $data = [];

    // ====================
    // 🔘 Search Input
    // ====================
    public $search = '', $search_workgroup = '', $divisi_search = '', $search_report_by = '', $search_report_to = '', $location_search = '';
    public $searchLikelihood = '', $searchConsequence = '';

    public function mount()
    {
        if (Auth::check()) {
            $this->report_by     = Auth::id();
            $this->report_byName = Auth::user()->lookup_name ?? Auth::user()->name;
        }
    }

    public function rules()
    {
        $baseRules = [
            'workgroup_name'        => ['required'],
            'event_type_id'         => ['required'],
            'sub_event_type_id'     => ['required'],
            'report_byName'         => ['required'],
            'date'                  => ['required'],
            'documentation'         => 'nullable|mimes:jpg,jpeg,png,svg,gif,xlsx,pdf,docx',
            'description'           => ['required'],
            'location_id'           => ['required'],
            'location_name'         => ['required'],
            'tindakkan_selanjutnya' => ['required'],
        ];

        if ($this->show_immidiate === 'yes') {
            $baseRules['immediate_corrective_action'] = ['required'];
        }

        return $baseRules;
    }

    public function messages()
    {
        return [
            'event_type_id.required'               => 'kolom wajib di isi',
            'sub_event_type_id.required'           => 'kolom wajib di isi',
            'report_byName.required'               => 'kolom wajib di isi',
            'workgroup_name.required'              => 'kolom wajib di isi',
            'date.required'                        => 'kolom wajib di isi',
            'documentation.mimes'                  => 'hanya format jpg,jpeg,png,svg,gif,xlsx,pdf,docx yang diperbolehkan',
            'description.required'                 => 'kolom wajib di isi',
            'immediate_corrective_action.required' => 'kolom wajib di isi',
            'location_name.required'               => 'kolom wajib di isi',
            'location_id.required'                 => 'kolom wajib di isi',
            'tindakkan_selanjutnya.required'       => 'kolom wajib di centang',
        ];
    }
    public function reportedBy($id)
    {
        $user = User::find($id);
        $this->report_by = $id;
        $this->report_byName = $user->lookup_name ?? $user->name;
        $this->report_by_nolist = null;
        $this->hiddenReportBy = 'hidden';
    }

    public function reportedTo($id)
    {
        $user = User::find($id);
        $this->report_to = $id;
        $this->report_toName = $user->lookup_name ?? $user->name;
        $this->report_to_nolist = null;
        $this->hiddenReportTo = 'hidden';
    }

    public function select_division($id)
    {
        $this->division_id = $id;
        $this->hiddenWorkgroup = 'hidden';
        $this->hiddenReportBy = 'hidden';
    }

    public function clickReportBy()
    {
        $this->hiddenReportBy = 'block';
    }
    public function clickReportTo()
    {
        $this->hiddenReportTo = 'block';
    }
    public function clickWorkgroup()
    {
        $this->hiddenWorkgroup = 'block';
    }

    public function ReportByAndReportTo()
    {
        if (!empty($this->report_by_nolist)) {
            $this->report_by = null;
            $this->report_byName = $this->report_by_nolist;
        }
    }

    public function changeConditionDivision()
    {
        $this->business_unit = $this->dept = $this->select_divisi = $this->division_id = null;
    }

    public function render()
    {
        $this->ReportByAndReportTo();

        $divisiQuery = Division::with([
            'DeptByBU.BusinesUnit.Company',
            'DeptByBU.Department',
            'Company',
            'Section'
        ])->orderBy('dept_by_business_unit_id', 'asc');

        if ($this->division_id) {
            $divisi = $divisiQuery->find($this->division_id);
            if ($divisi) {
                $this->workgroup_name = implode('-', array_filter([
                    optional($divisi->DeptByBU->BusinesUnit->Company)->name_company,
                    optional($divisi->DeptByBU->Department)->department_name,
                    optional($divisi->Company)->name_company,
                    optional($divisi->Section)->name,
                ]));
            }
        }

        $this->divisi_search = $divisiQuery
            ->searchParent(trim($this->parent_Company))
            ->searchBU(trim($this->business_unit))
            ->searchDept(trim($this->dept))
            ->searchComp(trim($this->select_divisi))
            ->searchDeptCom(trim($this->workgroup_name))
            ->get();

        if ($this->event_type_id) {
            $this->EventSubType = Eventsubtype::where('event_type_id', $this->event_type_id)->get();
        }

        if ($this->documentation) {
            $this->fileUpload = pathinfo($this->documentation->getClientOriginalName(), PATHINFO_EXTENSION);
        }

        $this->show = Auth::check() && Auth::user()->role_user_permit_id === 1;

        $routePath = Request::getPathInfo();
        if (choseEventType::where('route_name', 'LIKE', $routePath)->exists()) {
            $eventTypeIds = choseEventType::where('route_name', 'LIKE', $routePath)->pluck('event_type_id');
            $this->Event_type = TypeEventReport::whereIn('id', $eventTypeIds)->get();
        }

        if ($this->workflow_template_id) {
            $workflow = WorkflowDetail::where('workflow_administration_id', $this->workflow_template_id)->first();
            if ($workflow) {
                $this->workflow_detail_id = $workflow->id;
                $this->ResponsibleRole = $workflow->responsible_role_id;
            }
        }

        return view('livewire.event-report.hazard-report-guest.create', [
            'Report_By' => User::searchNama(trim($this->report_byName))->paginate(100, ['*'], 'Report_By'),
            'Report_To' => User::searchNama(trim($this->report_toName))->paginate(100, ['*'], 'Report_To'),
            'Division'  => $this->divisi_search,
            'EventType' => $this->Event_type,
            'Location'  => LocationEvent::all(),
        ])->extends('base.index', [
            'header' => 'Hazard Report',
            'title'  => 'Hazard Report',
        ])->section('content');
    }
    public function store()
    {
        $tgl = DateTime::createFromFormat('d-m-Y : H:i', $this->date)->format('Y/m/d');
        $referenceHazard = "HR/TOKA/$tgl/";
        $hazardLast = HazardReport::latest()->first();
        $reference = optional($hazardLast)->id ? $hazardLast->id + 1 : 1;
        $this->reference = $referenceHazard . str_pad($reference, 4, "0", STR_PAD_LEFT);

        $this->validate();

        $file_name = '';
        if ($this->documentation) {
            $file_name = $this->documentation->getClientOriginalName();
            $extension = strtolower($this->documentation->getClientOriginalExtension());
            $this->fileUpload = $extension;

            $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($extension, $allowedImageExtensions)) {
                $image = Image::make($this->documentation->get())->encode($extension, 70);
                Storage::put("public/documents/hzd/{$file_name}", $image);
            } else {
                $this->documentation->storeAs('public/documents/hzd', $file_name);
            }
        }

        if ($this->show_immidiate === 'no') {
            $this->immediate_corrective_action = null;
        }

        $closed_by = '';
        if ($this->tindakkan_selanjutnya == 0) {
            $workflow = WorkflowDetail::where('workflow_administration_id', $this->workflow_template_id)
                ->where('name', 'like', '%closed%')
                ->first();
            $this->workflow_detail_id = $workflow->id ?? null;
            $closed_by = $this->report_byName;
        }

        $fields = [
            'event_type_id'               => $this->event_type_id,
            'sub_event_type_id'           => $this->sub_event_type_id,
            'reference'                   => $this->reference,
            'report_by'                   => $this->report_by,
            'report_to'                   => $this->report_to,
            'division_id'                 => $this->division_id,
            'date'                        => DateTime::createFromFormat('d-m-Y : H:i', $this->date)->format('Y-m-d H:i:s'),
            'location_name'               => $this->location_name,
            'event_location_id'           => $this->location_id,
            'site_id'                     => $this->site_id,
            'show_immidiate'              => $this->show_immidiate,
            'kondisi_tidak_aman'          => $this->kondisi_tidak_aman,
            'tindakan_tidak_aman'         => $this->tindakan_tidak_aman,
            'tindakkan_selanjutnya'       => $this->tindakkan_selanjutnya,
            'company_involved'            => $this->company_involved,
            'risk_consequence_id'         => $this->risk_consequence_id,
            'risk_likelihood_id'          => $this->risk_likelihood_id,
            'workgroup_name'              => $this->workgroup_name,
            'report_byName'               => $this->report_byName,
            'report_toName'               => $this->report_toName,
            'task_being_done'             => $this->task_being_done,
            'documentation'               => $file_name,
            'description'                 => $this->description,
            'immediate_corrective_action' => $this->immediate_corrective_action,
            'suggested_corrective_action' => $this->suggested_corrective_action,
            'corrective_action_suggested' => $this->corrective_action_suggested,
            'report_by_nolist'            => $this->report_by_nolist,
            'report_to_nolist'            => $this->report_to_nolist,
            'workflow_detail_id'          => $this->workflow_detail_id,
            'workflow_template_id'        => $this->workflow_template_id,
            'closed_by'                   => $closed_by,
        ];

        $hazardReport = HazardReport::create($fields);

        $this->dispatch('alert', [
            'text'            => "Laporan Hazard Anda Sudah Terkirim, Terima kasih sudah melapor!!!",
            'duration'        => 5000,
            'destination'     => '/contact',
            'newWindow'       => true,
            'close'           => true,
            'backgroundColor' => "linear-gradient(to right, #06b6d4, #22c55e)",
        ]);

        $this->dispatch('buttonClicked', ['duration' => 4000]);

        $url = $hazardReport->id;
        $actionUrl = url("/eventReport/hazardReportDetail/{$url}");

        if ($this->ResponsibleRole == 1) {
            $moderators = User::whereIn('id', function ($query) {
                $query->select('user_id')
                    ->from('event_user_securities')
                    ->where('responsible_role_id', 1)
                    ->where('type_event_report_id', $this->event_type_id);

                if (Auth::check()) {
                    $query->where('user_id', '!=', Auth::id());
                }
            })->whereNotNull('email')->get();

            foreach ($moderators as $moderator) {
                Notification::send($moderator, new toModerator([
                    'greeting'  => 'Halo ' . $moderator->lookup_name . ' 👋',
                    'subject'   => '⚠️ Laporan Bahaya: ' . $this->reference,
                    'line'      => $this->report_byName . ' baru saja mengirimkan laporan bahaya. Mohon untuk segera ditinjau.',
                    'line2'     => 'Klik tombol di bawah ini untuk melihat detail laporan dan mengambil tindakan.',
                    'line3'     => 'Tetap waspada dan terima kasih atas perhatian Anda 🙏',
                    'actionUrl' => $actionUrl,
                ]));
            }
        }

        $report_to = User::where('id', $this->report_to)->whereNotNull('email')->get();
        if ($report_to) {
            Notification::send($report_to, new toModerator([
                'greeting'  => 'Halo ' . $this->report_toName . ' 👋',
                'subject'   => '⚠️ Laporan Bahaya dengan Nomor Referensi: ' . $this->reference,
                'line'      => $this->report_byName . ' telah mengirimkan laporan bahaya kepada Anda. Mohon untuk segera ditinjau.',
                'line2'     => 'Klik tombol di bawah ini untuk melihat detail laporan.',
                'line3'     => 'Terima kasih atas perhatian dan kerjasamanya 🙏',
                'actionUrl' => $actionUrl,
            ]));
        }

        $this->clearFields();
    }
      public function clearFields()
    {
        $this->reset([
            'location_name', 'location_id', 'site_id', 'company_involved',
            'event_type_id', 'sub_event_type_id', 'task_being_done', 'description',
            'immediate_corrective_action', 'suggested_corrective_action', 'corrective_action_suggested',
            'documentation', 'fileUpload', 'date', 'reference', 'division_id',
            'workgroup_name', 'risk_likelihood_id', 'risk_likelihood_notes',
            'risk_consequence_id', 'risk_consequence_doc', 'risk_probability_doc',
            'report_to', 'report_toName', 'report_to_nolist',
            'show_immidiate', 'alamat', 'kondisi_tidak_aman', 'tindakan_tidak_aman',
            'tindakkan_selanjutnya', 'workflow_detail_id',
        ]);
        $this->resetValidation();
    }
}
