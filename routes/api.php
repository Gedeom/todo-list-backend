<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1', 'middleware' => 'cors'], function () {
    Route::post('/auth/login', [UserController::class, 'login'])->name('users.login');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/auth/logout', [UserController::class, 'logout'])->name('users.logout');
        Route::post('/auth/checkToken', [UserController::class, 'checkToken'])->name('users.checkToken');
        Route::apiResource('users', UserController::class);
        Route::apiResource('boards', BoardController::class);
        Route::apiResource('lists', ListController::class);
        Route::post('lists/search', [ListController::class, 'search'])->name('lists.search');
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('projects', ProjectController::class);
        Route::apiResource('cards', CardController::class);
        Route::post('cards/search', [CardController::class, 'search'])->name('cards.search');
        Route::put('cards/{cards}/move', [CardController::class, 'moveCard'])->name('cards.move');
        Route::apiResource('activities', ActivityController::class);
        Route::post('activities/search', [ActivityController::class, 'search'])->name('activities.search');
    });
});



