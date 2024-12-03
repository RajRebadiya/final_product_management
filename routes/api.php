<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ApiController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\StaffController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(ApiController::class)->group(function () {
    Route::post('/categories', 'category_data');
    Route::get('/products', 'product_data');
    Route::post('/product-search', 'search_products');
    Route::post('/product-add', 'product_add');
    Route::post('/all-products-with-pagination', 'all_products_with_pagination');
    Route::post('/all-category-with-pagination', 'all_category_with_pagination');
    Route::post('/category-add', 'category_add');
    Route::post('/delete-product', 'delete_product')->name('delete-product');
    Route::post('/delete-category', 'delete_category')->name('delete-category');
    Route::post('/edit-category', 'edit_category')->name('edit-category');
    Route::post('/edit-product', 'edit_product')->name('edit-product');
    Route::post('/product_stock_update', 'product_stock_update')->name('product_stock_update');
    Route::post('/get_product_with_cat', 'get_product_with_cat')->name('get_product_with_cat');
    Route::post('/new_arrival_product', 'new_arrival_product')->name('new_arrival_product');
    Route::post('/update_product_price', 'update_product_price')->name('update_product_price');
    Route::post('/product_filter', 'product_filter')->name('product_filter');
    // In routes/api.php or routes/web.php
    Route::post('/product-sync', 'productSync');
    Route::post('/update-product-sync', 'updatedProductSync');

    Route::get('/displayAllProducts', 'displayAllProducts')->name('displayAllProducts');





});

Route::controller(StaffController::class)->group(function () {
    Route::post('/api_login', 'api_login')->name('api_login');
    Route::post('/api_logout', 'api_logout')->name('api_logout')->middleware('auth:sanctum');
    Route::get('/get_profile', 'get_profile')->name('get_profile')->middleware('auth:sanctum');
    Route::get('/get_permissions', 'get_permissions')->name('get_permissions')->middleware('auth:sanctum');
    Route::get('/get_config', 'get_config')->name('get_config')->middleware('auth:sanctum');
    Route::post('/get_colors', 'get_colors')->name('get_colors')->middleware('auth:sanctum');
    Route::post('/add_color', 'add_color')->name('add_color')->middleware('auth:sanctum');
});

Route::controller(OrderController::class)->group(function () {
    Route::post('/search_party', 'search_party')->name('search_party');
    Route::post('/create_offer_form', 'create_offer_form')->name('create_offer_form');
    Route::post('/create_offer_order', 'create_offer_order')->name('create_offer_order');
    Route::post('/order_form_list', 'order_form_list')->name('order_form_list');
    Route::post('/delete-temp-order', 'delete_temp_order')->name('delete-temp-order');

});
