<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HospitalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\likeController;
use App\Http\Controllers\API\Post_likeController;

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace' => 'API'], function () {
    Route::post('/register', [AuthController::class, 'register_user']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register/company', [AuthController::class, 'register_company']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(['api','jwt.verify']);
    Route::post('/change/password',[AuthController::class,'change_password'])->middleware(['api','jwt.verify']);
    Route::get('/list/hospitals',[HospitalController::class,'getHospitals']);
    /////////////////////routes
    Route::post('/posts/store', [PostController::class, 'store'])->middleware(['api','jwt.verify']);
    Route::get('/posts/index', [PostController::class, 'index'])->middleware(['api','jwt.verify']);
    Route::put('/posts/update/{id}', [PostController::class, 'update'])->middleware(['api','jwt.verify']);
    Route::delete('/posts/delete/{id}', [PostController::class, 'destroy'])->middleware(['api','jwt.verify']);
    Route::get('/posts/show/{id}', [PostController::class, 'show'])->middleware(['api','jwt.verify']);
    Route::post('/booldType/store', [PostController::class, 'storebooldType'])->middleware(['api','jwt.verify']);
    Route::post('/comments/store/{id}', [PostController::class, 'storeComment'])->middleware(['api','jwt.verify']);
    Route::get('/comments', [PostController::class, 'getComments'])->middleware(['api','jwt.verify']);
    Route::get('/booldType', [PostController::class, 'getTypes'])->middleware(['api','jwt.verify']);
    //  Route::resource('/posts',PostController::class)->middleware(['api','jwt.verify']);



    //Route::get('/profile/{user}', 'ProfileController@show');
    Route::get('/show-profile/{user_id}', [ProfileController::class, 'showProfile']);

    Route::put('/update-profile/{user_id}', [ProfileController::class, 'updateProfile']);
    Route::get('/getLikesCount/{posts_id}',[likeController::class, 'getLikesCount']);
    //Route::post('/updateImage/{user_id}',[ProfileController::class, 'updateImage']);


    //Route::delete('/posts/{post}/unlike', 'PostLikeController@unlike');
    Route::delete('/unlikePost/{user_id}/{posts_id}', [likeController::class, 'unlikePost']);


});





