<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServicePlanController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Service Plans
Route::get('/service-plans', [ServicePlanController::class, 'index'])->name('service-plans.index');
Route::get('/service-plans/{servicePlan}', [ServicePlanController::class, 'show'])->name('service-plans.show');

// Events
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// Project Requests
Route::post('/project-requests', [HomeController::class, 'storeProjectRequest'])->name('project-requests.store');

// Authentication routes will be added by Laravel Breeze
// Admin routes will be added after authentication is set up
