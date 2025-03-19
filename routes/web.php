<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CanteenController as AdminCanteenController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\CanteenController as SellerCanteenController;
use App\Http\Controllers\Seller\MenuController as SellerMenuController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ReportController as SellerReportController;
use App\Http\Controllers\Buyer\DashboardController as BuyerDashboardController;
use App\Http\Controllers\Buyer\CanteenController as BuyerCanteenController;
use App\Http\Controllers\Buyer\OrderController as BuyerOrderController;
use App\Http\Controllers\Buyer\ProfileController as BuyerProfileController;
use App\Http\Controllers\Buyer\FavoriteController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CanteenController;


use App\Http\Controllers\Admin\UserController as AdminUserController;

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

use App\Http\Controllers\Buyer\FavoriteController as BuyerFavoriteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Welcome page
Route::get('/', [WelcomeController::class, 'index']);

// Language switcher
Route::get('/language/{locale}', [LanguageController::class, 'switchLang'])->name('language.switch');

// Canteen routes
Route::get('/canteens/{canteen}', [CanteenController::class, 'show'])->name('canteens.show');

// Menu routes
Route::get('/menus/{menu}', [MenuController::class, 'show'])->name('menus.show');
Route::get('/menus/category/{category}', [MenuController::class, 'byCategory'])->name('menus.category');


// Authentication routes including forgot password
Auth::routes(['verify' => false]);

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Users management
    Route::resource('users', UserController::class);
    
    // Canteens management
    Route::resource('canteens', AdminCanteenController::class);
    
    // Menus management
    Route::resource('menus', AdminMenuController::class);
    
    // Orders management
    Route::resource('orders', AdminOrderController::class);
    
    // Categories management
    Route::resource('categories', CategoryController::class);
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/settings/system-info', [SettingController::class, 'systemInfo'])->name('settings.system-info');
});

// Seller routes
Route::prefix('seller')->name('seller.')->middleware(['auth', 'seller'])->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    
    // Canteen Management Routes
    Route::resource('canteens', SellerCanteenController::class);
    
    // Menu Management Routes
    Route::resource('menus', SellerMenuController::class);
    
    // Order Management Routes
    Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/confirm-payment', [SellerOrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::get('/scan-barcode', [SellerOrderController::class, 'scanBarcode'])->name('orders.scan');
    Route::post('/verify-barcode', [SellerOrderController::class, 'verifyBarcode'])->name('orders.verify-barcode');
    
    // Report Routes
    Route::get('/reports', [SellerReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [SellerReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/menu-performance', [SellerReportController::class, 'menuPerformance'])->name('reports.menu-performance');
    Route::get('/reports/export-sales', [SellerReportController::class, 'exportSales'])->name('reports.export-sales');
    });
    

// Buyer routes
Route::prefix('buyer')->middleware(['auth', 'buyer'])->name('buyer.')->group(function () {
    Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('dashboard');
    
// Canteen Browsing Routes
Route::get('/canteens', [BuyerCanteenController::class, 'index'])->name('canteens.index');
Route::get('/canteens/{canteen}', [BuyerCanteenController::class, 'show'])->name('canteens.show');
    // Orders
    Route::get('/orders', [BuyerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [BuyerOrderController::class, 'show'])->name('orders.show');
    Route::get('/canteens/{canteen}/order', [BuyerOrderController::class, 'create'])->name('orders.create');
    Route::post('/canteens/{canteen}/order', [BuyerOrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/payment', [BuyerOrderController::class, 'payment'])->name('orders.payment');
    Route::post('/orders/{order}/payment', [BuyerOrderController::class, 'processPayment'])->name('orders.process-payment');
    Route::get('/orders/{order}/barcode', [BuyerOrderController::class, 'barcode'])->name('orders.barcode');
    
    // Profile Routes
    Route::get('/profile', [BuyerProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [BuyerProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/preferences', [BuyerProfileController::class, 'updatePreferences'])->name('profile.update-preferences');
    
    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

    // Midtrans Payment Notification Route
    Route::post('/payments/notification', [BuyerOrderController::class, 'handlePaymentNotification'])->name('payment.notification');