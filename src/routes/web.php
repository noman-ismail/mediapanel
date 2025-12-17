<?php

use Illuminate\Support\Facades\Route;
use NomanIsmail\MediaPanel\Http\Controllers\MediaController;

/*
|--------------------------------------------------------------------------
| Media Panel Routes
|--------------------------------------------------------------------------
|
| Routes for the media panel functionality.
| These routes are loaded by the MediaPanelServiceProvider.
| Routes are prefixed with '/mediapanel' to avoid conflicts with existing routes.
|
*/

Route::middleware(['web'])->prefix('mediapanel')->name('mediapanel.')->group(function () {
    // Media panel interface (supports both HTML and JSON responses)
    Route::get('/', [MediaController::class, 'index'])->name('index');
    
    // Media CRUD operations
    Route::post('/', [MediaController::class, 'store'])->name('store');
    Route::put('/{id}', [MediaController::class, 'update'])->name('update');
    Route::delete('/{id}', [MediaController::class, 'destroy'])->name('destroy');
    
    // Media search and filtering
    Route::get('/search', [MediaController::class, 'search'])->name('search');
    Route::get('/folder', [MediaController::class, 'getByFolder'])->name('folder');
});
