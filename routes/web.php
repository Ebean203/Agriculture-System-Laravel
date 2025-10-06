<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ReportController;
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
    // Redirect to legacy analytics dashboard (procedural PHP page)
    Route::get('/analytics', function () {
        $legacyUrl = env('LEGACY_ANALYTICS_URL', 'http://localhost/agriculture-system/analytics_dashboard.php');
        return redirect()->away($legacyUrl);
    })->name('analytics.index');

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
    
    Route::get('/rsbsa', [App\Http\Controllers\RsbsaController::class, 'index'])->name('rsbsa');
    Route::get('/ncfrs', [App\Http\Controllers\NcfrsController::class, 'index'])->name('ncfrs');
    Route::get('/fishr', [App\Http\Controllers\FishrController::class, 'index'])->name('fishr');
    Route::get('/boats', [App\Http\Controllers\BoatsController::class, 'index'])->name('boats');
    // Inventory Routes
    Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventory');
    Route::post('/inventory/add-stock', [App\Http\Controllers\InventoryController::class, 'addStock'])->name('inventory.add-stock');
    Route::post('/inventory/update-stock', [App\Http\Controllers\InventoryController::class, 'updateStock'])->name('inventory.update-stock');
    Route::post('/inventory/distribute', [App\Http\Controllers\InventoryController::class, 'distribute'])->name('inventory.distribute');
    Route::post('/inventory/add-new-input', [App\Http\Controllers\InventoryController::class, 'addNewInput'])->name('inventory.add-new-input');
    Route::post('/inventory/add-commodity', [App\Http\Controllers\InventoryController::class, 'addNewCommodity'])->name('inventory.add-commodity');
    Route::get('/inventory/farmers', [App\Http\Controllers\InventoryController::class, 'getFarmers'])->name('inventory.farmers');
    Route::get('/distributions', [App\Http\Controllers\DistributionsController::class, 'index'])->name('distributions');
    // Activities (MAO) Routes
    Route::get('/activities', [App\Http\Controllers\ActivitiesController::class, 'index'])->name('activities');
    Route::post('/activities', [App\Http\Controllers\ActivitiesController::class, 'store'])->name('activities.store');
    Route::put('/activities/{id}', [App\Http\Controllers\ActivitiesController::class, 'update'])->name('activities.update');
    // All System Activities (legacy all_activities.php equivalent)
    Route::get('/activities/all', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activities.all');
    // Yield Monitoring Routes
    Route::get('/yield-monitoring', [App\Http\Controllers\YieldMonitoringController::class, 'index'])->name('yield-monitoring');
    Route::post('/yield-monitoring', [App\Http\Controllers\YieldMonitoringController::class, 'store'])->name('yield.store');
    
    // Reports Routes
    Route::get('/reports/index', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/all', [ReportController::class, 'allReports'])->name('reports.all');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/saved', [ReportController::class, 'getSavedReports'])->name('reports.saved');
    Route::get('/reports/count', [ReportController::class, 'getSavedReportsCount'])->name('reports.count');
    
    Route::get('/staff', [App\Http\Controllers\StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [App\Http\Controllers\StaffController::class, 'store'])->name('staff.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Commented out to use our custom agriculture login instead of Breeze
// require __DIR__.'/auth.php';
