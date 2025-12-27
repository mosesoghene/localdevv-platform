<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServicePlanController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProjectRequestController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ServicePlanController as AdminServicePlanController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\ProjectRequestController as AdminProjectRequestController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Pages
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/service-plans', [ServicePlanController::class, 'index'])->name('service-plans.index');
Route::get('/service-plans/{servicePlan}', [ServicePlanController::class, 'show'])->name('service-plans.show');

Route::get('/events', [EventController::class, 'index'])->name('events.index');

// Project Requests
Route::post('/project-requests', [ProjectRequestController::class, 'store'])->name('project-requests.store');

// Download Routes (Authenticated Users Only)
Route::middleware('auth')->group(function () {
    Route::get('/my-products', [DownloadController::class, 'myProducts'])->name('user.products');
    Route::post('/download/{product}', [DownloadController::class, 'generateToken'])->name('download.generate');
    Route::get('/download/{token}', [DownloadController::class, 'download'])->name('download.file');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Product Management
    Route::resource('products', AdminProductController::class);
    
    // Service Plan Management
    Route::resource('service-plans', AdminServicePlanController::class);
    
    // Event Management
    Route::resource('events', AdminEventController::class);
    
    // Portfolio Management
    Route::resource('portfolios', AdminPortfolioController::class);
    
    // Project Requests Management
    Route::get('project-requests', [AdminProjectRequestController::class, 'index'])->name('project-requests.index');
    Route::patch('project-requests/{projectRequest}/status', [AdminProjectRequestController::class, 'updateStatus'])->name('project-requests.update-status');
    Route::delete('project-requests/{projectRequest}', [AdminProjectRequestController::class, 'destroy'])->name('project-requests.destroy');
});

require __DIR__.'/auth.php';
