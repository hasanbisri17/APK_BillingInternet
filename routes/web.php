<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::redirect('/', '/admin');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/invoice/{payment}/download', [InvoiceController::class, 'download'])
    ->middleware(['auth'])
    ->where('payment', '[0-9]+')
    ->missing(function () {
        return redirect()->back()->with('error', 'Invoice not found');
    })
    ->name('invoice.download');

require __DIR__.'/auth.php';
