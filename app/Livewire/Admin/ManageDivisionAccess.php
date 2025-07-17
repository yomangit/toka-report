<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class ManageDivisionAccess extends Component
{
    use WithPagination;
    public $users;
    public $editUserId;
    public $editCanView = false;

   
    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        $this->editUserId = $user->id;
        $this->editCanView = $user->can_view_own_division;
    }
    public function updateAccess()
    {
        $user = User::findOrFail($this->editUserId);
        $user->can_view_own_division = $this->editCanView;
        $user->save();

        $this->dispatch('notify', 'Akses divisi berhasil diperbarui');
        $this->loadUsers();
    }
    public function render()
    {
        return view('livewire.admin.manage-division-access',[
           'users' =>User::paginate(20)
        ])->extends('base.index', ['header' => 'Akeses Divisi', 'title' => 'Akeses Divisi'])->section('content');
    }
     public function paginationView()
    {
        return 'pagination.masterpaginate';
    }
}
