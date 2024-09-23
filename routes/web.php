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
    'isAdmin',
])->group(function () {
    Route::get('/manage/admins', [\App\Http\Controllers\AdminController::class, 'index'])->name('manage.admins');
    Route::delete('/manage/admin/delete/{user}/', [\App\Http\Controllers\AdminController::class, 'destroy'])->name('manage.admins.delete');

    Route::get('/manage/tags', [\App\Http\Controllers\TagController::class, 'index'])->name('manage.tags');
    Route::get('/manage/tags/create', [\App\Http\Controllers\TagController::class, 'create'])->name('manage.tags.create');
    Route::post('/manage/tags/create', [\App\Http\Controllers\TagController::class, 'store'])->name('manage.tags.store');
    Route::delete('/manage/tags/delete/{tag}', [\App\Http\Controllers\TagController::class, 'destroy'])->name('manage.tags.delete');
});
