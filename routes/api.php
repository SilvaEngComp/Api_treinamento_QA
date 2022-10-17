<?php

use App\Http\Controllers\AuthController;
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

Route::prefix('v1')->group(function () {
    // Route::get('auth/codeRescue/{code}', [AuthController::class, 'codeValidation']);
    // Route::get('auth/recoverAccess/{email}', [AuthController::class, 'checkEmailExistente']);
    // Route::patch('/auth/updatePassword/user/{user}', [AuthController::class, 'updatePassword']);
    Route::post('/auth/login', [AuthController::class, 'login']);
});


Route::prefix('v2')->group(
    function () {
        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::prefix('netEmpresa')->group(function () {
                // Route::get("/", [UserController::class, "index"]);
                // Route::get("/{user}", [UserController::class, "show"]);
                Route::post("/cadastro", [UserController::class, "store"]);
                // Route::patch("/{user}", [UserController::class, "update"]);
                // Route::delete("/{user}", [UserController::class, "destroy"]);
            });
        });
    }
);
