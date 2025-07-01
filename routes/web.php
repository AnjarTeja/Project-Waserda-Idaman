<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PenjualanController;

// Route utama, mengarahkan ke halaman Register
Route::get('/', function () {
    return redirect()->route('register');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 3. Kelola Barang
    Route::get('/barangs/search', [BarangController::class, 'search'])->name('barangs.search');
    Route::resource('barangs', BarangController::class)->except(['show']);
    
    // 4. Riwayat Penjualan
    Route::get('/penjualans/report', [PenjualanController::class, 'generateReport'])->name('penjualans.report');
    Route::get('/penjualans/search', [PenjualanController::class, 'search'])->name('penjualans.search');
    Route::resource('penjualans', PenjualanController::class)->except(['show']);
});