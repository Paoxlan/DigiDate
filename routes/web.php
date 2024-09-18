<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    'verified',
//    'role:admin',
])->group(function () {
    Route::get('/manage/admins', [\App\Http\Controllers\AdminController::class, 'index'])->name('manage.admins');
    Route::delete('/manage/admin/delete/{user}/', [\App\Http\Controllers\AdminController::class, 'destroy'])->name('manage.admins.delete');
});
