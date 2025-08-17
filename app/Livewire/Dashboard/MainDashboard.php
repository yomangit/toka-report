<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class MainDashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard.main-dashboard')->extends('base.index', ['header' => 'Dashboard', 'title' => 'Dashboard'])->section('content');
    }
}
