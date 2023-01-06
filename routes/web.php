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

/*
|--------------------------------------------------------------------------
| Routing Syntax 
|--------------------------------------------------------------------------
| user level = singular nouns
| professional level = plural nouns
| admin level = plural nouns + underscore + view (_view)
|
*/



// Auth
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

// dashboard
Route::get('/dashboard', 
    [App\Http\Controllers\DashboardController::class, 'index']
)->middleware(['auth'])->name('dashboard');




require __DIR__.'/auth.php';

// user level
Route::group([
    'namespace' => 'App\Http\Controllers\User',
    'prefix' => '',
    'middleware' => ['auth'],
], function () {
    Route::resource('profile', 'ProfileController');
    Route::resource('content', 'ContentController');
    Route::resource('answer', 'AnswerController');
    Route::resource('event', 'EventController');
    Route::resource('appointment', 'AppointmentController');
    Route::resource('payment', 'PaymentController');
    Route::resource('chat', 'ChatController');
    Route::resource('event.task', 'EventTaskController');
    Route::resource('appointment.chat', 'AppointmentChatController');
    Route::resource('appointment.task', 'AppointmentTaskController');
    Route::resource('appointment.answer', 'AppointmentAnswerController');
});

// professional level
Route::group([
    'namespace' => 'App\Http\Controllers\Professional',
    'prefix' => 'professional',
    'middleware' => ['auth'],
], function () {
    Route::resource('profiles', 'ProfileController');
    Route::resource('contents', 'ContentController');
    Route::resource('answers', 'AnswerController');
    Route::resource('events', 'EventController');
    Route::resource('appointments', 'AppointmentController');
    Route::resource('payments', 'PaymentController');
    Route::resource('appointments.users', 'AppointmentUserController');
    Route::resource('chats', 'ChatController');
    Route::resource('appointments.chats', 'AppointmentChatController');
    Route::resource('appointments.contents', 'AppointmentContentController');
    Route::resource('appointments.answers', 'AppointmentAnswerController'); 
    Route::resource('appointments.tasks', 'AppointmentTaskController');
    Route::resource('events.tasks', 'EventTaskController');
    Route::resource('events.contents', 'EventContentController');
});

// admin level
Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => 'admin',
    'middleware' => ['auth'],
], function () {
    Route::resource('contents_view', 'ContentController');
    Route::resource('answers_view', 'AnswerController');
    Route::resource('events_view', 'EventController');
    Route::resource('appointments_view', 'AppointmentController');
    Route::resource('payments_view', 'PaymentController');
    // function for admin 
    Route::resource('users_view', 'UserController');
    Route::resource('roles_view', 'RoleController');
    Route::resource('permissions_view', 'PermissionController');
});

