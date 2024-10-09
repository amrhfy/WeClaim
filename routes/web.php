<?php

use App\Http\Controllers\ClaimController;
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

Route::get('claims', function () {
    return app('App\Http\Controllers\ClaimController')->index('claims.dashboard');
})->name('claims-dashboard');

Route::get('claims/new', function () {
    return view('claims.new');
})->name('claims-new');

Route::post('claims/new', [App\Http\Controllers\ClaimController::class , 'store'])
->name('claims-new');

Route::get('claims/approval', function () {
    return app('App\Http\Controllers\ClaimController')->index('claims.approval');
})->name('claims-approval');

Route::get('claims/{id}', function ($id) {
    return app('App\Http\Controllers\ClaimController')->show($id, 'claims.claim');
})->name('claims-claim');

Route::get('claims/approval/{id}', function ($id) {
    return app('App\Http\Controllers\ClaimController')->show($id, 'claims.review');
})->name('claims-approval-review');

Route::get('/claims/approve/{token}', [ClaimController::class, 'approve'])->name('claims.approve');
Route::get('/claims/reject/{token}', [ClaimController::class, 'reject'])->name('claims.reject');

Route::get('/send-claim-email/{id}', [ClaimController::class, 'sendClaimEmail'])->name('claims.send-email');
