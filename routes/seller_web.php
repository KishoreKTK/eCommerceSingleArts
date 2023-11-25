<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BazaartController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\SellerCategoryController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Models\Order;
use App\Models\Seller;

Route::prefix('seller')->name('seller.')->group(function(){

    Route::middleware(['guest:seller','PreventBackHistory'])->group(function(){
        Route::view('/login','dashboard.seller.login')->name('login');
        Route::view('/register','dashboard.seller.register')->name('register');
        Route::post('/create',[SellerController::class,'create'])->name('create');
        Route::post('/check',[SellerController::class,'check'])->name('check');
    });

    Route::middleware(['auth:seller','PreventBackHistory'])->group(function(){

        // Route::view('/','dashboard.seller.home')->name('home');
        Route::get('/',[SellerController::class,'Dashboard'])->name('home');
        Route::post('logout',[SellerController::class,'logout'])->name('logout');

        Route::get('categories',[SellerCategoryController::class,'CategoryList'])->name('categories');
        Route::post('categories/add', [SellerCategoryController::class,'store'])->name('NewCategory');


        Route::prefix('products')->group(function()
        {
            Route::get('/',[SellerProductController::class,'ProductList'])->name('ProductList');
            Route::get("/ViewProductDet/{id}",[SellerProductController::class,'ProductDetails'])->name("ProductDetails");

            Route::get('AddProduct',[SellerProductController::class,'AddProductPage'])->name('AddProduct');
            Route::post('/CreateProduct',[SellerProductController::class,'CreateProduct'])->name('CreateProduct');

            Route::get('/CutomPrice/{id}',[SellerProductController::class,'CutomPrice'])->name('CutomPrice');
            Route::post('PostCustomPrice',[SellerProductController::class,'PostCustomPrice'])->name('PostCustomPrice');
            Route::get('/GetAttributes',[SellerProductController::class,'GetAttributes']);

            Route::post('/featuredproducts',[SellerProductController::class,'featuredproducts'])->name('featuredproducts');
            Route::post('/productstatus',[SellerProductController::class,'productstatus'])->name('productstatus');

            Route::get('/Reviews',[SellerProductController::class,'ProductReviewList'])->name('ProductReviews');

        });

        Route::prefix('orders')->group(function(){
            Route::get('OrderList',[SellerOrderController::class,'OrderList'])->name('OrderList');
            Route::get('GetOrderStatus',[SellerOrderController::class,'GetOrderStatus']);
            Route::post('UpdateOrderStatus',[SellerOrderController::class,'UpdateOrderStatus']);
            Route::get('GetOrderTrack',[SellerOrderController::class,'GetOrderTrack']);
        });
    });
});
