<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BazaartController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminSellerController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\API\UserController as APIUserController;
use App\Http\Controllers\OtherModulesController;
use App\Http\Controllers\User\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Website
Route::get('/',[BazaartController::class,'HomePage'])->name('webpage');
Route::get('/blog',[BazaartController::class,'BlogPage'])->name('blog');
Route::get('/blog-details/{title}',[BazaartController::class,'BlogDetails'])->name('blogdet');
Route::get('/faq-support',[BazaartController::class,'faqsupport'])->name('faqsupport');
Route::view('/privacy-policy','website.privacy_policy')->name('privacy_policy');
Route::view('/terms-and-conditions','website.terms_n_conditions')->name('terms_n_condition');

Route::get('/seller_login',[BazaartController::class,'CheckAuthenticated'])->name('webpage');
Route::get('VerifyEmail',[APIUserController::class,'VerifyEmail']);
Route::get('ResetEmail',[APIUserController::class,'ResetEmail']);
Route::post('updatepassword',[APIUserController::class,'UpdatePassword']);




Route::prefix('admin')->name('admin.')->group(function()
{
    Route::middleware(['guest:admin','PreventBackHistory'])->group(function(){
          Route::view('/login','dashboard.admin.login')->name('login');
          Route::post('/check',[AdminController::class,'check'])->name('check');
          Route::get('/getforgetpassword',[AdminController::class,'getforgetpassword'])->name('getforgetpassword');
          Route::post('/ForgetPassword',[AdminController::class,'ForgetPassword'])->name('forgetpassword');
    });

    Route::middleware(['auth:admin','PreventBackHistory'])->group(function()
    {
        // Route::view('/','dashboard.admin.home')->name('home');
        Route::get('/',[AdminController::class,'Dashboard'])->name('home');
        Route::post('/logout',[AdminController::class,'logout'])->name('logout');

        // Categories
        Route::prefix('/categories')->group(function(){
            Route::get('/', [CategoryController::class,'index'])->name('categories');
            Route::post('/categories/add', [CategoryController::class,'store'])->name('NewCategory');
            Route::post('/verify_category',[CategoryController::class,'VerfiyCategory']);
            Route::post('/UpdateCategory',[CategoryController::class,'UpdateCategory']);
            Route::post('/changestatus',[CategoryController::class,'ChangeStatus'])->name('ChangeCatStatus');
        });

        // Seller Module
        Route::prefix('/seller')->group(function()
        {

            // Seller Requests & Actions
            Route::get('Request',[AdminSellerController::class,'SellerRequestPage'])->name('sellerrequest');
            Route::get('VerifySeller/{sellerid}',[AdminSellerController::class,'VerifySellerPage'])->name('VerifySeller');
            Route::post('requestapproval',[AdminSellerController::class,'Approval'])->name('SellerApproval');

            // Seller List and CRUD
            Route::get('/',[AdminSellerController::class,'SellerList'])->name('SellerList');

            Route::view('/CreateSellerPage','dashboard.admin.seller.CreateSeller')->name('CreateSellerPage');
            Route::post('Create',[AdminSellerController::class,'CreateNewSeller'])->name('SellerCreate');

            Route::post('approve/{id}',[AdminSellerController::class,'Approval'])->name('selleredit');
            Route::post('ChangeSellerStatus',[AdminSellerController::class,'ChangeSellerStatus'])->name('ChangeSellerStatus');

            Route::get('details/{id}',[AdminSellerController::class,'SellerDet'])->name('SellerDetail');

            Route::get('edit/{id}',[AdminSellerController::class,'EditSeller'])->name('EditSellerPage');
            Route::post('updateseller',[AdminSellerController::class,'UpdateSeller'])->name('UpdateSeller');

            Route::get('/export-sellers',[AdminSellerController::class,'exportSellers'])->name('export-sellers');
        });

        // Products Module
        Route::prefix('products')->group(function()
        {
            Route::get('/',[AdminProductController::class,'ProductList'])->name('ProductList');
            Route::get('/AddProduct',[AdminProductController::class,'AddProductPage'])->name('AddProduct');
            Route::post('/CreateProduct',[AdminProductController::class,'CreateProduct'])->name('CreateProduct');
            Route::post('/featuredproducts',[AdminProductController::class,'featuredproducts'])->name('featuredproducts');
            Route::post('/productstatus',[AdminProductController::class,'productstatus'])->name('productstatus');

            Route::get("/ViewProductDet/{id}",[AdminProductController::class,'ProductDetails'])->name("ProductDetails");

            Route::get('/Attributes',[AdminProductController::class,'ProductAttributesList'])->name('ProductAttributes');
            Route::post('/AttributesAdd',[AdminProductController::class,'createattributes'])->name('AddProductAttributes');

            // Later Use
            // Route::get('ProductFilterOptions',[AdminProductController::class,'GetFilterOptionsPage'])->name('ProductFilters');
            // Route::get('/Reviews',[AdminProductController::class,'ProductReviewList'])->name('ProductReviews');
        });

        // Orders Module
        Route::prefix('order')->group(function(){
            Route::get('/OrderStatus',[AdminOrderController::class,'OrderStatusList'])->name('OrderStatusList');
            Route::post('/OrderStatusCreate',[AdminOrderController::class,'OrderStatusCreate'])->name('OrderStatusCreate');
            Route::get('Orderlist',[AdminOrderController::class,'OrderList'])->name('OrderList');
            Route::get('OrderDetails/{id}',[AdminOrderController::class,'OrderDetail'])->name('OrderDetail');
        });

        // Customers Module
        Route::prefix('customers')->group(function(){
            Route::get('/',[AdminCustomerController::class,'CustomerList'])->name('CustomerList');
            Route::get("/ViewcustomerDet/{id}",[AdminCustomerController::class,'CustomerDetails'])->name("CustomerDetails");
            Route::post('/customerstatus',[AdminCustomerController::class,'ChangeUserStatus'])->name('CustomerStatus');
        });

        Route::prefix('settings')->group(function(){

            Route::get('banners',[OtherModulesController::class,'Banners'])->name('banners');
            Route::post('changebannertype',[OtherModulesController::class,'ChangeBannerType']);
            Route::post('addbanners',[OtherModulesController::class,'AddBanners'])->name('postbanners');
            Route::post('updatebanner',[OtherModulesController::class,'UpdateBanner'])->name('updatebanner');
            Route::get('deletebanner/{id}',[OtherModulesController::class,'DeleteBanner'])->name('deletebanner');

            Route::get('faq',[OtherModulesController::class,'GetFAQPage'])->name('faq');
            Route::post('postfaq',[OtherModulesController::class,'PostFAQ'])->name('postfaq');

            Route::get('/contents',[OtherModulesController::class,'GetContentPage'])->name('contents');
            Route::post('/postcontent',[OtherModulesController::class,'PostContent'])->name('postcontent');
            Route::post('/updatecontent',[OtherModulesController::class,'UpdateContent'])->name('updatecontent');
            Route::post('/deleteContent',[OtherModulesController::class,'DeleteContent'])->name('deleteContent');

            Route::get('ViewContentPage/{title}',[OtherModulesController::class,'ViewContent'])->name('ViewContentPage');
        });

        Route::prefix('blogs')->group(function(){
            Route::get('/',[BlogController::class,'index'])->name('blogindex');
            Route::post('/blogpost',[BlogController::class,'AddBlog'])->name('blogpost');
            Route::post('/updateblogpost',[BlogController::class,'UpdateBlog'])->name('updatepostblog');
            Route::post('/statuschange',[BlogController::class,'ChangeStatus'])->name('updatestatuspost');
            Route::post('/deleteblog',[BlogController::class,'Deletepost'])->name('deletepost');
        });
    });
});

Route::prefix('App')->group(function(){
    Route::get('{title}',[OtherModulesController::class,'ViewContent'])->name('ViewContentPage');
});


Route::prefix('testing')->group(function(){
    Route::view('/','TestingPage');
});

Route::get('TestMail',[TestMail::class,'TestMail']);
