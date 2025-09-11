<?php

namespace App\Livewire\Register;

use Rules\Password;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $name, $username, $user_id, $email, $password, $password_confirmation;

    public function name_Click(User $id)
    {
         if (!empty($id->password)) {
            // kirim notif ke frontend (pakai session flash atau Livewire dispatch)
            $this->dispatch(
                'alert',
                [
                    'text' => "User sudah terdaftar, silahkan login",
                    'duration' => 3000,
                    'destination' => '/contact',
                    'newWindow' => true,
                    'close' => true,
                    'backgroundColor' => "linear-gradient(to right, #00b09b, #96c93d)",
                ]
            );

            // reset form supaya tidak bisa lanjut register
            $this->reset(['user_id', 'name', 'email', 'username']);
        }
        $this->user_id = $id->id;
        $this->name = $id->lookup_name;
        $this->email = $id->email;
        $this->username = $id->username;
       
    }
    public function rules()
    {
        return [
            'email' => ['required', 'email', 'unique:users,email,' . $this->user_id],
            'username' => ['required'],
            'password' => ['required'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ];
    }
    public function store()
    {
        $this->validate();
        $user = User::find($this->user_id);
        if ($user && !empty($user->password)) {
            $this->dispatch(
                'alert',
                [
                    'text' => "User sudah terdaftar, silahkan login",
                    'duration' => 3000,
                    'destination' => '/contact',
                    'newWindow' => true,
                    'close' => true,
                    'backgroundColor' => "linear-gradient(to right, #00b09b, #96c93d)",
                ]
            );
            return;
        }
        $user = User::updateOrCreate(['id' => $this->user_id], [

            'lookup_name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard-hazard', absolute: false));
    }
    public function render()
    {
        return view('livewire.register.index', [
            'User' => User::searchNama(trim($this->name))->limit(500)->get()
        ])->extends('base.guest')->section('content');
    }
}
