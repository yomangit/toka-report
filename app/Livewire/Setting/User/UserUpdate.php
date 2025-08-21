<?php

namespace App\Livewire\Setting\User;

use App\Models\Department;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class UserUpdate extends Component
{
    public User $user;
    public $name;
    public $department_id;
    public $department_name;
    public $password;

    protected function rules()
    {
        return [
            'name'          => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'password'      => 'nullable|min:6',
        ];
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->department_id = $user->department_id;
    }

    public function update()    
    {
        $this->validate();

        $this->user->update([
            'name'          => $this->name,
            'department' => $this->department_id,
            'department_name' => $this->department_name,
            'password'      => $this->password ? Hash::make($this->password) : $this->user->password,
        ]);

        $this->reset('password'); // biar kosong lagi setelah update

        session()->flash('success', 'User updated successfully.');
    }
    public function updateDepartment_id(){
        $this->department_name = Department::whereID($this->department_id)->first()->department_name;
    }

    public function render()
    {
        return view('livewire.setting.user.user-update',[
              'departments' => Department::all(),
        ])->extends('base.index', ['header' => 'User', 'title' => 'User'])->section('content');
    }
}

