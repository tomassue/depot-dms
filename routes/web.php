<?php

use App\Livewire\Dashboard;
use App\Livewire\Settings\Category;
use App\Livewire\Settings\Mechanics;
use App\Livewire\Settings\Permissions;
use App\Livewire\Settings\Roles;
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

    Route::get('/settings/mechanics', Mechanics::class)->name('mechanics');
    Route::get('/settings/category', Category::class)->name('category');

    /* --------------------------- Super Admin Access --------------------------- */
    Route::get('/settings/user-management', UserManagement::class)->name('user-management');
    Route::get('/settings/roles', Roles::class)->name('roles');
    Route::get('/settings/permissions', Permissions::class)->name('permissions');
});
