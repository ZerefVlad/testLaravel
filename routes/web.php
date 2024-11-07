<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\RegisterController;

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', RegisterController::class)->name('register');
Route::post('/', [RegisterController::class, 'register']);

Route::get('/link/{token}', LinkController::class)->name('link.page');
Route::post('/link/generate', [LinkController::class, 'generateNewLink'])->name('link.generate');
Route::post('/link/deactivate', [LinkController::class, 'deactivateLink'])->name('link.deactivate');
Route::post('/link/drop', [LinkController::class, 'drop'])->name('link.drop');
Route::post('/link/history', [LinkController::class, 'history'])->name('link.history');
