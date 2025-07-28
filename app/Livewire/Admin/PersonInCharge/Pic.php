<?php

namespace App\Livewire\Admin\PersonInCharge;

use App\Models\User;
use Livewire\Component;
use App\Models\Division;
use Illuminate\Database\Eloquent\Collection;

class Pic extends Component
{
    public string $searchDivisionQuery = '';
    public bool $showDivisionDropdown = false;
    public Collection $divisionSearchResults;
    public ?int $divisionId = null;
    public array $selectedUsers = [];
    public bool $editMode = false;

    public function mount()
    {
        $this->resetForm();
         $this->divisionSearchResults = collect();
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
    public function resetForm()
    {
        $this->divisionId = 0; // Biar tetap masuk kondisi modal
        $this->selectedUsers = [];
        $this->editMode = false;
    }

    public function edit(int $divisionId)
    {
        $division = Division::findOrFail($divisionId);
        $this->divisionId = $divisionId;
        $this->selectedUsers = $division->users_pic->pluck('id')->toArray();
        $this->editMode = true;
    }

    public function save()
    {
        $this->validate([
            'divisionId' => 'required|exists:divisions,id',
            'selectedUsers' => 'array',
        ]);

        $division = Division::findOrFail($this->divisionId);
        $division->users_pic()->sync($this->selectedUsers);

        $this->dispatch('saved');
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.admin.person-in-charge.pic', [
            'divisions' => Division::all(),
            'users' => User::all(),
            'specialAccessList' => Division::with('users_pic')->get()
        ])->extends('base.index', ['header' => 'Akeses PIC', 'title' => 'Akeses PIC'])->section('content');
    }
}
