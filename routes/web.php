<?php

use App\Exports\ExamNilaiExport;
use App\Exports\NilaiExport;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PercobaanController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\TestController;
use App\Models\Exam;
use App\Models\Rombel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', LoginController::class)->middleware('guest:student')->name('/');
Route::get('/login', fn() => redirect('/'))->name('login'); 
Route::post('/login', [LoginController::class, 'login']);

Route::get('rekap/{exam}', RekapController::class)->name('rekap');

Route::middleware('auth:student')->group(function(){
    Route::get('home', HomeController::class)->name('home');

    // Route::get('exam', ExamController::class)->name('exam.show');
    Route::get('exam/{exam}', [ExamController::class, 'setActive'])->name('exam.setActive');
    Route::put('exam/{exam}', [ExamController::class, 'verifyToken']);

    Route::post('exam/{exam}', [ExamController::class, 'saveJawaban']);
    Route::post('exam/{exam}/finish', [ExamController::class, 'finish']);

    Route::get('feb', [PercobaanController::class, 'extractTables']);
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/re', function(){
    dd(Carbon::now(), phpinfo());
});