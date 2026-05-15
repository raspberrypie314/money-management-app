<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BudgetingController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\GrafikController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

// Auth (tanpa middleware)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Group dengan middleware auth (session check)
Route::middleware('auth.session')->group(function () {
    
    // Dashboard (income/outcome)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Budgeting (CRUD kategori)
    Route::get('/budgeting', [BudgetingController::class, 'index'])->name('budgeting');
    Route::post('/budgeting', [BudgetingController::class, 'store']);
    Route::put('/budgeting/{id}', [BudgetingController::class, 'update'])->name('budgeting.update');
    Route::delete('/budgeting/{id}', [BudgetingController::class, 'destroy'])->name('budgeting.destroy');
    Route::post('/budgeting/apply', [BudgetingController::class, 'applyToMonth'])->name('budgeting.apply');
    
    // Riwayat
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');
    Route::delete('/riwayat/{id}', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');
    
    // Grafik
    Route::get('/grafik', [GrafikController::class, 'index'])->name('grafik');
    
    // Profil (PERBAIKAN DI SINI)
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::post('/profil/name', [ProfilController::class, 'updateName'])->name('profil.name');
    Route::post('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
    Route::delete('/profil/account', [ProfilController::class, 'destroyAccount'])->name('profil.account');
    
}); 