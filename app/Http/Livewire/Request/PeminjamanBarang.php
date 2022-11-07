<?php

namespace App\Http\Livewire\Request;

use App\Models\DataBarang;
use App\Models\DataUser;
use App\Models\PeminjamanBarang as ModelsPeminjamanBarang;
use Livewire\Component;
use Livewire\WithPagination;

class PeminjamanBarang extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'delete',
        'pengembalianConfirmed' => 'pengembalian',
    ];
    public $id_user, $id_barang, $id_pengawas, $tanggal_pinjam, $tanggal_kembali;
    public $edit_id_user, $edit_id_barang, $edit_id_pengawas;
    public $filter_nama_peminjam, $filter_nama_barang, $filter_nama_pengawas, $filter_tanggal_pinjam, $filter_tanggal_kembali;
    public $dari_tanggal, $sampai_tanggal;
    public $searchTerm, $lengthData;
    public $updateMode = false;
    public $idRemoved, $idPengembalian = null;
    protected $paginationTheme = 'bootstrap';

    public function mount() {
        $this->id_user = DataUser::min('id');
        $this->id_barang = DataBarang::min('id');
        $this->id_pengawas = DataUser::min('id');
        $this->tanggal_pinjam = date('Y-m-d H:i');
        
        $this->filter_nama_peminjam = 'ASC';
        $this->filter_nama_barang = 'ASC';
        $this->filter_nama_pengawas = 'ASC';
        $this->filter_tanggal_pinjam = 'ASC';
        $this->filter_tanggal_kembali = 'ASC';

        $this->dari_tanggal = date('Y-m-d 00:00');
        $this->sampai_tanggal = date('Y-m-d 23:59');
    }

    private function resetInputFields()
    {
        $this->id_user = DataUser::min('id');
        $this->id_barang = DataBarang::min('id');
        $this->id_pengawas = DataUser::min('id');
        $this->tanggal_pinjam = date('Y-m-d H:i');
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
        $users = DataUser::select('id', 'nama_user')->get();
        $barangs = DataBarang::select('id', 'nama_barang')->get();

        $data = ModelsPeminjamanBarang::select('peminjaman_barang.*', 'data_barang.nama_barang', 'user1.nama_user as nama_peminjam', 'user2.nama_user as nama_pengawas')
            ->join('data_user as user1', 'user1.id', 'peminjaman_barang.id_user')
            ->join('data_user as user2', 'user2.id', 'peminjaman_barang.id_pengawas')
            ->join('data_barang', 'data_barang.id', 'peminjaman_barang.id_barang')
            ->where(function($query) use ($searchTerm) {
                $query->where('data_barang.nama_barang', 'LIKE', $searchTerm);
                $query->orWhere('user1.nama_user', 'LIKE', $searchTerm);
                $query->orWhere('user2.nama_user', 'LIKE', $searchTerm);
                $query->orWhere('peminjaman_barang.tanggal_pinjam', 'LIKE', $searchTerm);
                $query->orWhere('peminjaman_barang.tanggal_kembali', 'LIKE', $searchTerm);
            })
            ->whereBetween('peminjaman_barang.tanggal_pinjam', [$this->dari_tanggal, $this->sampai_tanggal])
            ->orderBy('user1.nama_user', $this->filter_nama_peminjam)
            ->orderBy('data_barang.nama_barang', $this->filter_nama_barang)
            ->orderBy('user2.nama_user', $this->filter_nama_pengawas)
            ->orderBy('peminjaman_barang.tanggal_pinjam', $this->filter_tanggal_pinjam)
            ->orderBy('peminjaman_barang.tanggal_kembali', $this->filter_tanggal_kembali)
            ->paginate($lengthData ?? 5);
            
        return view('livewire.request.peminjaman-barang', compact('data', 'users', 'barangs'))
            ->extends('layouts.apps', ['title' => 'Peminjaman Barang']);
    }

    public function store()
    {
        $this->validate([
            'id_user'  => 'required',
            'id_barang' => 'required',
            'id_pengawas' => 'required',
            'tanggal_pinjam' => 'required',
        ]);
        ModelsPeminjamanBarang::create([
            'id_user'  => $this->id_user,
            'id_barang' => $this->id_barang,
            'id_pengawas' => $this->id_pengawas,
            'tanggal_pinjam' => $this->tanggal_pinjam,
            'tanggal_kembali' => '',
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
        $data = ModelsPeminjamanBarang::where('id',$id)->first();
        $this->dataId = $id;
        $this->edit_id_user = $data->id_user;
        $this->edit_id_barang = $data->id_barang;
        $this->edit_id_pengawas = $data->id_pengawas;
        $this->tanggal_pinjam = $data->tanggal_pinjam;
        $this->tanggal_kembali = date('Y-m-d 17:30');
    }

    public function update()
    {
        $this->validate([
            'edit_id_user'  => 'required',
            'edit_id_barang' => 'required',
            'edit_id_pengawas' => 'required',
            'tanggal_pinjam' => 'required',
        ]);

        if ($this->dataId) {
            $data = ModelsPeminjamanBarang::findOrFail($this->dataId);
            $data->update([
                'id_user'  => $this->edit_id_user,
                'id_barang' => $this->edit_id_barang,
                'id_pengawas' => $this->edit_id_pengawas,
                'tanggal_pinjam' => $this->tanggal_pinjam,
                'tanggal_kembali' => $this->tanggal_kembali,
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
        $data = ModelsPeminjamanBarang::findOrFail($this->idRemoved);
        $data->delete();
    }

    public function filterSort($peminjam, $nm_barang, $pengawas, $tgl_pinjam, $tgl_kembali)
    {
        if ( $peminjam == 'ASC') {
            $this->filter_nama_peminjam = 'DESC';
        } else if ( $peminjam == 'DESC' ) {
            $this->filter_nama_peminjam = 'ASC';
        }

        if ( $nm_barang == 'ASC') {
            $this->filter_nama_barang = 'DESC';
        } else if ( $nm_barang == 'DESC' ) {
            $this->filter_nama_barang = 'ASC';
        }

        if ( $pengawas == 'ASC') {
            $this->filter_nama_pengawas = 'DESC';
        } else if ( $pengawas == 'DESC' ) {
            $this->filter_nama_pengawas = 'ASC';
        }

        if ( $tgl_pinjam == 'ASC') {
            $this->filter_tanggal_pinjam = 'DESC';
        } else if ( $tgl_pinjam == 'DESC' ) {
            $this->filter_tanggal_pinjam = 'ASC';
        }

        if ( $tgl_kembali == 'ASC') {
            $this->filter_tanggal_kembali = 'DESC';
        } else if ( $tgl_kembali == 'DESC' ) {
            $this->filter_tanggal_kembali = 'ASC';
        }
    }

    // public function pengembalianConfirm($id)
    // {
    //     $this->idPengembalian = $id;
    //     $this->dispatchBrowserEvent('swalPengembalian');
    // }

    public function pengembalian($id)
    {
        $data = ModelsPeminjamanBarang::findOrFail($id);
        $data->update(array('tanggal_kembali' => date('Y-m-d H:i')));
    }
}
