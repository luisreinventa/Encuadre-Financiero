<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'show'])->name('landing');

Route::post('/leads', [LeadController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('leads.store');
