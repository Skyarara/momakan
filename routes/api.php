<?php

use Illuminate\Http\Request;
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

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('details', 'API\UserController@details');
    Route::get('makanan', 'API\MakananController@data');
    Route::get('makanan/menuplan', 'API\MakananController@datamenuplan');
    Route::get('kategori', 'API\KategoriMakananController@data');
    Route::get('vendor', 'API\VendorController@data');

    Route::get('order', 'API\OrderController@data');
    Route::get('order/status/{contract_id}', 'API\OrderController@status');

    Route::post('tambah/orderdetail', 'API\OrderDetailController@tambah');

    Route::post('tambah/employeefood', 'API\EmployeeFoodController@tambah');
    Route::post('ubah/employeefood', 'API\EmployeeFoodController@ubah_status');
    Route::post('ubah/employeefood/food', 'API\EmployeeFoodController@ubah_food');
    Route::get('employeefood', 'API\ListEmployeeFoodController@data');

    Route::post('edit_user', 'API\UpdateUserController@update');

    Route::get('extraemployeefood', 'API\ExtraEmployeeFoodController@data');

    Route::post('tambah/extraemployee', 'API\ExtraEmployeeController@tambah');
    Route::post('ubah/extraemployee', 'API\ExtraEmployeeController@ubah');
    Route::post('hapus/extraemployee', 'API\ExtraEmployeeController@hapus');

    Route::post('ubah/password', 'API\PegawaiController@ubah_password');

    Route::get('employeemenu', 'API\EmployeeMenuController@data');
    Route::post('tambah/employeemenu', 'API\EmployeeMenuController@tambah');
    Route::post('ubah/employeemenu', 'API\EmployeeMenuController@ubah');
    Route::post('hapus/employeemenu', 'API\EmployeeMenuController@hapus');

    Route::get('kontrak', 'API\KontrakController@data');

    //Promo
    Route::get('promo', 'API\PromoController@data');

    //Feddback
    Route::post('tambah/feedback', 'API\FeedbackController@add');

    //invoice 
    Route::get('myinvoice','API\InvoiceController@getMyInvoice');

    //payment
    Route::post('myinvoice/upload', 'API\PaymentController@addPayment');
    
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
