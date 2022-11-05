<?php

namespace App\Http\Livewire\Persediaan;

use App\Models\DataBarang;
use App\Models\StockManage;
use Livewire\Component;
use Livewire\WithPagination;

class BarangMasuk extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];
    public $tanggal, $id_barang, $qty, $keterangan;
    public $searchTerm, $lengthData;
    public $updateMode = false;
    public $idRemoved = null;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->tanggal = date('Y-m-d H:i');
        $this->id_barang = DataBarang::min('id');
        $this->qty = '1';
        $this->keterangan = '-';
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    
    private function resetInputFields()
    {
        $this->tanggal = date('Y-m-d H:i');
        $this->id_barang = DataBarang::min('id');
        $this->qty = '1';
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
		$lengthData = $this->lengthData;
        $barangs = DataBarang::select('id', 'nama_barang')->get();

        $data = StockManage::select('stock_manages.*', 'data_barang.nama_barang')
                    ->join('data_barang', 'data_barang.id', 'stock_manages.id_barang')
                    ->where(function($query) use ($searchTerm) {
                        $query->where('data_barang.nama_barang', 'LIKE', $searchTerm);
                        $query->orWhere('stock_manages.tanggal', 'LIKE', $searchTerm);
                        $query->orWhere('stock_manages.qty', 'LIKE', $searchTerm);
                        $query->orWhere('stock_manages.keterangan', 'LIKE', $searchTerm);
                    })
                    ->where('stock_manages.status', 'In')
				    ->orderBy('stock_manages.id', 'DESC')
				    ->paginate($lengthData);

        return view('livewire.persediaan.barang-masuk', compact('data', 'barangs'))
        ->extends('layouts.apps', ['title' => 'Persediaan - Barang Masuk']);
    }

    public function store()
    {
        $this->validate([
            'tanggal'       => 'required',
            'id_barang'     => 'required',
            'qty'           => 'required',
            'keterangan'    => 'required',
        ]);
        StockManage::create([
            'tanggal'       => $this->tanggal,
            'id_barang'     => $this->id_barang,
            'qty'           => $this->qty,
            'keterangan'    => $this->keterangan,
            'status'        => 'In',
        ]);

        $stock_terakhir_barang = DataBarang::where('id', $this->id_barang)->first()->stock;
        $tambah_stock = $stock_terakhir_barang + $this->qty;
        
        $update_stock_barang = DataBarang::where('id', $this->id_barang)->update(array('stock' => $tambah_stock));

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
        $data = StockManage::where('id',$id)->first();
        $this->dataId = $id;
        $this->tanggal = $data->tanggal;
        $this->id_barang = $data->id_barang;
        $this->qty = $data->qty;
        $this->keterangan = $data->keterangan;
    }

    public function update()
    {
        $this->validate([
            'tanggal'       => 'required',
            'id_barang'     => 'required',
            'qty'           => 'required',
            'keterangan'    => 'required',
        ]);

        if ($this->dataId) {
            $data = StockManage::findOrFail($this->dataId);
            $data->update([
                'tanggal'       => $this->tanggal,
                'id_barang'     => $this->id_barang,
                'qty'           => $this->qty,
                'keterangan'    => $this->keterangan,
                'status'        => 'In',
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
        $qty_terakhir = StockManage::where('id', $this->idRemoved)->first()->qty; // 10
        $id_barang = StockManage::where('id', $this->idRemoved)->first()->id_barang;
        $stock_terakhir = DataBarang::where('id', $id_barang)->first()->stock; // Stock : 10

        $stock_terakhir = $stock_terakhir - $qty_terakhir;

        DataBarang::where('id', $id_barang)->update(array('stock' => $stock_terakhir));

        $data = StockManage::findOrFail($this->idRemoved);
        $data->delete();
    }
}
