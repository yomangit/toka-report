<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\Division;
use Livewire\WithPagination;

class ManageDivisionAccess extends Component
{
    use WithPagination;
    public $search_nama;
    public $selectedUserId;
    public $selectedDivisionIds = [];
    public $showModal = false;
    public $showForm = false;
    public $editMode = false;
    public $searchUserQuery = '';
    public $searchResults = [];
    public $showUserDropdown = false;

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
        $this->dispatch('initTomSelect');
    }

    public function updatedSearchUserQuery()
    {
        $this->showUserDropdown = true;
        $this->searchResults = User::where('lookup_name', 'like', '%' . $this->searchUserQuery . '%')
            ->limit(10)
            ->get();
    }

    public function selectUserFromDropdown($userId)
    {
        $user = User::find($userId);

        $this->selectedUserId = $user->id;
        $this->searchUserQuery = $user->lookup_name;
        $this->showUserDropdown = false;
    }

    public function openEditModal($userId)
    {
        $user = User::with('divisions')->findOrFail($userId);

        $this->selectedUserId = $user->id;
        $this->selectedDivisionIds = $user->divisions->pluck('id')->toArray();
        $this->showModal = true;
        $this->editMode = true;
        $this->dispatch('initTomSelect');
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
        $this->sendAlert($this->selectedUserId ? 'Data has been updated' : 'Data added Successfully!!');
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
    public function delete($userId)
    {
        $user = User::findOrFail($userId);
        $user->divisions()->detach();
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
       $this->reset(['showModal', 'editMode', 'selectedUserId', 'selectedDivisionIds', 'searchUserQuery', 'searchResults', 'showUserDropdown']);
    }
    public function render()
    {
        return view('livewire.admin.manage-division-access', [
            'users' => User::searchNama($this->search_nama)->paginate(20),
            'user_select' => User::take(20)->get(),
            'divisions' => Division::all()
        ])->extends('base.index', ['header' => 'Akeses Divisi', 'title' => 'Akeses Divisi'])->section('content');
    }
    public function paginationView()
    {
        return 'pagination.masterpaginate';
    }
}
