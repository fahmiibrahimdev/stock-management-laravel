<?php

namespace App\Http\Livewire\Master;

use App\Models\DataUser as ModelsDataUser;
use App\Models\Divisi;
use Livewire\Component;
use Livewire\WithPagination;

class DataUser extends Component
{
    use WithPagination;
    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];
    public $nama, $id_divisi;
    public $searchTerm, $lengthData;
    public $updateMode = false;
    public $idRemoved = null;
    protected $paginationTheme = 'bootstrap';

    public function mount() {
        $this->nama = '';
        $this->id_divisi = Divisi::min('id');
    }

    private function resetInputFields()
    {
        $this->nama = '';
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
		
		$data = ModelsDataUser::select('data_user.*', 'divisi.nama_divisi')
            ->join('divisi', 'divisi.id', 'data_user.id_divisi')
            ->where(function($query) use ($searchTerm) {
                $query->where('divisi.nama_divisi', 'LIKE', $searchTerm);
                $query->orWhere('data_user.nama_user', 'LIKE', $searchTerm);
                $query->orWhere('data_user.id_divisi', 'LIKE', $searchTerm);
            })
            ->orderBy('data_user.id', 'DESC')
            ->paginate($lengthData ?? 5);

        return view('livewire.master.data-user', compact('data'))
        ->extends('layouts.apps', ['title' => 'Data User']);
    }
}
