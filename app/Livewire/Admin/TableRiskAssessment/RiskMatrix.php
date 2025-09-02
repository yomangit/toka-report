<?php

namespace App\Livewire\Admin\TableRiskAssessment;

use Livewire\Component;
use App\Models\RiskConsequence;
use App\Models\RiskLikelihood;

class RiskMatrix extends Component
{
     public $likelihoods, $consequences;

    public function mount()
    {
        $this->likelihoods = RiskLikelihood::orderByDesc('level')->get(); // L5 - L1
        $this->consequences = RiskConsequence::orderBy('level')->get(); // C1 - C5
    }
    public function render()
    {
        return view('livewire.admin.table-risk-assessment.risk-matrix');
    }
}
