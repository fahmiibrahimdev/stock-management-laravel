<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Dashboard\Dashboard;
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

Route::group(['middleware' => ['auth', 'role:boss']], function() {
    Route::get('hahaha/', Dashboard::class)->name('');
});

Route::group(['middleware' => ['auth', 'role:supervisor']], function() {
    Route::get('hahaha/', Dashboard::class)->name('');
    Route::get('setting-user/', SettingUser::class)->name('setting-user.index');
});

Route::group(['middleware' => ['auth', 'role:karyawan']], function() {
    Route::get('hahaha/', Dashboard::class)->name('');
});

Route::group(['middleware' => ['auth', 'role:magang']], function() {
    Route::get('hahaha/', Dashboard::class)->name('');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
