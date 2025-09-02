<?php

namespace App\Livewire\Admin\TableRiskAssessment;

use Livewire\Component;
use App\Models\RiskLikelihood;
use App\Models\RiskMatrixCell;
use App\Models\RiskConsequence;

class Grid extends Component
{
    public $likelihoods, $consequences, $severity, $description, $action;
    public $editingCellId = null;
    public $showModal = false;
    public function mount()
    {
        $this->likelihoods = RiskLikelihood::orderByDesc('level')->get();
        $this->consequences = RiskConsequence::orderBy('level')->get();
    }

    public function edit($likelihoodId, $consequenceId)
    {
        $likelihoods_level = RiskLikelihood::whereId($likelihoodId)->first()->level;
        $RiskConsequence_level = RiskConsequence::whereId($consequenceId)->first()->level;
        $cell = RiskMatrixCell::where('likelihood_id', $likelihoodId)
            ->where('risk_consequence_id', $consequenceId)
            ->first();
        $this->editingCellId = $cell?->id;
        $this->severity = $cell?->severity;
        $this->description = "Auto generated L $likelihoods_level × C $RiskConsequence_level";
        $this->action = $cell?->action;
        $this->dispatch('edit-cell', id: $cell?->id, likelihood: $likelihoodId, consequence: $consequenceId);
       $this->showModal = true;
    }
    public function updateMatrix()
    {
        $this->validate([

            'severity' => 'required',
        ]);

        RiskMatrixCell::updateOrCreate(
            [
                'id' =>  $this->editingCellId,
            ],
            [
                'severity' => $this->severity,
                'description' => $this->description,
                'action' => $this->action,
            ]
        );
        $this->dispatch(
            'alert',
            [
                'text'            => "data berhasil di edit!!!",
                'duration'        => 5000,
                'destination'     => '/contact',
                'newWindow'       => true,
                'close'           => true,
                'backgroundColor' => "linear-gradient(to right, #06b6d4, #22c55e)",
            ]
        );
         $this->showModal = false;
    }
    public function close_modal()
    {
         $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.admin.table-risk-assessment.grid')->extends('base.index', ['header' => 'Table Risk ', 'title' => 'Table Risk'])->section('content');
    }
}
