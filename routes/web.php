<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataVisualizationController;
use App\Http\Controllers\ConfigController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
 //   return view('welcome');
//});


Route::get('/', [DataVisualizationController::class, 'index']);

Route::post('/set-database-config', [ConfigController::class, 'setDatabaseConfig']);
Route::get('/get-table-names', [ConfigController::class, 'getTableNames']);
Route::post('/execute-query', [ConfigController::class, 'executeQuery']);
