<?php

use App\Http\Controllers\Admin\AirportPickupController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\EntranceTicketController;
use App\Http\Controllers\Admin\GroupTourController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PrivateVanTourController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\Admin\ProductTagController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('categories-list', [CategoryController::class, 'getCategoryList']);
    Route::get('tags-list', [TagController::class, 'getTagList']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('posts', PostController::class);


    Route::apiResource('product-categories', ProductCategoryController::class);
    Route::apiResource('product-sub-categories', ProductSubCategoryController::class);
    Route::apiResource('destinations', DestinationController::class);
    Route::apiResource('cities', CityController::class);
    Route::apiResource('cars', CarController::class);
    Route::apiResource('product-tags', ProductTagController::class);
    Route::apiResource('private-van-tours', PrivateVanTourController::class);
    Route::apiResource('group-tours', GroupTourController::class);
    Route::apiResource('entrance-tickets', EntranceTicketController::class);
    Route::apiResource('airport-pickups', AirportPickupController::class);
});
