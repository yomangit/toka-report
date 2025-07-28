<?php

namespace App\Livewire\Admin\PersonInCharge;

use App\Models\User;
use Livewire\Component;
use App\Models\Division;
use Illuminate\Support\Collection;
use Livewire\WithPagination;

class Pic extends Component
{
    use WithPagination;
    public string $searchDivisionQuery = '';
    public string $search_nama = '';
    public bool $showDivisionDropdown = false;
    public Collection $divisionSearchResults;
    public ?int $divisionId = null;
    public array $selectedUsers = [];
    public $showModal = false;
    public $showForm = false;
    public $editMode = false;
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }
    public function updatedSearchDivisionQuery()
    {
        $this->showDivisionDropdown = true;
        $this->divisionSearchResults = Division::with(['DeptByBU.BusinesUnit.Company', 'DeptByBU.Department', 'Company', 'Section'])->get()
            ->filter(function ($division) {
                return str($division->formatWorkgroupName())->lower()->contains(str($this->searchDivisionQuery)->lower());
            })->values();
    }
    public function selectDivisionFromDropdown($divisionId)
    {
        $this->divisionId = $divisionId;
        $this->searchDivisionQuery = Division::find($divisionId)?->formatWorkgroupName() ?? '';
        $this->showDivisionDropdown = false;
    }

    public function openEditModal($division_Id)
    {
        $divisi = Division::with('users_pic')->findOrFail($division_Id);

        $this->divisionId = $divisi->id;
        $this->selectedUsers = $divisi->users_pic->pluck('id')->toArray();
        $this->showModal = true;
        $this->editMode = true;
        $this->dispatch('initTomSelect');
    }
    public function getModalTitleProperty()
    {
        return $this->editMode ? 'Edit PIC' : 'Tambah PIC';
    }

    public function save()
    {
        $this->validate([
            'divisionId' => 'required|exists:divisions,id',
            'selectedUsers' => 'array',
        ]);
        $division = Division::findOrFail($this->divisionId);
        $division->users_pic()->sync($this->selectedUsers);
        $this->sendAlert($this->divisionId ? 'Data has been updated' : 'Data added Successfully!!');
        $this->dispatch('saved');
        $this->resetForm();
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
    public function delete($division_Id)
    {
        $division = Division::findOrFail($division_Id);
        $division->users_pic()->detach();
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
    }
    public function resetForm()
    {
        $this->reset(['showModal', 'editMode', 'divisionId', 'selectedUsers', 'searchDivisionQuery', 'showDivisionDropdown']);
    }
    public function render()
    {
        $users = $this->divisionId ? User::searchNama(trim($this->search_nama))->whereIn('id', $this->selectedUsers)->take(50)->get() : User::searchNama(trim($this->search_nama))->take(50)->get();
        return view('livewire.admin.person-in-charge.pic', [
            'users' => $users,
            'specialAccessList' => Division::with('users_pic')->paginate(20),
        ])->extends('base.index', ['header' => 'Akeses PIC', 'title' => 'Akeses PIC'])->section('content');
    }
    public function paginationView()
    {
        return 'pagination.masterpaginate';
    }
}
