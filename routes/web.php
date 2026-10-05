<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelPackageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth', 'is_admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // booking
    Route::resource('bookings', App\Http\Controllers\Admin\BookingController::class)->only(['index', 'update', 'destroy']);
    // travel packages
    Route::resource('travel_packages', App\Http\Controllers\Admin\TravelPackageController::class)->except('show');
    Route::resource('travel_packages.galleries', GalleryController::class)->except(['create', 'index', 'show']);
    // categories
    Route::resource('categories', CategoryController::class)->except('show');
    // blogs
    Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class)->except('show');
    // profile
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/', [HomeController::class, 'index'])->name('homepage');
// travel packages
Route::get('travel-packages', [TravelPackageController::class, 'index'])->name('travel_package.index');
Route::get('travel-packages/{travel_package:slug}', [TravelPackageController::class, 'show'])->name('travel_package.show');
// blogs
Route::get('blogs', [BlogController::class, 'index'])->name('blog.index');
Route::get('blogs/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('blogs/category/{category:slug}', [BlogController::class, 'category'])->name('blog.category');
// contact
Route::get('contact', function () {
    return Inertia::render('Contact');
})->name('contact');
// booking
Route::post('booking', [BookingController::class, 'store'])->middleware('throttle:10,1')->name('booking.store');
