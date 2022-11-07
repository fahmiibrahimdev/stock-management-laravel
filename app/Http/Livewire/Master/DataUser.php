<?php

namespace App\Http\Livewire\Master;

use App\Models\DataUser as ModelsDataUser;
use App\Models\Divisi;
use App\Models\Jabatan;
use Livewire\Component;
use Livewire\WithPagination;

class DataUser extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];
    public $nama_user, $id_divisi, $id_jabatan;
    public $searchTerm, $lengthData;
    public $updateMode = false;
    public $idRemoved = null;
    protected $paginationTheme = 'bootstrap';

    public function mount() {
        $this->nama_user = '';
        $this->id_divisi = Divisi::min('id');
        $this->id_jabatan = '';
    }

    private function resetInputFields()
    {
        $this->nama_user = '';
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
		
        $divisis = Divisi::select('id', 'kode_divisi', 'nama_divisi')->get();
        $jabatans = Jabatan::select('id', 'nama_jabatan')->get();

		$data = ModelsDataUser::select('data_user.*', 'divisi.nama_divisi', 'jabatan.nama_jabatan')
            ->join('divisi', 'divisi.id', 'data_user.id_divisi')
            ->join('jabatan', 'jabatan.id', 'data_user.id_jabatan')
            ->where(function($query) use ($searchTerm) {
                $query->where('divisi.nama_divisi', 'LIKE', $searchTerm);
                $query->orWhere('jabatan.nama_jabatan', 'LIKE', $searchTerm);
                $query->orWhere('data_user.nama_user', 'LIKE', $searchTerm);
                $query->orWhere('data_user.id_divisi', 'LIKE', $searchTerm);
            })
            ->orderBy('data_user.id', 'DESC')
            ->paginate($lengthData ?? 5);

        return view('livewire.master.data-user', compact('data', 'divisis', 'jabatans'))
            ->extends('layouts.apps', ['title' => 'Data User']);
    }

    public function store()
    {
        $this->validate([
            'nama_user'  => 'required',
            'id_divisi'  => 'required',
            'id_jabatan'  => 'required',
        ]);
        ModelsDataUser::create([
            'nama_user'  => $this->nama_user,
            'id_divisi'  => $this->id_divisi,
            'id_jabatan'  => $this->id_jabatan,
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
        $data = ModelsDataUser::where('id',$id)->first();
        $this->dataId = $id;
        $this->nama_user = $data->nama_user;
        $this->id_divisi = $data->id_divisi;
        $this->id_jabatan = $data->id_jabatan;
    }

    public function update()
    {
        $this->validate([
            'nama_user'  => 'required',
            'id_divisi'  => 'required',
            'id_jabatan'  => 'required',
        ]);

        if ($this->dataId) {
            $data = ModelsDataUser::findOrFail($this->dataId);
            $data->update([
                'nama_user'  => $this->nama_user,
                'id_divisi'  => $this->id_divisi,
                'id_jabatan'  => $this->id_jabatan,
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
        $data = ModelsDataUser::findOrFail($this->idRemoved);
        $data->delete();
    }
}
