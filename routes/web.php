<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'guest'], function() {
    
    Route::redirect('/', '/login');

    Route::view('/register', 'register')->name('user.register.view');
    Route::post('/register', [UserController::class, 'register'])->name('user.register');

    Route::view('/login', 'login')->name('user.login.view');
    Route::post('/login', [UserController::class, 'login'])->name('user.login');

});

Route::group(['middleware' => 'auth'], function() {
    
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/users', [UserController::class, 'users'])->name('users');
    Route::get('/user/{id}', [UserController::class, 'user'])->name('user');
    Route::get('/approve/{id}', [UserController::class, 'approveUser'])->name('user.approve');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::view('/add-user', 'newUser')->name('user.new.view');
    Route::post('/add-user', [UserController::class, 'addUser'])->name('user.new');
    Route::view('/edit-user/{id}', 'editUser')->name('user.edit.view');
    Route::put('/edit-user/{id}', [UserController::class, 'updateUser'])->name('user.edit');
    Route::get('/delete-user/{id}', [UserController::class, 'deleteUser'])->name('user.delete');

});