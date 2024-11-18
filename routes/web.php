<?php

use App\Livewire\Dashboard;
use App\Livewire\Incoming;
use App\Livewire\Settings\Category;
use App\Livewire\Settings\Location;
use App\Livewire\Settings\Mechanics;
use App\Livewire\Settings\Office;
use App\Livewire\Settings\Permissions;
use App\Livewire\Settings\Roles;
use App\Livewire\Settings\SubCategory;
use App\Livewire\Settings\UserManagement;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]); // Disable registration

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Apply the 'auth' middleware to ensure only authenticated users can access these routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/incoming', Incoming::class)->name('incoming');

    Route::get('/settings/mechanics', Mechanics::class)->name('mechanics');
    Route::get('/settings/category', Category::class)->name('category');
    Route::get('/settings/sub-category', SubCategory::class)->name('sub-category');
    Route::get('/settings/location', Location::class)->name('location');
    Route::get('/settings/offices', Office::class)->name('office');

    /* --------------------------- Super Admin Access --------------------------- */
    Route::get('/settings/user-management', UserManagement::class)->name('user-management');
    Route::get('/settings/roles', Roles::class)->name('roles');
    Route::get('/settings/permissions', Permissions::class)->name('permissions');
});
