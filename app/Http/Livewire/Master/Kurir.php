<?php

namespace App\Http\Livewire\Master;

use App\Models\Kurir as ModelsKurir;
use Livewire\Component;
use Livewire\WithPagination;

class Kurir extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];
    public $nama_kurir;
    public $searchTerm, $lengthData;
    public $updateMode = false;
    public $idRemoved = null;
    protected $paginationTheme = 'bootstrap';

    public function mount() {
        $this->nama_kurir = '';
    }

    private function resetInputFields()
    {
        $this->nama_kurir = '';
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
		$lengthData = $this->lengthData;
		
		$data = ModelsKurir::where('nama_kurir', 'LIKE', $searchTerm)
				  ->orderBy('id', 'DESC')
				  ->paginate($lengthData);


        return view('livewire.master.kurir', compact('data'))
        ->extends('layouts.apps', ['title' => 'Kurir']);
    }

    public function store()
    {
        $this->validate([
            'nama_kurir'  => 'required'
        ]);
        ModelsKurir::create([
            'nama_kurir'  => $this->nama_kurir
        ]);
        $this->resetInputFields();
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',  
            'message' => 'Berhasil!', 
            'text' => 'Data Berhasil Dibuat!.'
        ]);
        $this->emit('dataStore');
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $data = ModelsKurir::where('id',$id)->first();
        $this->dataId = $id;
        $this->nama_kurir = $data->nama_kurir;
    }

    public function update()
    {
        $this->validate([
            'nama_kurir'  => 'required',
        ]);

        if ($this->dataId) {
            $data = ModelsKurir::findOrFail($this->dataId);
            $data->update([
                'nama_kurir'  => $this->nama_kurir,
            ]);
            $this->updateMode = false;
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',  
                'message' => 'Berhasil!', 
                'text' => 'Data berhasil diubah!.'
            ]);
            $this->resetInputFields();
            $this->emit('dataStore');
        }
    }

    public function deleteConfirm($id)
    {
        $this->idRemoved = $id;
        $this->dispatchBrowserEvent('swal');
    }

    public function delete()
    {
        $data = ModelsKurir::findOrFail($this->idRemoved);
        $data->delete();
    }
}
