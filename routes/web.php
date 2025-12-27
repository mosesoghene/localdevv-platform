<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServicePlanController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProjectRequestController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ServicePlanController as AdminServicePlanController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\ProjectRequestController as AdminProjectRequestController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\TicketTypeController;
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

// Payment Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout/product/{product}', [PaymentController::class, 'checkoutProduct'])->name('checkout.product');
    Route::get('/checkout/service/{servicePlan}', [PaymentController::class, 'checkoutServicePlan'])->name('checkout.service');
    Route::post('/payment/product/{product}', [PaymentController::class, 'initializeProductPayment'])->name('payment.product');
    Route::post('/payment/service/{servicePlan}', [PaymentController::class, 'initializeServicePayment'])->name('payment.service');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');
});

// Payment Webhook (No authentication required)
Route::post('/webhook/payment/{provider}', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Ticket Routes
Route::middleware('auth')->group(function () {
    Route::get('/events/{event}/tickets', [TicketController::class, 'showEventTickets'])->name('events.tickets');
    Route::get('/tickets/{ticketType}/checkout', [TicketController::class, 'checkout'])->name('tickets.checkout');
    Route::post('/tickets/{ticketType}/payment', [TicketController::class, 'initializePayment'])->name('tickets.payment');
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('user.tickets');
    Route::get('/tickets/{ticket}', [TicketController::class, 'showTicket'])->name('tickets.show');
});

// Dashboard
Route::get('/dashboard', [UserDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// User Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/my-subscriptions', [UserDashboardController::class, 'subscriptions'])->name('user.subscriptions');
    Route::post('/subscriptions/{subscription}/cancel', [UserDashboardController::class, 'cancelSubscription'])->name('subscriptions.cancel');
    Route::get('/my-orders', [UserDashboardController::class, 'orders'])->name('user.orders');
});

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
    
    // Payment Settings Management
    Route::get('payment-settings', [PaymentSettingController::class, 'index'])->name('payment-settings.index');
    Route::put('payment-settings/{provider}', [PaymentSettingController::class, 'update'])->name('payment-settings.update');
    Route::post('payment-settings/{provider}/toggle', [PaymentSettingController::class, 'toggle'])->name('payment-settings.toggle');
    
    // Ticket Type Management (nested under events)
    Route::prefix('events/{event}')->name('events.')->group(function () {
        Route::resource('ticket-types', TicketTypeController::class);
    });
});

require __DIR__.'/auth.php';
