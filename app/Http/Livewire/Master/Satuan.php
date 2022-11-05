<?php

namespace App\Http\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Satuan as ModelsSatuan;

class Satuan extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];
    public $nama_satuan;
    public $searchTerm, $lengthData;
    public $updateMode = false;
    public $idRemoved = null;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->nama_satuan = '';
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    
    private function resetInputFields()
    {
        $this->nama_satuan = '';
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
		$lengthData = $this->lengthData;
		
		$data = ModelsSatuan::where('nama_satuan', 'LIKE', $searchTerm)
				  ->orderBy('id', 'ASC')
				  ->paginate($lengthData);

        return view('livewire.master.satuan', compact('data'))
            ->extends('layouts.apps', ['title' => 'Satuan']);
    }

    public function store()
    {
        $this->validate([
            'nama_satuan'  => 'required'
        ]);
        ModelsSatuan::create([
            'nama_satuan'  => $this->nama_satuan
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
        $data = ModelsSatuan::where('id',$id)->first();
        $this->dataId = $id;
        $this->nama_satuan = $data->nama_satuan;
    }

    public function update()
    {
        $this->validate([
            'nama_satuan'  => 'required',
        ]);

        if ($this->dataId) {
            $data = ModelsSatuan::findOrFail($this->dataId);
            $data->update([
                'nama_satuan'  => $this->nama_satuan,
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
        $data = ModelsSatuan::findOrFail($this->idRemoved);
        $data->delete();
    }
}
