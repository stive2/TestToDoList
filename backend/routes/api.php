<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\TacheController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api'
], function ($router) {
    Route::post('auth/login', [ApiController::class, 'authenticate']);
    Route::post('auth/logout', [ApiController::class, 'logout']);
    Route::get('auth/refresh', [ApiController::class, 'refresh']);
    Route::get('auth/user', [ApiController::class, 'get_user']);

    Route::get('taches', [TacheController::class, 'index']);
    Route::get('tachesEnCours', [TacheController::class, 'indexEnCours']);
    Route::get('tachesAchevees', [TacheController::class, 'indexAchevees']);
    Route::get('tachesFaites', [TacheController::class, 'indexFaites']);
    Route::get('historique/{date}', [TacheController::class, 'historique']);
    Route::get('tache/{id}', [TacheController::class, 'show']);
    Route::post('createTache', [TacheController::class, 'store']);
    Route::post('clotureTache', [TacheController::class, 'cloture']);
    Route::put('updateTache/{tache}',  [TacheController::class, 'update']);
    Route::delete('deleteTache/{tache}',  [TacheController::class, 'destroy']);
    Route::put('doneTache/{tache}',  [TacheController::class, 'done']);
});

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('logout', [ApiController::class, 'logout']);
});

Route::get('users', [UserController::class, 'index']);
Route::get('users/{user}', [UserController::class, 'show']);
Route::post('users', [UserController::class, 'store']);
Route::put('users/{user}',  [UserController::class, 'update']);
Route::delete('users/{user}',  [UserController::class, 'destroy']);
