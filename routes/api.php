<?php

use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\API\BazaartAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CategoryAPIController;
use App\Http\Controllers\API\ProductAPIController;
use App\Http\Controllers\API\SellerAPIController;
use App\Http\Controllers\API\CartAPIController;
use App\Http\Controllers\API\CollectionsAPIController;
use App\Http\Controllers\API\UserAddressController;
use App\Http\Controllers\API\OrderAPIController;
use App\Http\Controllers\OtherModulesController;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
Auth::routes(['verify' => true]);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', [UserController::class,'login']);
Route::post('register', [UserController::class,'register']);

Route::post("forget_password",[UserController::class,'ForgetPassword']);

Route::middleware('auth:api')->group(function ()
{
    // User
    Route::post('ChangePassword',[UserController::class,'ChangePassword']);
    Route::post('EditProfile',[UserController::class,'EditProfile']);
    Route::get("UserDet",[UserController::class,'UserDet']);

    // DASHBOARD
    // Route::post("SearchAPI",[BazaartAPIController::class,'SearchAPI']);
    Route::get("Banners",[BazaartAPIController::class,'Banners']);
    Route::get("NewBanners",[BazaartAPIController::class,'NewBanners']);
    Route::get('filter',[ProductAPIController::class,'ProductFilterOptions']);
    // Sellers
    Route::get('sellers',[SellerAPIController::class,'sellers']);
    Route::get('sellers/{id}',[SellerAPIController::class,'sellerdetail']);

    // Categories
    Route::get('categories', [CategoryAPIController::class,'categorylist']);
    Route::post('PreferredCategories', [CategoryAPIController::class,'PreferredCategories']);
    Route::get('FilterAttributes',[CategoryAPIController::class,'FilterAttributes']);

    // Products
    Route::post('products',[ProductAPIController::class,'productlist']);
    Route::get('products/{id}',[ProductAPIController::class,'product_detail']);
    Route::post('CheckFavorite',[ProductAPIController::class,'CheckFavorite']);
    Route::post('CheckSaved',[ProductAPIController::class,'CheckSaved']);
    Route::get('SavedProductList',[ProductAPIController::class,'SavedProductList']);

    // Route::get('ProducReview',[ProductAPIController::class,'product_detail']);

    // Carts
    Route::get('CartList',[CartAPIController::class,'CartList']);
    Route::get('AddToCart',[CartAPIController::class,'AddToCart']);
    Route::get('QuantityChanges',[CartAPIController::class,'QuantityChanges']);
    Route::get('RemoveFromCart',[CartAPIController::class,'RemoveFromCart']);
    Route::get('ClearCart',[CartAPIController::class,'ClearCart']);

    // Address
    Route::get('AddressList',[UserAddressController::class,'index']);
    Route::post('AddAddress',[UserAddressController::class,'add']);
    Route::get('EditAddress',[UserAddressController::class,'update']);
    Route::get('DeleteAddress',[UserAddressController::class,'delete']);
    Route::get('DefaultAddress/{id}',[UserAddressController::class,'UpdateDefaultAddress']);
    Route::get('countries',[UserAddressController::class,'Countries']);
    // Orders
    Route::get('CheckOut',[OrderAPIController::class,'ProceedtoCheckOut']);
    Route::post('PlaceOrder',[OrderAPIController::class,'PlaceOrder']);
    Route::get('OrderDetails/{id}',[OrderAPIController::class,'ParticularOrderDet']);
    Route::get('MyOrders',[OrderAPIController::class,'MyOrders']);

    // Collections
    Route::get('MyCollections',[CollectionsAPIController::class,'MyCollections']);
    Route::post('AddCollection',[CollectionsAPIController::class,'AddNewCollection']);
    Route::post('UpdateCollection',[CollectionsAPIController::class,'UpdateCollection']);
    Route::post('RemoveCollection',[CollectionsAPIController::class,'RemoveCollection']);
    Route::post('ContactUs',[ContactUsController::class,'ContactUsPost']);

    Route::get('/logout',[UserController::class,'logout']);
});

Route::get('Collection/{title}',[CollectionsAPIController::class,'ShareCollectionUrl']);
Route::get('faq',[OtherModulesController::class,'FAQ_Api']);

Route::fallback(function(){
    return response()->json(['status'=>false, 'message' => 'Invalid API Please Check!'], 404);
});

