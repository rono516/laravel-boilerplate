<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;


Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(['auth']);
Route::get('/listings', [ListingsController::class, 'index'])->name('listings');
Route::resource('listings',ListingsController::class)->only('create','show', 'edit', 'update','store');
Auth::routes();

Route::match(['get', 'put'], '/users/{token}/welcome', [\App\Http\Controllers\Auth\WelcomeController::class, 'setPassword'])->name('users.welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/users/{user}/delete', [UserController::class, 'delete'])->name('users.delete');
    Route::resource('users', UserController::class)->except('show', 'edit', 'update');
});

Route::resource('users', UserController::class)->only('show', 'edit', 'update')->middleware('auth');

Route::namespace('App\Http\Controllers\Admin')->middleware(['auth', 'verified'])->name('admin.')->prefix('setup/')->group(function () {
    Route::get('/roles/{role}/delete', [RoleController::class, 'delete'])->name('roles.delete');
    Route::resource('roles', RoleController::class);

    Route::resource('permissionGroups', PermissionGroupController::class)->except('index', 'show', 'destroy');

    Route::get('/permissions/{permission}/delete', [PermissionController::class, 'delete'])->name('permissions.delete');
    Route::resource('permissions', PermissionController::class);

    Route::get('/audits', 'AuditController@auditing')->name('auditing.index');
});
