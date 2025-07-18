<?php

namespace App\Livewire\Administration\TindakanTidakAman;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tindakantidakaman;

class Tta extends Component
{
    use WithPagination;
    public $name;
    public $tta_id, $modal = 'modal', $divider, $search = '';

    public function modalOpen()
    {
        $this->modal = 'modal modal-open';
        $this->divider = 'Input KTA';
    }
    public function closeModal()
    {
        $this->reset('modal');
        $this->reset('name', 'tta_id');
    }
    public function createKta()
    {
        $this->modalOpen();
    }
    public function updateData(Tindakantidakaman $tta)
    {
        $this->modalOpen();
        $this->tta_id = $tta->id;
        $this->name = $tta->name;
        $this->divider = 'Edit KTA';
    }
    public function rules()
    {
        return  [
            'name'        => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Kolom wajib diisi',
        ];
    }

    public function store()
    {
        Tindakantidakaman::updateOrCreate(
            ['id' => $this->tta_id],
            ['name' => $this->name]
        );
        if ($this->tta_id) {
            $this->dispatch(
                'alert',
                [
                    'text' => "Data has been updated",
                    'duration' => 3000,
                    'destination' => '/contact',
                    'newWindow' => true,
                    'close' => true,
                    'backgroundColor' => "linear-gradient(to right, #00b09b, #96c93d)",
                ]
            );
        } else {
            $this->dispatch(
                'alert',
                [
                    'text' => "Data added Successfully!!",
                    'duration' => 3000,
                    'destination' => '/contact',
                    'newWindow' => true,
                    'close' => true,
                    'backgroundColor' => "linear-gradient(to right, #00b09b, #96c93d)",
                ]
            );
            $this->reset('name', 'tta_id');
        }
    }
    public function delete($id)
    {
        $deleteFile = Tindakantidakaman::whereId($id);
        $deleteFile->delete();
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
    public function render()
    {
        return view('livewire.administration.tindakan-tidak-aman.tta', [
            'TTA' => Tindakantidakaman::paginate(30)
        ])->extends('base.index', ['header' => 'TTA', 'title' => 'TTA'])->section('content');
    }
    public function paginationView()
    {
        return 'pagination.masterpaginate';
    }
}
