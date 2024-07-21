<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [TaskController::class,'index'])->name('home');
Route::get('/getAllData', [TaskController::class,'getAllData'])->name('getAllData');
Route::post('/createTask', [TaskController::class,'create']);
Route::post('/completedTask', [TaskController::class,'updateStatus']);
Route::post('/editTask', [TaskController::class,'editTask']);
Route::post('/deleteTask', [TaskController::class,'deleteTask']);





