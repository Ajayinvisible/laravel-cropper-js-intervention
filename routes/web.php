<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;


Route::get('/',[ImageController::class, 'image'])->name('image');
Route::post('/upload',[ImageController::class, 'upload'])->name('upload');
Route::get('/edit/{id?}', [ImageController::class, 'editImage'])->name('edit');
Route::post('/update/{id?}', [ImageController::class, 'update'])->name('update');
Route::delete('/delete/{id?}', [ImageController::class, 'deleteImage'])->name('delete');
