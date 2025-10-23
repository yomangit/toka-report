<?php

namespace App\Livewire\EventReport\HazardReport;

use DateTime;
use App\Models\User;
use Livewire\Component;
use App\Models\Division;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\Eventsubtype;
use App\Models\HazardReport;
use Livewire\WithPagination;
use App\Models\LocationEvent;
use Livewire\WithFileUploads;
use App\Models\choseEventType;
use App\Models\PersonInCharge;
use App\Models\WorkflowDetail;
use App\Models\TypeEventReport;
use App\Models\Kondisitidakaman;
use App\Models\EventUserSecurity;
use App\Models\Tindakantidakaman;
use Livewire\Attributes\Validate;
use App\Notifications\toModerator;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Auth;
use App\Rules\DateBeforeOrEqualToday;
use Intervention\Image\Facades\Image;
use Cjmellor\Approval\Models\Approval;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
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

    // Pelapor
    #[Validate]
    public $pelapor_id;
    public $searchPelapor = '';
    public $pelapors = [];
    public $showPelaporDropdown = false;
    public $manualPelaporMode = false;
    public $manualPelaporName = '';
    // IDs and Relational Keys
    #[Validate]
    public $location_id;
    public $tablerisk_id;
    public $risk_assessment_id;
    public $workflow_detail_id;
    public $workflow_template_id;
    public $division_id;
    #[Validate]
    public $event_type_id;
    #[Validate]
    public $sub_event_type_id;
    public $report_by;
    public $report_to;
    public $site_id;
    public $workgroup_id;
    public $select_divisi;
    public $token;
    #[Validate]
    public $key_word;
    public $kondisitidakamen_id;
    public $tindakantidakamen_id;

    // Names and Labels
    #[Validate]
    public $location_name;
    #[Validate]
    public $workgroup_name;
    #[Validate]
    public $report_byName;
    #[Validate]
    public $report_toName;

    // Other Report Data
    public $reference;
    public $report_by_nolist;
    public $report_to_nolist;
    public $company_involved;
    public $task_being_done;
    #[Validate]
    public $date;
    #[Validate]
    public $description;
    public $documentation;
    #[Validate]
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
    #[Validate]
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
            $this->searchPelapor = Auth::user()->lookup_name ?? Auth::user()->name;
            $this->pelapor_id     = Auth::id();
        }
    }
    public function rules()
    {
        $baseRules = [
            'workgroup_name'        => ['required'],
            'event_type_id'         => ['required'],
            'sub_event_type_id'     => ['required'],
            'report_byName'         => ['required'],
            'report_toName'         => ['required'],
            'date' => [
                'required',
                new DateBeforeOrEqualToday, // pakai custom rule
            ],
            'documentation'         => 'nullable|mimes:jpg,jpeg,png,svg,gif,xlsx,pdf,docx',
            'description'           => ['required'],
            'location_id'           => ['required'],
            'location_name'         => ['required'],
            'key_word'         => ['required'],
            'tindakkan_selanjutnya' => ['required'],
            'immediate_corrective_action' => ['required'],
            'pelapor_id' => $this->manualPelaporMode ? 'nullable' : 'required',
            'manualPelaporName' => $this->manualPelaporMode ? 'required|string|max:255' : 'nullable',
        ];
        if ($this->key_word === 'kta') {
            $baseRules['kondisitidakamen_id'] = ['required'];
        }
        if ($this->key_word === 'tta') {
            $baseRules['tindakantidakamen_id'] = ['required'];
        }

        return $baseRules;
    }

    public function messages()
    {
        return [
            'event_type_id.required'                => 'Kolom wajib diisi',
            'sub_event_type_id.required'            => 'Kolom wajib diisi',
            'report_byName.required'                => 'Kolom wajib diisi',
            'report_toName.required'                => 'Kolom wajib diisi',
            'workgroup_name.required'               => 'Kolom wajib diisi',
            'date.required'               => 'Kolom wajib diisi',
            'date.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
            'site_id.required'                      => 'Kolom wajib diisi',
            'documentation.mimes'                   => 'Hanya format file jpg, jpeg, png, svg, gif, xlsx, pdf, docx yang diizinkan',
            'description.required'                  => 'Kolom wajib diisi',
            'immediate_corrective_action.required'  => 'Kolom wajib diisi',
            'location_name.required'                => 'Kolom wajib diisi',
            'location_id.required'                  => 'Kolom wajib diisi',
            'kondisitidakamen_id.required'                  => 'Kolom wajib diisi',
            'tindakantidakamen_id.required'                  => 'Kolom wajib diisi',
            'tindakkan_selanjutnya.required'        => 'Kolom wajib dicentang',
            'key_word.required'        => 'Kolom wajib dicentang',
            'pelapor_id.required' => 'Pelapor wajib dipilih.',
        ];
    }
    // Fungsi dari Pelapor
    public function updatedSearchPelapor()
    {
        
        if (strlen($this->searchPelapor) > 2) {
            $this->pelapors = User::searchNama(trim($this->searchPelapor))
                ->orderBy('lookup_name')
                ->limit(10)
                ->get();
            $this->showPelaporDropdown = true;
        } else {
            $this->pelapors = [];
            $this->showPelaporDropdown = false;
        }
        $this->reset('manualPelaporName', 'pelapor_id');
        $this->manualPelaporMode = false;
    }
    public function selectPelapor($id, $name)
    {
        $this->pelapor_id = $id;
        $this->searchPelapor = $name;
        $this->showPelaporDropdown = false;
        $this->manualPelaporMode = false;
        $this->validateOnly('pelapor_id');
    }
    public function enableManualPelapor()
    {
        $this->manualPelaporMode = true;
        $this->manualPelaporName = $this->searchPelapor; // isi default sama dengan isi search
    }
    public function updatedManualPelaporName($value)
    {
        $this->pelapor_id = null;
    }

    public function addPelaporManual()
    {
        $this->searchPelapor = $this->manualPelaporName;
        $this->showPelaporDropdown = false;
        $this->pelapor_id = null;
    }




    // real-time validation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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
    public function updatedKeyWord($value)
    {
        if ($value === 'kta') {
            $this->reset('tindakantidakamen_id');
        } elseif ($value === 'tta') {
            $this->reset('kondisitidakamen_id');
        } else {
            $this->reset('kondisitidakamen_id', 'tindakantidakamen_id');
        }
    }
    public function render()
    {
        $this->realTimeFunc();
        $this->ReportByAndReportTo();

        return view('livewire.event-report.hazard-report.create-and-update', [
            'Report_By'  => User::searchNama(trim($this->report_byName))->paginate(100, ['*'], 'Report_By'),
            'Report_To'  =>  PersonInCharge::where('division_id', $this->division_id)->get(),
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
        if ($this->date) {
            $dateObj = DateTime::createFromFormat('d-m-Y : H:i', $this->date);
            $dateForRef = $dateObj->format('Y/m/d');
            $dateForDB  = $dateObj->format('Y-m-d : H:i');

            // Generate reference number
            $count = HazardReport::count() + 1;
            $refNumber = str_pad($count, 4, '0', STR_PAD_LEFT);
            $this->reference = "LB-{$refNumber}";
        }
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
        $pelaporId = $this->pelapor_id ?: null;

        // Simpan data ke database
        $fields = [
            'event_type_id'               => $this->event_type_id,
            'sub_event_type_id'           => $this->sub_event_type_id,
            'reference'                   => $this->reference,
            'report_by'                   => $pelaporId,
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
            'report_byName'               => $this->pelapor_id ? User::find($this->pelapor_id)?->lookup_name : $this->manualPelaporName,
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
            'key_word'                    => $this->key_word,
            'kondisitidakamen_id'         => $this->kondisitidakamen_id,
            'tindakantidakamen_id'        => $this->tindakantidakamen_id,
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
        } else {
            $exists = Approval::where('new_data->token', $this->token)->exists();
            if ($exists) {
                Approval::whereIn('new_data->token', $this->token)->delete();
            }
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
            $content_moderator = [
                'greeting'  => 'Halo ' . $user->lookup_name . ' 👋',
                'subject'   => '⚠️ Laporan Bahaya: ' . $this->reference,
                'line'      => $this->report_byName . ' baru saja mengirimkan laporan bahaya. Mohon untuk segera ditinjau.',
                'line2'     => 'Klik tombol di bawah ini untuk melihat detail laporan dan mengambil tindakan.',
                'line3'     => 'Tetap waspada dan terima kasih atas perhatian Anda 🙏',
                'actionUrl' => url("/eventReport/hazardReportDetail/{$url}"),
            ];
            Notification::send($user, new toModerator($content_moderator));
            $user_moderator = User::find($user->id);
            $judul =  ['en' => '⚠️ Laporan Bahaya Nomor Referensi: ' . $this->reference];
            $isi = ['en' => $this->report_byName . ' telah mengirimkan laporan bahaya . Mohon untuk segera ditinjau.'];
            $urls = url("/eventReport/hazardReportDetail/{$hazardReport->id}");
            NotificationHelper::sendToUser($user_moderator, $judul, $isi, $urls);
        }

        // Kirim notifikasi ke report_to
        $reportTo = User::where('id', $this->report_to)->whereNotNull('email')->get();
        if ($reportTo->isNotEmpty()) {
            $content = [
                'greeting'  => 'Halo ' . $this->report_toName . ' 👋',
                'subject'   => '⚠️ Laporan Bahaya dengan Nomor Referensi: ' . $this->reference,
                'line'      => $this->report_byName . ' telah mengirimkan laporan bahaya kepada Anda. Mohon untuk segera ditinjau.',
                'line2'     => 'Klik tombol di bawah ini untuk melihat detail laporan.',
                'line3'     => 'Terima kasih atas perhatian dan kerjasamanya 🙏',
                'actionUrl' => url("/eventReport/hazardReportDetail/{$url}"),
            ];
            Notification::send($reportTo, new toModerator($content));

            $user_os = User::find($this->report_to);
            $judul =  ['en' => '⚠️ Laporan Bahaya Nomor Referensi: ' . $this->reference];
            $isi = ['en' => $this->report_byName . ' telah mengirimkan laporan bahaya kepada Anda. Mohon untuk segera ditinjau.'];
            $urls = url("/eventReport/hazardReportDetail/{$hazardReport->id}");
            NotificationHelper::sendToUser($user_os, $judul, $isi, $urls);
        }
        $this->dispatch('hazardChartShouldRefresh');
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
