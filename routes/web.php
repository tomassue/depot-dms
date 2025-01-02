<?php

use App\Http\Controllers\FileController;
use App\Livewire\AccountSettings\ChangePassword;
use App\Livewire\Dashboard;
use App\Livewire\Incoming;
use App\Livewire\Mechanics\MechanicDetails;
use App\Livewire\Mechanics\Mechanics as MechanicsMechanics;
use App\Livewire\Report;
use App\Livewire\Settings\Category;
use App\Livewire\Settings\Location;
use App\Livewire\Settings\Mechanics;
use App\Livewire\Settings\Model;
use App\Livewire\Settings\Office;
use App\Livewire\Settings\Permissions;
use App\Livewire\Settings\Roles;
use App\Livewire\Settings\Signatories;
use App\Livewire\Settings\Status;
use App\Livewire\Settings\SubCategory;
use App\Livewire\Settings\Type;
use App\Livewire\Settings\TypeOfRepair;
use App\Livewire\Settings\UserManagement;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]); // Disable registration

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'check_default_password', 'is_active'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/incoming', Incoming::class)->name('incoming');
    Route::get('/report', Report::class)->name('report');

    Route::get('/mechanics-list', MechanicsMechanics::class)->name('mechanics-list');
    Route::get('/mechanics-list/{id}', MechanicDetails::class)->name('mechanic-details');

    Route::get('/file/view/{id}', [FileController::class, 'viewFile'])->name('file.view')->middleware('signed');

    Route::get('/settings/signatories', Signatories::class)->name('signatories');
    Route::get('/settings/equipment-or-vehicle-type', Type::class)->name('equipment-or-vehicle-type');
    Route::get('/settings/equipment-or-vehicle-model', Model::class)->name('equipment-or-vehicle-model');
    Route::get('/settings/type-of-repair', TypeOfRepair::class)->name('type-of-repair');
    Route::get('/settings/mechanics', Mechanics::class)->name('mechanics');
    Route::get('/settings/category', Category::class)->name('category');
    Route::get('/settings/sub-category', SubCategory::class)->name('sub-category');
    Route::get('/settings/location', Location::class)->name('location');
    Route::get('/settings/offices', Office::class)->name('office');
    Route::get('/settings/status', Status::class)->name('status');

    /* --------------------------- Super Admin Access --------------------------- */
    Route::get('/settings/user-management', UserManagement::class)->name('user-management');
    Route::get('/settings/roles', Roles::class)->name('roles');
    Route::get('/settings/permissions', Permissions::class)->name('permissions');
});

/* ---------------------------- Account Settings ---------------------------- */
Route::middleware(['auth', 'is_active'])->group(function () {
    Route::get('account-settings/change-password', ChangePassword::class)->name('change-password');
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/cdo-dts/livewire/livewire.js', $handle);
});
Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/cdo-dts/livewire/update', $handle);
});
