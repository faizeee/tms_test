<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocalesController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\TranslationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {

Route::get("locales",LocalesController::class);
Route::get("tags",TagsController::class);
Route::prefix("translations")->group(function(){
    Route::get("export/{tag}/{locale?}",[TranslationsController::class,"export"]);
    Route::get("/",[TranslationsController::class,"index"]);
    Route::post("/",[TranslationsController::class,"create"]);
    Route::put("/{content}",[TranslationsController::class,"update"]);
});
});

