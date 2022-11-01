<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

Route::group([
    'namespace' => 'App\Http\Controllers\User',
    'prefix' => '',
    'middleware' => ['auth'],
], function () {
    Route::resource('role', 'RoleController');
    Route::resource('permission', 'PermissionController');
    Route::resource('post', 'PostController');
});

Route::group([
    'namespace' => 'App\Http\Controllers\Professional',
    'prefix' => 'professional',
    'middleware' => ['auth'],
], function () {
    Route::resource('professional-role', 'RoleController');
    Route::resource('professional-permission', 'PermissionController');
    Route::resource('professional-post', 'PostController');
});

Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => 'admin',
    'middleware' => ['auth'],
], function () {
    Route::resource('admin-user', 'UserController');
    Route::resource('admin-role', 'RoleController');
    Route::resource('admin-permission', 'PermissionController');
    Route::resource('admin-post', 'PostController');
});

