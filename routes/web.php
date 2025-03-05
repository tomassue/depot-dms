<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\GeneratePDFController;
use App\Livewire\AccountSettings\ChangePassword;
use App\Livewire\Dashboard;
use App\Livewire\Incoming;
use App\Livewire\Mechanics\MechanicDetails;
use App\Livewire\Mechanics\Mechanics as MechanicsMechanics;
use App\Livewire\MechanicsJobOrderReport;
use App\Livewire\Report;
use App\Livewire\Settings\Category;
use App\Livewire\Settings\Location;
use App\Livewire\Settings\Mechanics;
use App\Livewire\Settings\Model;
use App\Livewire\Settings\Office;
use App\Livewire\Settings\Permissions;
use App\Livewire\Settings\RefSectionsMechanic;
use App\Livewire\Settings\RefSubSectionsMechanic;
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
    Route::get('/generate-release-form/{id}', [GeneratePDFController::class, 'generateReleaseForm'])->name('generate-release-form')->middleware('signed');

    Route::get('/report/weekly-depot-repair-bay-vehicle-or-equipment-inventory', Report::class)->name('weekly-depot-repair-bay-vehicle-or-equipment-inventory');
    Route::get('/report/mechanics-job-order', MechanicsJobOrderReport::class)->name('mechanics-job-order');

    Route::get('/mechanics-list', MechanicsMechanics::class)->name('mechanics-list');
    Route::get('/generate-mechanics-list-pdf', [GeneratePDFController::class, 'generateMechanicsListPDF'])->name('generate-mechanics-list-pdf')->middleware('signed'); //* I did not include the date parameter here because I have http_build_query in the Component.
    Route::get('/mechanics-list/{id}', MechanicDetails::class)->name('mechanic-details');
    Route::get('/generate-job-orders-pdf/{id}/{date?}', [GeneratePDFController::class, 'generateJobOrdersPDF'])->name('generate-job-orders-pdf')->middleware('signed');

    Route::get('/file/view/{id}', [FileController::class, 'viewFile'])->name('file.view')->middleware('signed');

    Route::get('/settings/signatories', Signatories::class)->name('signatories');
    Route::get('/settings/equipment-or-vehicle-type', Type::class)->name('equipment-or-vehicle-type');
    Route::get('/settings/equipment-or-vehicle-model', Model::class)->name('equipment-or-vehicle-model');
    Route::get('/settings/type-of-repair', TypeOfRepair::class)->name('type-of-repair');

    Route::get('/settings/reference-mechanics', Mechanics::class)->name('mechanics');
    Route::get('/settings/mechanics-pdf/{date?}', [GeneratePDFController::class, 'generateMechanicsPDF'])->name('generate-mechanics-pdf');
    Route::get('/settings/sections-mechanics', RefSectionsMechanic::class)->name('sections-mechanic');
    Route::get('/settings/sub-sections', RefSubSectionsMechanic::class)->name('sub-sections-mechanic');

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

/* -------------------------------------------------------------------------- */

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/depot-dms/livewire/livewire.js', $handle);
});
Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/depot-dms/livewire/update', $handle);
});
