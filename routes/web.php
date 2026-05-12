<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

$prefix = trim((string) env('APP_PATH_PREFIX', ''), '/');

Route::prefix($prefix)->group(function () {
    Route::get('/', [LandingController::class, 'show'])->name('landing');

    Route::post('/leads', [LeadController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('leads.store');
});
