<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        if( Auth::user()->hasRole('boss') ){
            return view('livewire.dashboard.dashboard-boss')
            ->extends('layouts.apps', ['title' => 'Dashboard']);
        } else if ( Auth::user()->hasRole('supervisor') ) {
            return view('livewire.dashboard.dashboard-supervisor')
            ->extends('layouts.apps', ['title' => 'Dashboard']);
        } else if ( Auth::user()->hasRole('karyawan') ) {
            return view('livewire.dashboard.dashboard-karyawan')
            ->extends('layouts.apps', ['title' => 'Dashboard']);
        } else if ( Auth::user()->hasRole('magang') ) {
            return view('livewire.dashboard.dashboard-magang')
            ->extends('layouts.apps', ['title' => 'Dashboard']);
        }

    }
}
