<?php

namespace App\Livewire\Admin\PersonInCharge;

use App\Models\User;
use Livewire\Component;
use App\Models\Division;

class Pic extends Component
{
    public ?int $divisionId = null;
    public array $selectedUsers = [];
    public bool $editMode = false;

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->divisionId = null;
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
        ]);
    }
}
