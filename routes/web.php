<?php

use App\Http\Controllers\OrderPanel\OrderController;
use App\Http\Controllers\OrderPanel\LoginController;
use App\Http\Controllers\AdminPanel\DashboardController;
use App\Http\Controllers\AdminPanel\ReminderController;
use Illuminate\Support\Facades\Route;

// ============================================
// APP 1 - PANEL ODDZIAŁU (PROSTE LOGOWANIE)
// ============================================
Route::prefix('order')->name('order.')->group(function () {
    
    // Logowanie
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard i zamówienia (wymaga logowania)
    Route::middleware(['auth.order'])->group(function () {
        Route::get('/dashboard', [OrderController::class, 'dashboard'])->name('dashboard');
        Route::get('/form', [OrderController::class, 'showForm'])->name('form');
        Route::post('/save-draft', [OrderController::class, 'saveDraft'])->name('save-draft');
        Route::post('/submit', [OrderController::class, 'submit'])->name('submit');
        Route::get('/confirmation', [OrderController::class, 'confirmation'])->name('confirmation');
        Route::get('/history', [OrderController::class, 'history'])->name('history');
    });
});

// ============================================
// APP 2 - PANEL ADMINA (/admin)
// ============================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard i zarządzanie (wymaga logowania i roli admin)
    Route::middleware(['auth', 'admin'])->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Zarządzanie oddziałami
        Route::resource('wards', \App\Http\Controllers\AdminPanel\WardController::class);
        
        // Przypomnienia
        Route::post('/remind', [ReminderController::class, 'sendReminders'])->name('remind');
        Route::post('/remind/{wardId}', [ReminderController::class, 'sendSingleReminder'])->name('remind.single');
        
        // Wydruk zamówień dla oddziału
        Route::get('/print/ward/{wardId}', [DashboardController::class, 'printWardOrders'])->name('print.ward');
        Route::get('/print/ward/{wardId}/date/{date}', [DashboardController::class, 'printWardOrders'])->name('print.ward.date');
        
        // NOWE: Zbiorczy wydruk dla kuchni
        Route::get('/print/kitchen', [DashboardController::class, 'printKitchen'])->name('print.kitchen');
        Route::get('/print/kitchen/date/{date}', [DashboardController::class, 'printKitchen'])->name('print.kitchen.date');
    });
});
// ============================================
// Strona główna – przekierowanie do logowania oddziału
// ============================================
Route::get('/', function () {
    return redirect()->route('order.login');
});

// ============================================
// SZYBKIE LOGOWANIE DLA TESTÓW (TYLKO ROZWÓJ)
// ============================================
Route::get('/quick-login/{wardId}', function($wardId) {
    $ward = App\Models\Ward::find($wardId);
    if (!$ward) {
        return "Oddział nie istnieje. Dostępne oddziały: " . App\Models\Ward::pluck('name', 'id');
    }
    session(['order_ward_id' => $ward->id]);
    return redirect()->route('order.dashboard')
        ->with('success', 'Zalogowano jako: ' . $ward->name);
})->name('quick.login');

// ============================================
// TRASY Z LARAVEL BREEZE (logowanie, rejestracja)
// ============================================
require __DIR__.'/auth.php';