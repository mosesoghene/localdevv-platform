<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServicePlanController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProjectRequestController;
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

require __DIR__.'/auth.php';
