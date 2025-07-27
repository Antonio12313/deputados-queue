<?php

use App\Http\Controllers\Home\DeputadoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function (Request $request) {
    return Inertia::render('auth/login', [
        'canResetPassword' => Route::has('password.request'),
        'status' => $request->session()->get('status'),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DeputadoController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
