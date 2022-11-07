<?php

namespace App\Http\Livewire\Master;

use App\Models\Jabatan as ModelsJabatan;
use Livewire\Component;
use Livewire\WithPagination;

class Jabatan extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];
    public $nama_jabatan;
    public $searchTerm, $lengthData;
    public $updateMode = false;
    public $idRemoved = null;
    protected $paginationTheme = 'bootstrap';
    
    public function mount() {
        $this->nama_jabatan = '';
    }

    private function resetInputFields()
    {
        $this->nama_jabatan = '';
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
		
		$data = ModelsJabatan::where('nama_jabatan', 'LIKE', $searchTerm)
				  ->orderBy('id', 'DESC')
				  ->paginate($lengthData);

        return view('livewire.master.jabatan', compact('data'))
        ->extends('layouts.apps', ['title' => 'Jabatan']);
    }

    public function store()
    {
        $this->validate([
            'nama_jabatan'  => 'required'
        ]);
        ModelsJabatan::create([
            'nama_jabatan'  => $this->nama_jabatan
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
        $data = ModelsJabatan::where('id',$id)->first();
        $this->dataId = $id;
        $this->nama_jabatan = $data->nama_jabatan;
    }

    public function update()
    {
        $this->validate([
            'nama_jabatan'  => 'required',
        ]);

        if ($this->dataId) {
            $data = ModelsJabatan::findOrFail($this->dataId);
            $data->update([
                'nama_jabatan'  => $this->nama_jabatan,
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
        $data = ModelsJabatan::findOrFail($this->idRemoved);
        $data->delete();
    }
}
