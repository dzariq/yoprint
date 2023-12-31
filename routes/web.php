<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

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


Route::get('/update_table', [ExportController::class, 'updateTable']);
Route::get('/', [ExportController::class, 'uploadCSV'])->name('upload.csv');
Route::post('/export', [ExportController::class, 'exportCSV'])->name('export.csv');

