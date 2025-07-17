<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;

class ManageDivisionAccess extends Component
{
    public $users;
    public $editUserId;
    public $editCanView = false;

    public function mount()
    {
        $this->loadUsers();
    }
    public function loadUsers()
    {
        $this->users = User::get();
    }
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
        return view('livewire.admin.manage-division-access')->extends('base.index', ['header' => 'Akeses Divisi', 'title' => 'Akeses Divisi'])->section('content');
    }
}
