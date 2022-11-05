<?php

namespace App\Http\Livewire\Master;

use App\Models\DataBarang as ModelsDataBarang;
use App\Models\DataCb;
use App\Models\Kategori;
use App\Models\Satuan;
use Livewire\Component;
use Livewire\WithPagination;

class DataBarang extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];
    public $nama_barang, $id_kategori, $id_cb, $id_satuan, $stock;
    public $searchTerm, $lengthData;
    public $updateMode = false;
    public $idRemoved = null;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->nama_barang = '';
        $this->id_kategori = Kategori::min('id');
        $this->id_cb = DataCb::min('id');
        $this->id_satuan = Satuan::min('id');
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    
    private function resetInputFields()
    {
        $this->nama_barang = '';
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
		$lengthData = $this->lengthData;

        $data = ModelsDataBarang::select('data_barang.id', 'data_barang.nama_barang', 'data_barang.stock', 'kategori.nama_kategori', 'data_cb.nama_cb', 'satuan.nama_satuan')
                    ->join('kategori', 'kategori.id', 'data_barang.id_kategori')
                    ->join('data_cb', 'data_cb.id', 'data_barang.id_cb')
                    ->join('satuan', 'satuan.id', 'data_barang.id_satuan')
                    ->where(function($query) use ($searchTerm) {
                        $query->where('data_barang.nama_barang', 'LIKE', $searchTerm);
                        $query->orWhere('data_barang.stock', 'LIKE', $searchTerm);
                        $query->orWhere('kategori.nama_kategori', 'LIKE', $searchTerm);
                        $query->orWhere('data_cb.nama_cb', 'LIKE', $searchTerm);
                        $query->orWhere('satuan.nama_satuan', 'LIKE', $searchTerm);
                    })
				    ->orderBy('data_barang.id', 'ASC')
				    ->paginate($lengthData);

        $kategoris  = Kategori::select('id', 'nama_kategori')->get();
        $cbs        = DataCb::select('id', 'nama_cb', 'keterangan')->get();
        $satuans    = Satuan::select('id', 'nama_satuan')->get();

        return view('livewire.master.data-barang', compact('data', 'kategoris', 'cbs', 'satuans'))
            ->extends('layouts.apps', ['title' => 'Data Barang']);
    }

    public function store()
    {
        $this->validate([
            'nama_barang'   => 'required',
            'id_kategori'   => 'required',
            'id_cb'         => 'required',
            'id_satuan'     => 'required',
        ]);
        ModelsDataBarang::create([
            'nama_barang'   => $this->nama_barang,
            'id_kategori'   => $this->id_kategori,
            'id_cb'         => $this->id_cb,
            'id_satuan'     => $this->id_satuan,
            'stock'         => 0,
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
        $data = ModelsDataBarang::where('id',$id)->first();
        $this->dataId = $id;
        $this->nama_barang = $data->nama_barang;
        $this->id_kategori = $data->id_kategori;
        $this->id_cb = $data->id_cb;
        $this->id_satuan = $data->id_satuan;
    }

    public function update()
    {
        $this->validate([
            'nama_barang'   => 'required',
            'id_kategori'   => 'required',
            'id_cb'         => 'required',
            'id_satuan'     => 'required',
        ]);

        if ($this->dataId) {
            $data = ModelsDataBarang::findOrFail($this->dataId);
            $data->update([
                'nama_barang'   => $this->nama_barang,
                'id_kategori'   => $this->id_kategori,
                'id_cb'         => $this->id_cb,
                'id_satuan'     => $this->id_satuan,
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
        $data = ModelsDataBarang::findOrFail($this->idRemoved);
        $data->delete();
    }
}
