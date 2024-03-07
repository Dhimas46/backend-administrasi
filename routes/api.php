<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\HistoryPenghuniController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\SummaryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Penghuni
// Route::resource('penghuni', PenghuniController::class);

Route::prefix('penghuni')->group(function () {
    Route::get('/', [PenghuniController::class, 'index'])->name('penghuni.manage');
    Route::post('/store', [PenghuniController::class, 'store'])->name('penghuni.store');
    Route::post('/update/{id}', [PenghuniController::class, 'update'])->name('penghuni.update');
    Route::delete('delete/{id}', [PenghuniController::class, 'destroy'])->name('penghuni.delete');
});

Route::prefix('rumah')->group(function () {
    Route::get('/', [RumahController::class, 'index'])->name('rumah.manage');
    Route::post('/store', [RumahController::class, 'store'])->name('rumah.store');
    Route::post('/update/{id}', [RumahController::class, 'update'])->name('rumah.update');
    Route::delete('delete/{id}', [RumahController::class, 'destroy'])->name('rumah.delete');
});


Route::prefix('history/penghuni')->group(function () {
    Route::get('/', [HistoryPenghuniController::class, 'index'])->name('history.penghuni.manage');
    Route::post('/store', [HistoryPenghuniController::class, 'store'])->name('history.penghuni.store');
    Route::get('/details/{id}', [HistoryPenghuniController::class, 'show'])->name('history.penghuni.show');
    Route::get('/pembayaran/{id}', [HistoryPenghuniController::class, 'pembayaran'])->name('history.penghuni.pembayaran');
    Route::post('/update/{id}', [HistoryPenghuniController::class, 'update'])->name('history.penghuni.update');
    Route::delete('delete/{id}', [HistoryPenghuniController::class, 'destroy'])->name('history.penghuni.delete');
});

Route::prefix('pembayaran')->group(function () {
    Route::get('/', [PembayaranController::class, 'index'])->name('pembayaran.manage');
    Route::post('/store', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/details/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::post('/update/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::delete('delete/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.delete');
});

Route::prefix('pengeluaran')->group(function () {
    Route::get('/', [PengeluaranController::class, 'index'])->name('pengeluaran.manage');
    Route::post('/store', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::get('/details/{id}', [PengeluaranController::class, 'show'])->name('pengeluaran.show');
    Route::post('/update/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
    Route::delete('delete/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.delete');
});

Route::prefix('summary')->group(function () {
    Route::get('/chart', [SummaryController::class, 'chart'])->name('summary.chart');
    Route::get('/export', [SummaryController::class, 'export'])->name('summary.export');
});