<?php

use Illuminate\Support\Facades\Route;
use NomanIsmail\MediaPanel\Http\Controllers\MediaController;

/*
|--------------------------------------------------------------------------
| Media Panel Routes
|--------------------------------------------------------------------------
|
| Routes for the media panel functionality.
|
*/

Route::middleware(['web'])->group(function () {
    // Media panel interface
    Route::get('/media', [MediaController::class, 'index'])->name('mediapanel.index');
    
    // Media CRUD operations
    Route::post('/media', [MediaController::class, 'store'])->name('mediapanel.store');
    Route::put('/media/{id}', [MediaController::class, 'update'])->name('mediapanel.update');
    Route::delete('/media/{id}', [MediaController::class, 'destroy'])->name('mediapanel.destroy');
    
    // Media search and filtering
    Route::get('/media/search', [MediaController::class, 'search'])->name('mediapanel.search');
    Route::get('/media/folder', [MediaController::class, 'getByFolder'])->name('mediapanel.folder');
});
