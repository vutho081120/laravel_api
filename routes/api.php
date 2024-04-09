<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('category', [ApiController::class, 'index'])->name('api.category.index');
Route::post('category', [ApiController::class, 'addCategory'])->name('api.category.addCategory');
Route::get('category/delete/{id}', [ApiController::class, 'deleteCategory'])->name('api.category.deleteCategory');
Route::get('category/{id}', [ApiController::class, 'categoryId'])->name('api.category.categoryId');
// Route::apiResource('categories', ApiController::class);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function (Request $request) {
        return auth()->user();
    });
    // API route thoát
    Route::post('/logout', [AuthController::class, 'logout']);
});
