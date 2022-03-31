<?php

use App\Http\Controllers;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Client;
use App\Http\Livewire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Debugbar::disable();

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

Route::redirect('/', 'home');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/profile', [Client\ProfileController::class, 'profile'])->name('profile');
    Route::put('/profile', [Client\ProfileController::class, 'update'])->name('profile.update');
});
Auth::routes();


Route::group(['middleware' => ['auth', 'role:admin,team'],], function () {
    Route::get('/home', [Client\HomeController::class, 'index'])->name('home');
    Route::resource('/projects', Client\ProjectController::class)->except(['show']);
    Route::get('projects/{project}', Livewire\Tasks::class)->name('projects.show');
});

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['auth', 'role:admin'], 'as' => 'admin.'], function () {
        // if(request()->is('admin*')) {
        //     \Debugbar::enable();
        // }
        Route::redirect('/', '/admin/dashboard', 301)->name('redirect.admin-dashboard');
        Route::get('dashboard', [Admin\AdminController::class, 'index'])->name('index');

        Route::resource('/users', Admin\UserController::class)->except(['create', 'store', 'show']);

        Route::resource('/settings', Admin\SettingController::class);

        Route::get('logs', [Admin\AdminController::class, 'logs'])->name('logs.index');
    });
});
