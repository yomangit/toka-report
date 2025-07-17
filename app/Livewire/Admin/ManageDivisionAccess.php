<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class ManageDivisionAccess extends Component
{
    use WithPagination;
    public $search_nama;
    public $showEditModal = false;
    public $editUserId;
    public $editCanView;
    public $editUserName;
    public $editUserEmail;
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->editUserId = $user->id;
        $this->editUserName = $user->lookup_name;
        $this->editUserEmail = $user->email;
        $this->editCanView = $user->can_view_own_division;

        $this->showEditModal = true;
    }
    public function updateAccess()
    {
        $user = User::findOrFail($this->editUserId);
        $user->can_view_own_division = $this->editCanView;
        $user->save();
        $this->showEditModal = false;
        session()->flash('success', 'Akses berhasil diperbarui.');
    }
    public function render()
    {
        return view('livewire.admin.manage-division-access', [
            'users' => User::searchNama($this->search_nama)->paginate(20)
        ])->extends('base.index', ['header' => 'Akeses Divisi', 'title' => 'Akeses Divisi'])->section('content');
    }
    public function paginationView()
    {
        return 'pagination.masterpaginate';
    }
}
