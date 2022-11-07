<?php

namespace App\Http\Livewire\Request;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\DataUser;
use Livewire\WithPagination;
use App\Models\PembelianBarang as ModelsPembelianBarang;

class PembelianBarang extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];
    public $tanggal, $id_user_request, $nama_barang, $qty, $harga, $total, $keterangan, $link_pembelian;
    public $filter_dari_tanggal, $filter_sampai_tanggal;
    public $awb, $courier, $status_order, $origin, $destination, $shipper, $receiver, $histories;
    public $searchTerm, $lengthData;
    public $updateMode = false;
    public $idRemoved = null;
    protected $paginationTheme = 'bootstrap';

    public function cekResi($kode_resi, $jenis_kurir) {
        $api_key = '7df929b7c85f149136e48a7ac1bcbf35ba2a6153c03de6eebb7b229dbe5ba733';
        $url = 'https://api.binderbyte.com/v1/track?api_key='.$api_key.'&courier='.$jenis_kurir.'&awb='.$kode_resi;
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $str = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($str, true);
        
        // dd($json);
        $this->awb = $json['data']['summary']['awb'];
        $this->courier = $json['data']['summary']['courier'];
        $this->status_order = $json['data']['summary']['status']; 
        $this->origin = $json['data']['detail']['origin'];
        $this->destination = $json['data']['detail']['destination'];
        $this->shipper = $json['data']['detail']['shipper'];
        $this->receiver = $json['data']['detail']['receiver'];
        $this->histories = $json['data']['history'];
        // dd($this->histories);
    }

    public function mount() {
        $this->tanggal = date('Y-m-d H:i');
        $this->filter_dari_tanggal = Carbon::now()->startOfMonth()->format('Y-m-d 00:00');
        $this->filter_sampai_tanggal = Carbon::now()->endOfMonth()->format('Y-m-d 23:59');
        $this->id_user_request = DataUser::min('id');
        $this->nama_barang = '';
        $this->qty = '1';
        $this->harga = '0';
        $this->total = '0';
        $this->keterangan = '-';
        $this->link_pembelian = '';
    }

    private function resetInputFields()
    {
        $this->tanggal = date('Y-m-d H:i');
        $this->id_user_request = DataUser::min('id');
        $this->nama_barang = '';
        $this->qty = '1';
        $this->harga = '0';
        $this->total = '0';
        $this->link_pembelian = '';
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function render()
    {
        \Carbon\Carbon::setLocale('id');
        $users = DataUser::select('id', 'nama_user')->get();
        $searchTerm = '%'.$this->searchTerm.'%';
		$lengthData = $this->lengthData;

        $data = ModelsPembelianBarang::select('pembelian_barang.*', 'user1.nama_user as user_request', 'user2.nama_user as user_accept', 'kurir.nama_kurir')
            ->join('data_user as user1', 'user1.id', 'pembelian_barang.id_user_request')
            ->leftJoin('data_user as user2', 'user2.id', 'pembelian_barang.id_user_accept')
            ->leftJoin('kurir', 'kurir.id', 'pembelian_barang.id_kurir')
            ->where(function($query) use ($searchTerm) {
                $query->where('user1.nama_user', 'LIKE', $searchTerm);
                $query->orWhere('user2.nama_user', 'LIKE', $searchTerm);
                $query->orWhere('pembelian_barang.nama_barang', 'LIKE', $searchTerm);
                $query->orWhere('pembelian_barang.tanggal', 'LIKE', $searchTerm);
                $query->orWhere('pembelian_barang.qty', 'LIKE', $searchTerm);
                $query->orWhere('pembelian_barang.harga', 'LIKE', $searchTerm);
                $query->orWhere('pembelian_barang.total', 'LIKE', $searchTerm);
                $query->orWhere('pembelian_barang.keterangan', 'LIKE', $searchTerm);
                $query->orWhere('pembelian_barang.link_pembelian', 'LIKE', $searchTerm);
            })
            ->whereBetween('pembelian_barang.created_at', [$this->filter_dari_tanggal, $this->filter_sampai_tanggal])
            ->orderBy('pembelian_barang.id', 'DESC')
            ->paginate($lengthData ?? 5);

        return view('livewire.request.pembelian-barang', compact('data', 'users'))
            ->extends('layouts.apps', ['title' => 'Pembelian Barang']);
    }

    public function store()
    {
        $this->validate([
            'tanggal'           => 'required',
            'id_user_request'   => 'required',
            'nama_barang'       => 'required',
            'qty'               => 'required',
            'harga'             => 'required',
            'total'             => 'required',
            'keterangan'        => 'required',
            'link_pembelian'    => 'required',
        ]);

        if( $this->total == 0 ) {
            $total = $this->qty * $this->harga;
        } else {
            $total = $this->total;
        }

        ModelsPembelianBarang::create([
            'tanggal_group'     => '',
            'tanggal'           => $this->tanggal,
            'id_user_request'   => $this->id_user_request,
            'id_user_accept'    => '',
            'kode_resi'         => '',
            'id_kurir'          => '',
            'nama_barang'       => $this->nama_barang,
            'qty'               => $this->qty,
            'harga'             => $this->harga,
            'total'             => $total,
            'keterangan'        => $this->keterangan,
            'link_pembelian'    => $this->link_pembelian,
            'status'            => 'Menunggu Persetujuan'
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
        $data = ModelsPembelianBarang::where('id',$id)->first();
        $this->dataId           = $id;
        $this->tanggal          = $data->tanggal;
        $this->id_user_request  = $data->id_user_request;
        $this->nama_barang      = $data->nama_barang;
        $this->qty              = $data->qty;
        $this->harga            = $data->harga;
        $this->total            = $data->total;
        $this->keterangan       = $data->keterangan;
        $this->link_pembelian   = $data->link_pembelian;
    }

    public function update()
    {
        $this->validate([
            'tanggal'           => 'required',
            'id_user_request'   => 'required',
            'nama_barang'       => 'required',
            'qty'               => 'required',
            'harga'             => 'required',
            'total'             => 'required',
            'keterangan'        => 'required',
            'link_pembelian'    => 'required',
        ]);

        if( $this->total == 0 ) {
            $total = $this->qty * $this->harga;
        } else {
            $total = $this->total;
        }
        
        if ($this->dataId) {
            $data = ModelsPembelianBarang::findOrFail($this->dataId);
            $data->update([
                'tanggal'           => $this->tanggal,
                'id_user_request'   => $this->id_user_request,
                'nama_barang'       => $this->nama_barang,
                'qty'               => $this->qty,
                'harga'             => $this->harga,
                'total'             => $total,
                'keterangan'        => $this->keterangan,
                'link_pembelian'    => $this->link_pembelian,
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
        $data = ModelsPembelianBarang::findOrFail($this->idRemoved);
        $data->delete();
    }
}
