<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\MatchController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'twoFactorRequired',
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/matches', [MatchController::class, 'index'])->name('matches');
    Route::get('/matches/{user}', [MatchController::class, 'find'])->name('match.chat');

    Route::get('/matching', [\App\Http\Controllers\MatchingController::class, 'index'])->name('matching');
    Route::post('/matching/like/{user}', [\App\Http\Controllers\MatchingController::class, 'like'])->name('matching.like');
    Route::post('/matching/dislike/{user}', [\App\Http\Controllers\MatchingController::class, 'dislike'])->name('matching.dislike');
});

Route::middleware([
    'auth:sanctum',
    'twoFactorRequired',
    'verified',
    'isAdmin',
])->group(function () {
    Route::get('/manage/admins', [AdminController::class, 'index'])->name('manage.admins');
    Route::get('/manage/admin/register', [AdminController::class, 'create'])->name('manage.admin.create');
    Route::post('/manage/admin/register', [AdminController::class, 'store'])->name('manage.admin.store');
    Route::delete('/manage/admin/delete/{user}/', [AdminController::class, 'destroy'])->name('manage.admins.delete');

    Route::get('/manage/tags', [\App\Http\Controllers\TagController::class, 'index'])->name('manage.tags');
    Route::get('/manage/tags/create', [\App\Http\Controllers\TagController::class, 'create'])->name('manage.tags.create');
    Route::post('/manage/tags/create', [\App\Http\Controllers\TagController::class, 'store'])->name('manage.tags.store');
    Route::delete('/manage/tags/delete/{tag}', [\App\Http\Controllers\TagController::class, 'destroy'])->name('manage.tags.delete');

    Route::get('/audit-trails', [AuditTrailController::class, 'index'])->name('audit-trails');
    Route::get('/audit-trail/{auditTrail}', [AuditTrailController::class, 'show'])->name('audit-trail');
    Route::delete('/audit-trail/{auditTrail}', [AuditTrailController::class, 'destroy'])->name('audit-trail.delete');
});

