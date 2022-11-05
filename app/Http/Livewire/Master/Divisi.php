<?php

namespace App\Http\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Divisi as ModelsDivisi;

class Divisi extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];
    public $kode_divisi, $nama_divisi;
    public $searchTerm, $lengthData;
    public $updateMode = false;
    public $idRemoved = null;
    protected $paginationTheme = 'bootstrap';

    public function mount() {
        $this->kode_divisi = '';
        $this->nama_divisi = '';
    }

    private function resetInputFields()
    {
        $this->nama_divisi = '';
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
		
		$data = ModelsDivisi::where('kode_divisi', 'LIKE', $searchTerm)
                  ->orWhere('nama_divisi', 'LIKE', $searchTerm)
				  ->orderBy('id', 'ASC')
				  ->paginate($lengthData);

        return view('livewire.master.divisi', compact('data'))
        ->extends('layouts.apps', ['title' => 'Divisi']);
    }

    public function store()
    {
        $this->validate([
            'kode_divisi'  => 'required',
            'nama_divisi'  => 'required',
        ]);
        ModelsDivisi::create([
            'kode_divisi'  => $this->kode_divisi,
            'nama_divisi'  => $this->nama_divisi
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
        $data = ModelsDivisi::where('id',$id)->first();
        $this->dataId = $id;
        $this->kode_divisi = $data->kode_divisi;
        $this->nama_divisi = $data->nama_divisi;
    }

    public function update()
    {
        $this->validate([
            'kode_divisi'  => 'required',
            'nama_divisi'  => 'required',
        ]);

        if ($this->dataId) {
            $data = ModelsDivisi::findOrFail($this->dataId);
            $data->update([
                'kode_divisi'  => $this->kode_divisi,
                'nama_divisi'  => $this->nama_divisi,
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
        $data = ModelsDivisi::findOrFail($this->idRemoved);
        $data->delete();
    }
}
