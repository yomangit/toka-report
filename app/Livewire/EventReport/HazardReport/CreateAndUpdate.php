<?php
namespace App\Livewire\EventReport\HazardReport;

use DateTime;
use App\Models\User;
use Livewire\Component;
use App\Models\Approval;
use App\Models\Division;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\Eventsubtype;
use App\Models\HazardReport;
use Livewire\WithPagination;
use App\Models\LocationEvent;
use Livewire\WithFileUploads;
use App\Models\choseEventType;
use App\Models\WorkflowDetail;
use App\Models\TypeEventReport;
use App\Models\Kondisitidakaman;
use App\Models\EventUserSecurity;
use App\Models\Tindakantidakaman;
use App\Notifications\toModerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Notification;

class CreateAndUpdate extends Component
{
    use WithFileUploads;
    use WithPagination;

    // Basic UI Controls
    public $divider = 'Input Hazard Report';
    public $show = false;
    public $show_immidiate = 'yes';
    public $showLocation = false;

    // Dropdown & Visibility Controls
    public $dropdownLocation = 'dropdown', $hidden = 'block';
    public $dropdownWorkgroup = 'dropdown', $hiddenWorkgroup = 'block';
    public $dropdownReportBy = 'dropdown', $hiddenReportBy = 'block';
    public $dropdownReportTo = 'dropdown', $hiddenReportTo = 'block';

    // Search Fields
    public $search = '';
    public $searchLikelihood = '';
    public $searchConsequence = '';
    public $search_workgroup = '';
    public $divisi_search = '';
    public $search_report_by = '';
    public $search_report_to = '';
    public $location_search = '';

    // IDs and Relational Keys
    public $location_id;
    public $tablerisk_id;
    public $risk_assessment_id;
    public $workflow_detail_id;
    public $workflow_template_id;
    public $division_id;
    public $event_type_id;
    public $sub_event_type_id;
    public $report_by;
    public $report_to;
    public $event_location_id;
    public $site_id;
    public $workgroup_id;
    public $select_divisi;
    public $token;

    // Names and Labels
    public $location_name;
    public $workgroup_name;
    public $report_byName;
    public $report_toName;

    // Other Report Data
    public $reference;
    public $report_by_nolist;
    public $report_to_nolist;
    public $company_involved;
    public $task_being_done;
    public $date;
    public $description;
    public $documentation;
    public $immediate_corrective_action;
    public $suggested_corrective_action;
    public $preliminary_cause;
    public $corrective_action_suggested;

    // Risk Details
    public $TableRisk = [];
    public $RiskAssessment = [];
    public $risk_likelihood_id;
    public $risk_likelihood_notes;
    public $risk_consequence_id;
    public $risk_consequence_doc;
    public $risk_probability_doc;

    // Event Types and Roles
    public $Event_type = [];
    public $EventSubType = [];
    public $ResponsibleRole;

    // Hierarchy Data
    public $parent_Company;
    public $business_unit;
    public $dept;

    // Address & Conditions
    public $alamat;
    public $kondisi_tidak_aman;
    public $tindakan_tidak_aman;
    public $tindakkan_selanjutnya;

    // File Handling
    public $fileUpload;

    // Miscellaneous
    public $data = [];

    // data action
    public function mount()
    {
        $this->token = Str::uuid()->toString();
        if (Auth::check()) {
            $this->report_byName = Auth::user()->lookup_name ?? Auth::user()->name;
            $this->report_by     = Auth::id();
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
            'event_type_id.required'                => 'Kolom wajib diisi',
            'sub_event_type_id.required'            => 'Kolom wajib diisi',
            'report_byName.required'                => 'Kolom wajib diisi',
            'workgroup_name.required'               => 'Kolom wajib diisi',
            'date.required'                         => 'Kolom wajib diisi',
            'site_id.required'                      => 'Kolom wajib diisi',
            'documentation.mimes'                   => 'Hanya format file jpg, jpeg, png, svg, gif, xlsx, pdf, docx yang diizinkan',
            'description.required'                  => 'Kolom wajib diisi',
            'immediate_corrective_action.required'  => 'Kolom wajib diisi',
            'location_name.required'                => 'Kolom wajib diisi',
            'location_id.required'                  => 'Kolom wajib diisi',
            'tindakkan_selanjutnya.required'        => 'Kolom wajib dicentang',
        ];
    }
    #[On('closeAll')]
    public function clearTindakkan_selanjutnya()
    {
        $this->reset('tindakkan_selanjutnya');
    }
    public function reportedBy($id)
    {
        $this->report_by = $id;

        $reportBy = User::find($id);
        if ($reportBy) {
            $this->report_byName = $reportBy->lookup_name;
        }

        $this->report_by_nolist = null;
        $this->hiddenReportBy = 'hidden';
    }

    public function reportedTo($id)
    {
        $this->report_to = $id;

        $reportTo = User::find($id);
        if ($reportTo) {
            $this->report_toName = $reportTo->lookup_name;
        }

        $this->report_to_nolist = null;
        $this->hiddenReportTo = 'hidden';
    }

    public function reportByAndReportTo()
    {
        if (!empty($this->report_by_nolist)) {
            $this->report_by = null;
            $this->report_byName = $this->report_by_nolist;
        }
    }

    public function select_division($id)
    {
        $this->division_id = $id;
        $this->hiddenWorkgroup = 'hidden';
        $this->hiddenReportBy = 'hidden';
    }

    public function clickReportBy()
    {
        $this->dropdownReportBy = 'dropdown dropdown-open dropdown-end';
        $this->hiddenReportBy = 'block';
    }

    public function clickReportTo()
    {
        $this->hiddenReportTo = 'block';
    }

    public function clickWorkgroup()
    {
        $this->dropdownWorkgroup = 'dropdown dropdown-open dropdown-end';
        $this->hiddenWorkgroup = 'block';
    }

    public function changeConditionDivision()
    {
        $this->business_unit = null;
        $this->dept = null;
        $this->select_divisi = null;
        $this->division_id = null;
    }

    public function realTimeFunc()
    {
        // Tampilkan lokasi jika dipilih
        $this->showLocation = !empty($this->location_id);

        // Ambil event_type berdasarkan route
        $routePath = Request::getPathInfo();
        $eventTypeIds = choseEventType::where('route_name', 'LIKE', $routePath)->pluck('event_type_id');

        if ($eventTypeIds->isNotEmpty()) {
            $this->Event_type = TypeEventReport::whereIn('id', $eventTypeIds)->get();
        }

        // Ambil subtype jika event_type_id dipilih
        $this->EventSubType = $this->event_type_id
            ? Eventsubtype::where('event_type_id', $this->event_type_id)->get()
            : [];

        // Ambil ekstensi file dokumentasi
        if ($this->documentation) {
            $this->fileUpload = pathinfo($this->documentation->getClientOriginalName(), PATHINFO_EXTENSION);
        }

        // Tampilkan form jika user adalah superadmin (role_user_permit_id = 1)
        $this->show = Auth::check() && Auth::user()->role_user_permit_id == 1;

        // Proses data divisi
        if ($this->division_id) {
            $divisi = Division::with([
                'DeptByBU.BusinesUnit.Company',
                'DeptByBU.Department',
                'Company',
                'Section'
            ])->find($this->division_id);

            if ($divisi) {
                $this->workgroup_name = $divisi->formatWorkgroupName();
            }
        } else {
            // Jika tidak ada division_id
            $this->divisi_search = Division::with([
                'DeptByBU.BusinesUnit.Company',
                'DeptByBU.Department',
                'Company',
                'Section'
            ])
                ->searchDeptCom(trim($this->workgroup_name))
                ->searchParent(trim($this->parent_Company))
                ->searchBU(trim($this->business_unit))
                ->searchDept(trim($this->dept))
                ->searchComp(trim($this->select_divisi))
                ->orderBy('dept_by_business_unit_id', 'asc')
                ->get();
        }

        // Ambil workflow detail jika ada
        $workflow = $this->workflow_template_id
            ? WorkflowDetail::where('workflow_administration_id', $this->workflow_template_id)->first()
            : null;

        if ($workflow) {
            $this->workflow_detail_id = $workflow->id;
            $this->ResponsibleRole = $workflow->responsible_role_id;
        }
    }
    public function render()
    {
          $this->realTimeFunc();
        $this->ReportByAndReportTo();

        return view('livewire.event-report.hazard-report.create-and-update', [
             'Report_By'  => User::searchNama(trim($this->report_byName))->paginate(100, ['*'], 'Report_By'),
            'Report_To'  => EventUserSecurity::searchName(trim($this->workgroup_name))->where('responsible_role_id', 2)->paginate(100, ['*'], 'Report_To'),
            'Division'   => $this->divisi_search,
            'EventType'  => $this->Event_type,
            'KTA' => Kondisitidakaman::get(),
            'TTA' => Tindakantidakaman::get(),
            'Location'   => LocationEvent::all(),
        ])->extends('base.index', ['header' => 'Hazard Report', 'title' => 'Hazard Report'])->section('content');
    }
 public function store()
    {
        // Format tanggal untuk referensi
        $dateObj = DateTime::createFromFormat('d-m-Y : H:i', $this->date);
        $dateForRef = $dateObj->format('Y/m/d');
        $dateForDB  = $dateObj->format('Y-m-d : H:i');

        // Generate reference number
        $count = HazardReport::count() + 1;
        $refNumber = str_pad($count, 4, '0', STR_PAD_LEFT);
        $this->reference = "HR/TOKA/{$dateForRef}/{$refNumber}";
        // Validasi input
        $this->validate();

        // Upload file
        $file_name = '';
        if ($this->documentation) {
            $file_name = $this->documentation->getClientOriginalName();
            $extension = strtolower($this->documentation->getClientOriginalExtension());
            $this->fileUpload = $extension;

            $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $filePath = "public/documents/hzd/{$file_name}";

            if (in_array($extension, $allowedImageExtensions)) {
                $image = Image::make($this->documentation->get())->encode($extension, 70);
                Storage::put($filePath, $image);
            } else {
                $this->documentation->storeAs('public/documents/hzd', $file_name);
            }
        }

        // Atur tindakan langsung
        if ($this->show_immidiate === 'no') {
            $this->immediate_corrective_action = null;
        }

        // Jika tidak ditindaklanjuti, cari status "closed"
        $closed_by = '';
        if ($this->tindakkan_selanjutnya == 0) {
            $workflow = WorkflowDetail::where('workflow_administration_id', $this->workflow_template_id)
                ->where('name', 'like', '%closed%')
                ->first();

            $this->workflow_detail_id = optional($workflow)->id;
            $closed_by = $this->report_byName;
        }

        // Simpan data ke database
        $fields = [
            'event_type_id'               => $this->event_type_id,
            'sub_event_type_id'           => $this->sub_event_type_id,
            'reference'                   => $this->reference,
            'report_by'                   => $this->report_by,
            'report_to'                   => $this->report_to,
            'division_id'                 => $this->division_id,
            'date'                        => $dateForDB,
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
        if ($this->tindakkan_selanjutnya == 1) {
            $source = Approval::where('new_data->token', $this->token)->get();
            foreach ($source as $approval) {
                $newData = $approval->new_data; // ini adalah array/object yang bisa diubah
                $newData['hazard_id'] = $hazardReport->id; // ubah hazard_id

                $approval->new_data = $newData; // set ulang ke model
                $approval->save();              // simpan ke database
                $approval->approve();
            }
        }
        else{
            Approval::whereIn('new_data->token',$this->token)->delete();
        }
        // Pop-up sukses
        $this->dispatch('alert', [
            'text'            => "Laporan Hazard Anda Sudah Terkirim, Terima kasih sudah melapor!!!",
            'duration'        => 5000,
            'destination'     => '/contact',
            'newWindow'       => true,
            'close'           => true,
            'backgroundColor' => "linear-gradient(to right, #06b6d4, #22c55e)",
        ]);

        $this->dispatch('buttonClicked', ['duration' => 4000]);

        // Kirim notifikasi ke moderator
        $moderatorIds = EventUserSecurity::where('responsible_role_id', $this->ResponsibleRole)
            ->where('type_event_report_id', $this->event_type_id)
            ->when(Auth::check(), fn($q) => $q->where('user_id', '!=', Auth::id()))
            ->pluck('user_id')
            ->toArray();

        $moderators = User::whereIn('id', $moderatorIds)->get();
        $url = $hazardReport->id;

        foreach ($moderators as $user) {
            Notification::send($user, new toModerator([
                'greeting'  => 'Halo ' . $user->lookup_name . ' 👋',
                'subject'   => '⚠️ Laporan Bahaya: ' . $this->reference,
                'line'      => $this->report_byName . ' baru saja mengirimkan laporan bahaya. Mohon untuk segera ditinjau.',
                'line2'     => 'Klik tombol di bawah ini untuk melihat detail laporan dan mengambil tindakan.',
                'line3'     => 'Tetap waspada dan terima kasih atas perhatian Anda 🙏',
                'actionUrl' => url("/eventReport/hazardReportDetail/{$url}"),
            ]));
        }

        // Kirim notifikasi ke report_to
        $reportTo = User::where('id', $this->report_to)->whereNotNull('email')->get();
        if ($reportTo->isNotEmpty()) {
            Notification::send($reportTo, new toModerator([
                'greeting'  => 'Halo ' . $this->report_toName . ' 👋',
                'subject'   => '⚠️ Laporan Bahaya dengan Nomor Referensi: ' . $this->reference,
                'line'      => $this->report_byName . ' telah mengirimkan laporan bahaya kepada Anda. Mohon untuk segera ditinjau.',
                'line2'     => 'Klik tombol di bawah ini untuk melihat detail laporan.',
                'line3'     => 'Terima kasih atas perhatian dan kerjasamanya 🙏',
                'actionUrl' => url("/eventReport/hazardReportDetail/{$url}"),
            ]));
        }

        $this->clearFields();
        // $this->redirectRoute('hazardReportCreate', ['workflow_template_id' => $this->workflow_template_id]);

    }


    public function clearFields()
    {
        $this->report_byName               = "";
        $this->report_toName               = "";
        $this->workgroup_name              = "";
        $this->division_id                 = "";
        $this->date                        = "";
        $this->documentation               = "";
        $this->description                 = "";
        $this->immediate_corrective_action = "";
        $this->location_name               = "";
        $this->location_id                 = "";
        $this->kondisi_tidak_aman          = "";
        $this->tindakan_tidak_aman         = "";
        $this->tindakkan_selanjutnya         = "";
        $this->workgroup_name              = "";
    }
}
