<?php

namespace App\Livewire\Administration\KondisiTidakAman;

use App\Models\Kondisitidakaman;
use Livewire\Component;
use Livewire\WithPagination;

class Kta extends Component
{
    use WithPagination;
    public $name;
    public $kta_id, $modal = 'modal',$divider,$search='';

    public function modalOpen()
    {
        $this->modal = 'modal modal-open';
         $this->divider='input KTA';
    }
    public function closeModal()
    {
        $this->reset('modal');
    }
    public function createKta()
    {
        $this->modalOpen();
       
    }
    public function editKta(Kondisitidakaman $kta)
    {
        $this->modalOpen();
        $this->name = $kta->name;
          $this->divider='Edit KTA';
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
        Kondisitidakaman::updateOrCreate(
            ['id' => $this->kta_id],
            ['name' => $this->name]
        );
        if ($this->kta_id) {
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
            $this->reset('name');
        }
    }

    public function render()
    {
        return view('livewire.administration.kondisi-tidak-aman.kta', [
            'KTA' => Kondisitidakaman::paginate(30)
        ])->extends('base.index', ['header' => 'KTA', 'title' => 'KTA'])->section('content');
    }
    public function paginationView()
    {
        return 'pagination.masterpaginate';
    }
}
