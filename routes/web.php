<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PercobaanController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', TestController::class)->middleware('guest:student');
Route::post('/login', [LoginController::class, 'login']);

Route::get('home', HomeController::class)->name('home');

// Route::get('exam', ExamController::class)->name('exam.show');
Route::get('exam/{exam}', [ExamController::class, 'setActive'])->name('exam.setActive');
Route::post('exam/{exam}', [ExamController::class, 'saveJawaban']);

Route::get('feb', [PercobaanController::class, 'extractTables']);