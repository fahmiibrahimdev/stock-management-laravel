<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Dashboard\Dashboard;
use App\Http\Livewire\Master\DataBarang;
use App\Http\Livewire\Master\DataCb;
use App\Http\Livewire\Master\DataUser;
use App\Http\Livewire\Master\Divisi;
use App\Http\Livewire\Master\Jabatan;
use App\Http\Livewire\Master\Kategori;
use App\Http\Livewire\Master\Kurir;
use App\Http\Livewire\Master\Satuan;
use App\Http\Livewire\Persediaan\BarangKeluar;
use App\Http\Livewire\Persediaan\BarangMasuk;
use App\Http\Livewire\Persediaan\DaftarStock;
use App\Http\Livewire\Request\PembelianBarang;
use App\Http\Livewire\Request\PeminjamanBarang;
use App\Http\Livewire\SettingUser\SettingUser;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth']], function() {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
});

Route::group(['middleware' => ['auth', 'role:supervisor']], function() {
    Route::get('master/data-barang/', DataBarang::class)->name('master.data-barang');
    Route::get('master/data-user/', DataUser::class)->name('master.data-user');
    Route::get('master/divisi/', Divisi::class)->name('master.divisi');
    Route::get('master/jabatan/', Jabatan::class)->name('master.jabatan');
    Route::get('master/data-cb/', DataCb::class)->name('master.data-cb');
    Route::get('master/kategori/', Kategori::class)->name('master.kategori');
    Route::get('master/satuan/', Satuan::class)->name('master.satuan');
    Route::get('master/kurir/', Kurir::class)->name('master.kurir');
    Route::get('persediaan/barang-masuk/', BarangMasuk::class)->name('persediaan.barang-masuk');
    Route::get('persediaan/barang-keluar/', BarangKeluar::class)->name('persediaan.barang-keluar');
    Route::get('persediaan/daftar-stock/', DaftarStock::class)->name('persediaan.daftar-stock');
    Route::get('request/peminjaman-barang/', PeminjamanBarang::class)->name('request.peminjaman-barang');
    Route::get('request/pembelian-barang/', PembelianBarang::class)->name('request.pembelian-barang');
    Route::get('setting-user/', SettingUser::class)->name('setting-user.index');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
