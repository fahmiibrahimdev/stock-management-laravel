<?php

namespace App\Http\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DataCb as ModelsDataCb;

class DataCb extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];
    public $nama_cb;
    public $searchTerm, $lengthData;
    public $updateMode = false;
    public $idRemoved = null;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->nama_cb = '';
        $this->keterangan = '';
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    
    private function resetInputFields()
    {
        $this->nama_cb = '';
        $this->keterangan = '';
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
		$lengthData = $this->lengthData;
		
		$data = ModelsDataCb::where('nama_cb', 'LIKE', $searchTerm)
                  ->orWhere('keterangan', 'LIKE', $searchTerm)
				  ->orderBy('id', 'ASC')
				  ->paginate($lengthData);

        return view('livewire.master.data-cb', compact('data'))
            ->extends('layouts.apps', ['title' => 'Satuan']);
    }

    public function store()
    {
        $this->validate([
            'nama_cb'       => 'required',
            'keterangan'    => 'required'
        ]);
        ModelsDataCb::create([
            'nama_cb'       => $this->nama_cb,
            'keterangan'    => $this->keterangan,
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
        $data = ModelsDataCb::where('id',$id)->first();
        $this->dataId = $id;
        $this->nama_cb = $data->nama_cb;
        $this->keterangan = $data->keterangan;
    }

    public function update()
    {
        $this->validate([
            'nama_cb'  => 'required',
            'keterangan'  => 'required',
        ]);

        if ($this->dataId) {
            $data = ModelsDataCb::findOrFail($this->dataId);
            $data->update([
                'nama_cb'       => $this->nama_cb,
                'keterangan'    => $this->keterangan,
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
        $data = ModelsDataCb::findOrFail($this->idRemoved);
        $data->delete();
    }
}
