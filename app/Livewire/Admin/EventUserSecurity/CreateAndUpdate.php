<?php

namespace App\Livewire\Admin\EventUserSecurity;

use App\Models\User;
use App\Models\DeptByBU;
use App\Models\Division;
use App\Models\Workgroup;
use App\Models\BusinesUnit;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\ClassHierarchy;
use App\Models\CompanyCategory;
use App\Models\ResponsibleRole;
use App\Models\TypeEventReport;
use App\Models\EventUserSecurity;
use LivewireUI\Modal\ModalComponent;

class CreateAndUpdate extends ModalComponent
{
    use WithPagination;

    #[Url]
    public $search_people = '';
    public $divider = '', $search_workgroup = '';
    public $responsible_role_id, $workgroup_name, $user_id = [], $workgroup = [];
    public $type_event_report_id, $event_user_security_id, $user_id_update;
    public $division_id, $parent_Company, $business_unit, $dept;

    public function mount(EventUserSecurity $user_security)
    {
        $this->event_user_security_id = $user_security->id;
        $this->responsible_role_id = $user_security->responsible_role_id;
        $this->user_id_update = $user_security->user_id;
        $this->type_event_report_id = $user_security->type_event_report_id;
        $this->workgroup_name = $user_security->name;
        $this->parent_Company = $user_security->company_category_id;
        $this->dept = $user_security->dept_by_business_unit_id;
        $this->business_unit = $user_security->busines_unit_id;
    }
    public function updatedWorkgroup_name() 
    {
          $this->business_unit = $this->dept = $this->division_id =$this->parent_Company = null;
    }
    public function render()
    {
        $this->divider = $this->event_user_security_id ? 'Edit Event User Security' : 'Add Event User Security';
        $this->user_id_update = empty($this->search_people) ? $this->user_id_update : null;

        // Menentukan workgroup_name
        if ($this->parent_Company) {
            $this->workgroup_name = CompanyCategory::find(1)?->name_category_company;
        }

        if ($this->business_unit) {
            $this->workgroup_name = BusinesUnit::with('Company')->find($this->business_unit)?->Company?->name_company;
        }

        if ($this->dept) {
            $deptModel = DeptByBU::with(['Department', 'BusinesUnit.Company'])->find($this->dept);
            if ($deptModel) {
                $this->workgroup_name = $deptModel->BusinesUnit->Company->name_company . '-' . $deptModel->Department->department_name;
            }
        }
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
        }

        return view('livewire.admin.event-user-security.create-and-update', [
            'User' => User::searchId(trim($this->user_id_update))
                ->searchNama(trim($this->search_people))
                ->paginate(100, ['*'], 'User'),
            'ParentCompany' => CompanyCategory::whereId(1)->searchFor(trim($this->workgroup_name))->get(),
            'BusinessUnit' => BusinesUnit::with(['Department', 'Company'])->search(trim($this->workgroup_name))->get(),
            'Department' => DeptByBU::with(['Department', 'BusinesUnit'])->searchDept(trim($this->workgroup_name))->orderBy('busines_unit_id')->get(),
            'Division' => Division::with(['DeptByBU.BusinesUnit.Company', 'DeptByBU.Department', 'Company'])->searchContractor(trim('Contractor'))->searchByFormattedName(trim($this->workgroup_name))->orderBy('dept_by_business_unit_id', 'asc')->get(),
            'ClassHierarchy' => ClassHierarchy::with([
                'Company',
                'BusinesUnit.Company',
                'DeptByBU.BusinesUnit.Company',
                'DeptByBU.Department',
            ])
                ->searchParent(trim($this->parent_Company))
                ->searchBU(trim($this->business_unit))
                ->searchDept(trim($this->dept))
                ->get(),
            'ResponsibleRole' => ResponsibleRole::get(),
            'TypeEventReport' => TypeEventReport::get(),
        ]);
    }

    public function parentCompany($id)
    {
        $this->parent_Company = $id;
        $this->business_unit = $this->dept = $this->division_id = null;
    }

    public function businessUnit($id)
    {
        $this->business_unit = $id;
        $this->parent_Company = $this->dept = $this->division_id = null;
    }

    public function department($id)
    {
        $this->dept = $id;
        $this->parent_Company = $this->business_unit = $this->division_id = null;
    }
    public function division($id)
    {
        $this->division_id = $id;
        $this->parent_Company = $this->business_unit = $this->dept = null;
    }

    public function rules()
    {
        return $this->user_id_update
            ? [
                'responsible_role_id' => ['required'],
                'user_id_update' => ['required'],
                'type_event_report_id' => ['nullable'],
            ]
            : [
                'responsible_role_id' => ['required'],
                'user_id' => ['required'],
                'type_event_report_id' => ['nullable'],
            ];
    }

    public function messages()
    {
        return [
            'responsible_role_id.required' => 'Responsible Role is required',
            'workgroup_name.required' => 'Workgroup Name is required',
            'user_id.required' => 'People Name is required',
            'type_event_report_id.nullable' => 'Type Event Report is required',
        ];
    }

    protected function formData($userId)
    {
        $divisi = is_array($this->division_id) ? (int) $this->division_id[0] : null;

        return [
            'name' => $this->workgroup_name,
            'division_id' => $divisi,
            'company_category_id' => $this->parent_Company,
            'busines_unit_id' => $this->business_unit,
            'dept_by_business_unit_id' => $this->dept,
            'responsible_role_id' => $this->responsible_role_id,
            'user_id' => $userId,
            'type_event_report_id' => $this->type_event_report_id ?: null,
        ];
    }

    public function store()
    {
        $this->validate();

        if ($this->event_user_security_id) {
            EventUserSecurity::updateOrCreate(
                ['id' => $this->event_user_security_id],
                $this->formData($this->user_id_update)
            );
        } else {
            foreach ($this->user_id as $uid) {
                EventUserSecurity::create($this->formData($uid));
            }
        }

        $this->dispatch('alert', [
            'text' => $this->event_user_security_id ? 'Data has been updated' : 'Data added Successfully!!',
            'duration' => 3000,
            'destination' => '/contact',
            'newWindow' => true,
            'close' => true,
            'backgroundColor' => 'linear-gradient(to right, #00b09b, #96c93d)',
        ]);

        if ($this->event_user_security_id) {
            $this->forceClose()->closeModal();
        } else {
            $this->reset([
                'workgroup_name',
                'business_unit',
                'dept',
                'responsible_role_id',
                'user_id',
                'type_event_report_id',
            ]);
        }

        $this->dispatch('event_user_security_created');
    }

    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }
}
