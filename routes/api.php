<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\ProductController2;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');  // http://127.0.0.1:8000/api/user    => to show user data after login with token

//For Users
Route::prefix('/user')->group(function () {
    Route::post('/register', [UserController::class, 'store']);  // **
    Route::post('/login', [UserController::class, 'login']); // **

    Route::middleware('auth:sanctum','role.user')->group(function () {

        Route::get('/logout', [UserController::class, 'logout']);  // **
        Route::get('/products' , [ProductController2::class , "listActiveProducts"]); // to show active products only to make order //**
        Route::post('/searchProduct' , [ProductController2::class , 'searchProductByName']); // to search product by name //**
    });
});

//For Admins

Route::prefix('admin')->group(function () {
    Route::post('/register', [AdminController::class, 'register']); //**
    Route::post('/login', [AdminController::class, 'login']);//**

    Route::middleware(['auth:sanctum', 'role.admin'])->group(function () {
        Route::get('/logout', [AdminController::class, 'logout']); //**
    });
});


// Admin privileges
Route::middleware('auth:sanctum', 'role.admin' )->group(function () {
    //Categories
    Route::prefix('/categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store']); //**
        Route::get('/', [CategoryController::class, 'index']); //**
        Route::get('/{id}', [CategoryController::class, 'show']); //**
        Route::delete('/{id}', [CategoryController::class, 'destroy']); //**
        Route::put('/{id}', [CategoryController::class, 'update']); //**
    });

    //Products
    Route::prefix('/products')->group(function () {
        Route::get('/', [ProductController2::class, 'index']); //**
        Route::get('/{id}', [ProductController2::class, 'show']); //**
        Route::post('/', [ProductController2::class, 'store']);//**
        Route::patch('/{id}/status', [ProductController2::class, 'changeStatus']);//**
        Route::put('/{id}', [ProductController2::class, 'update']);//**
        Route::delete('/{id}', [ProductController2::class, 'destroy']);//**
    });
});

Route::prefix('/order')->group(function () {
    // User privileges on order
    Route::middleware('auth:sanctum', 'role.user')->group(function () {
        Route::post('/', [OrderController::class, 'store']); //User create order  //**
        Route::get('/myorders' , [OrderController::class , 'myorders']); // User show his orders  //**
        Route::get('/notifications' , [NotificationController::class , 'index']); //User show his notification

    });

    //Admin privileges on order
    Route::middleware('auth:sanctum' , 'role.admin')->group(function() {
        Route::get('/' , [OrderController::class , 'index']);  //**
          Route::get('/order-status' , function() {
            return response()->json([
                'statuses for order' => ['pending' , 'processing','completed' , 'cancelled']
            ]); //**
        });
        Route::patch('/{id}/statusOrder' , [OrderController::class , 'controlStatus']); 
        Route::get('/{id}' , [OrderController::class , 'show']);
        Route::delete('/{id}' , [OrderController::class , 'destroy']);



    });
});

//privileges for superadmin only
Route::middleware('auth:sanctum' , 'role.superadmin')->group(function() {
    Route::delete('/admin/{id}' , [AdminController::class , 'destroy']); //**
    Route::delete('/user/{id}' , [UserController::class , 'destroy']); // **
    Route::get('/all-users' , [UserController::class , 'index']);        // **
    Route::get('/user/{id}' , [UserController::class , 'show'] );   // **
    Route::get('/all-admins' , [AdminController::class , 'index']); //**
    Route::get('/dashboard-stats' , [AdminController::class , 'dashboardStats']); //**

});


// Route::get('/product' , [ProductController::class , "index"])->name('product.index');

