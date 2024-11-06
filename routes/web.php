<?php

use App\Livewire\Dashboard;
use App\Livewire\Settings\Roles;
use App\Livewire\Settings\UserManagement;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['auth'], function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

/* --------------------------- Super Admin Access --------------------------- */
Route::group(['auth'], function () {
    Route::get('/settings/user-management', UserManagement::class)->name('user-management');
    Route::get('/settings/roles', Roles::class)->name('roles');
});
