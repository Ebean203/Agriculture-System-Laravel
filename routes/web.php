<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

// Login routes (NO authentication required)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Agriculture System Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/yield-data', [DashboardController::class, 'getYieldData'])->name('api.yield-data');
    Route::get('/api/notifications', [NotificationController::class, 'getNotifications'])->name('api.notifications');

    // Analytics Routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Placeholder routes for sidebar navigation (will be implemented in next steps)
    
    // Farmer Management Routes
    Route::get('/farmers', [FarmerController::class, 'index'])->name('farmers.index');
    Route::get('/farmers/export-pdf', [FarmerController::class, 'exportPdf'])->name('farmers.export-pdf');
    Route::post('/farmers', [FarmerController::class, 'store'])->name('farmers.store');
    Route::post('/farmers/upload-photo', [FarmerController::class, 'uploadPhoto'])->name('farmers.upload-photo');
    Route::get('/farmers/{id}', [FarmerController::class, 'show'])->name('farmers.show');
    Route::get('/farmers/{id}/edit', [FarmerController::class, 'edit'])->name('farmers.edit');
    Route::put('/farmers/{id}', [FarmerController::class, 'update'])->name('farmers.update');
    Route::post('/farmers/{id}/archive', [FarmerController::class, 'archive'])->name('farmers.archive');
    Route::get('/api/farmers/search', [FarmerController::class, 'search'])->name('farmers.search');
    
    Route::get('/rsbsa', function () { return view('coming-soon', ['pageTitle' => 'RSBSA Records']); })->name('rsbsa');
    Route::get('/ncfrs', function () { return view('coming-soon', ['pageTitle' => 'NCFRS Records']); })->name('ncfrs');
    Route::get('/fishr', function () { return view('coming-soon', ['pageTitle' => 'FishR Records']); })->name('fishr');
    Route::get('/boats', function () { return view('coming-soon', ['pageTitle' => 'Boat Records']); })->name('boats');
    // Inventory Routes
    Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventory');
    Route::post('/inventory/add-stock', [App\Http\Controllers\InventoryController::class, 'addStock'])->name('inventory.add-stock');
    Route::post('/inventory/update-stock', [App\Http\Controllers\InventoryController::class, 'updateStock'])->name('inventory.update-stock');
    Route::post('/inventory/distribute', [App\Http\Controllers\InventoryController::class, 'distribute'])->name('inventory.distribute');
    Route::post('/inventory/add-new-input', [App\Http\Controllers\InventoryController::class, 'addNewInput'])->name('inventory.add-new-input');
    Route::get('/inventory/farmers', [App\Http\Controllers\InventoryController::class, 'getFarmers'])->name('inventory.farmers');
    Route::get('/distributions', function () { return view('coming-soon', ['pageTitle' => 'Distribution Records']); })->name('distributions');
    Route::get('/activities', function () { return view('coming-soon', ['pageTitle' => 'MAO Activities']); })->name('activities');
    Route::get('/yield-monitoring', function () { return view('coming-soon', ['pageTitle' => 'Yield Monitoring']); })->name('yield-monitoring');
    Route::get('/reports', function () { return view('coming-soon', ['pageTitle' => 'Reports']); })->name('reports');
    Route::get('/staff', function () { return view('coming-soon', ['pageTitle' => 'Staff Management']); })->name('staff.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Commented out to use our custom agriculture login instead of Breeze
// require __DIR__.'/auth.php';
