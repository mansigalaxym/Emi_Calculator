<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/',[FrontController::class,'index']);

Route::post('/getRate',[FrontController::class,'interest']);
Route::post('/getAmount',[FrontController::class,'amount']);
Route::post('/getTimet',[FrontController::class,'time']);
