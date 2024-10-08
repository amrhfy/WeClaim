<?php

use Illuminate\Support\Facades\Route;

// All Static Routes

Route::get('/', function () {
    return view('posts.home');
})->name('home');


// All Authentication Routes

Route::get('/login', function () {
    return view('authentication.login');
})->name('login');

Route::post('/login', [App\Http\Controllers\UserController::class, 'login'])->name('login');

Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('logout');

// All Claims Routes

Route::get('/claims', [App\Http\Controllers\ClaimController::class, 'index'])->name('claims-dashboard');

Route::get('claims/new', function () {
    return view('claims.new');
})->name('claims-new');

Route::post('claims/new', [App\Http\Controllers\ClaimController::class , 'store'])
->name('claims-new');