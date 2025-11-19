<?php

use App\Http\Controllers\DiaryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DiaryController::class, 'index'])->name('diaries.index');
Route::get('/diaries/create', [DiaryController::class, 'create'])->name('diaries.create');
Route::post('/diaries', [DiaryController::class, 'store'])->name('diaries.store');
Route::get('/diaries/{diary}/edit', [DiaryController::class, 'edit'])->name('diaries.edit');
Route::put('/diaries/{diary}', [DiaryController::class, 'update'])->name('diaries.update');
Route::delete('/diaries/{diary}', [DiaryController::class, 'destroy'])->name('diaries.destroy');
