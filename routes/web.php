<?php

use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminMedecinController;
use App\Http\Controllers\AdminPatientController;
use App\Http\Controllers\AdminPharmacyController;
use App\Http\Controllers\AdminOrdonnanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatsController;

Route::get('/', function () {
    return view('welcome');
});

// Admin login routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

// Admin sidebar route
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/sidebar', function () {
        return view('admin.sidebar');
    })->name('admin.sidebar');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/medecins', [AdminMedecinController::class, 'index'])->name('admin.medecins');
    Route::get('/admin/patients', [AdminPatientController::class, 'index'])->name('admin.patients');
    Route::get('/admin/pharmacies', [AdminPharmacyController::class, 'index'])->name('admin.pharmacies');
    Route::get('/admin/ordonnances', [AdminOrdonnanceController::class, 'index'])->name('admin.ordonnances');
    Route::get('/admin/stats', [StatsController::class, 'admin']);

    Route::resource('/admin/medecins', AdminMedecinController::class)->only(['edit', 'update', 'destroy'])->names([
        'edit' => 'admin.medecins.edit',
        'update' => 'admin.medecins.update',
        'destroy' => 'admin.medecins.destroy',
    ]);
    Route::resource('/admin/patients', AdminPatientController::class)->only(['edit', 'update', 'destroy'])->names([
        'edit' => 'admin.patients.edit',
        'update' => 'admin.patients.update',
        'destroy' => 'admin.patients.destroy',
    ]);
    Route::resource('/admin/pharmacies', AdminPharmacyController::class)->only(['edit', 'update', 'destroy'])->names([
        'edit' => 'admin.pharmacies.edit',
        'update' => 'admin.pharmacies.update',
        'destroy' => 'admin.pharmacies.destroy',
    ]);
    Route::resource('/admin/ordonnances', AdminOrdonnanceController::class)->only(['edit', 'update', 'destroy'])->names([
        'edit' => 'admin.ordonnances.edit',
        'update' => 'admin.ordonnances.update',
        'destroy' => 'admin.ordonnances.destroy',
    ]);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
