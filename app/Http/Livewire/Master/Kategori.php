<?php

namespace App\Http\Livewire\Master;

use App\Models\Kategori as ModelsKategori;
use Livewire\Component;
use Livewire\WithPagination;

class Kategori extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];
    public $nama_kategori;
    public $searchTerm, $lengthData;
    public $updateMode = false;
    public $idRemoved = null;
    protected $paginationTheme = 'bootstrap';

    public function mount() {
        $this->nama_kategori = '';
    }

    private function resetInputFields()
    {
        $this->nama_kategori = '';
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
		
		$data = ModelsKategori::where('nama_kategori', 'LIKE', $searchTerm)
				  ->orderBy('id', 'ASC')
				  ->paginate($lengthData);


        return view('livewire.master.kategori', compact('data'))
        ->extends('layouts.apps', ['title' => 'Kategori']);
    }

    public function store()
    {
        $this->validate([
            'nama_kategori'  => 'required'
        ]);
        ModelsKategori::create([
            'nama_kategori'  => $this->nama_kategori
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
        $data = ModelsKategori::where('id',$id)->first();
        $this->dataId = $id;
        $this->nama_kategori = $data->nama_kategori;
    }

    public function update()
    {
        $this->validate([
            'nama_kategori'  => 'required',
        ]);

        if ($this->dataId) {
            $data = ModelsKategori::findOrFail($this->dataId);
            $data->update([
                'nama_kategori'  => $this->nama_kategori,
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
        $data = ModelsKategori::findOrFail($this->idRemoved);
        $data->delete();
    }
}
