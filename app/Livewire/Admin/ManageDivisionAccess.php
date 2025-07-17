<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\Division;
use Livewire\WithPagination;

class ManageDivisionAccess extends Component
{

    public $search_nama;
    public $selectedUserId;
    public $selectedDivisionIds = [];
    public $showModal = false;
    public $showForm = false;
    public $editMode = false;
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }
    public function openEditModal($userId)
    {
        $user = User::with('divisions')->findOrFail($userId);

        $this->selectedUserId = $user->id;
        $this->selectedDivisionIds = $user->divisions->pluck('id')->toArray();
        $this->showModal = true;
        $this->editMode = true;
    }
    public function getModalTitleProperty()
    {
        return $this->editMode ? 'Edit Akses Divisi' : 'Tambah Akses Divisi';
    }
    public function store()
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'selectedDivisionIds' => 'array',
        ]);

        $user = User::findOrFail($this->selectedUserId);
        $user->divisions()->sync($this->selectedDivisionIds);
        session()->flash('success', 'Akses divisi berhasil disimpan.');
        $this->resetForm();
    }

    public function delete($userId)
    {
        $user = User::findOrFail($userId);
        $user->divisions()->detach();
        session()->flash('success', 'Akses divisi dihapus.');
    }

    public function resetForm()
    {
        $this->reset(['showModal', 'editMode', 'selectedUserId', 'selectedDivisionIds']);
    }
    public function render()
    {
        return view('livewire.admin.manage-division-access', [
            'users' => User::searchNama($this->search_nama)->paginate(20),
            'divisions' => Division::all()
        ])->extends('base.index', ['header' => 'Akeses Divisi', 'title' => 'Akeses Divisi'])->section('content');
    }
    
    public function paginationView()
    {
        return 'pagination.masterpaginate';
    }
}
