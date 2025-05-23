<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;


Route::get('/',[ImageController::class, 'image'])->name('image');
Route::post('/upload',[ImageController::class, 'upload'])->name('upload');
