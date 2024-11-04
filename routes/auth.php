<?php

use Illuminate\Support\Facades\Route;

Route::get('/register/two_factor', function () {
    $user = auth()->user();
    if (!$user) return redirect()->route('login');
    if ($user->two_factor_confirmed_at) return redirect()->route('dashboard');

    return view('auth.two-factor-register');
})->name('register.two-factor');
