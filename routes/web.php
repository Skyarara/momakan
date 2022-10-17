<?php

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

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/login', function () {
    return view('login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/homec', 'HomeController@indexc')->name('homecorporate');

    // Corporate
    Route::get('/corporate', 'CorporateController@index');
    Route::post('/corporate/addCorporate', 'CorporateController@add');
    Route::post('/corporate/editCorporate', 'CorporateController@edit');
    Route::post('/corporate/deleteCorporate', 'CorporateController@delete');

    // Employee
    Route::get('/employee/data/{id}', 'EmployeeController@index');
    Route::post('/employee/addEmployee', 'EmployeeController@add');
    Route::post('/employee/editEmployee', 'EmployeeController@edit');
    route::post('/employee/deleteEmployee', 'EmployeeController@delete');
    Route::post('import', 'EmployeeController@import')->name('contract.import');

    // EmployeeFood
    Route::get('/employeefood/{id}', 'EmployeeFoodController@index');
    Route::post('/employeefood/tambah', 'EmployeeFoodController@tambah');
    Route::post('/employeefood/ubah', 'EmployeeFoodController@ubah');
    Route::post('/employeefood/detail', 'EmployeeFoodController@detail');
    Route::post('/employeefood/hapus', 'EmployeeFoodController@hapus');

    // FoodCategory
    Route::get('kategori', 'KategoriController@index');
    Route::post('addKategori', 'KategoriController@add');
    Route::post('editKategori', 'KategoriController@edit');
    route::post('deleteKategori', 'KategoriController@delete');

    // Contract
    Route::get('/contract', 'ContractController@index');
    Route::post('/contract/addContract', 'ContractController@add');
    Route::post('/contract/editContract', 'ContractController@edit');
    Route::post('/contract/deleteContract', 'ContractController@delete');
    Route::get('/contract/active', 'ContractController@active');
    Route::get('/contract/inactive', 'ContractController@inactive');

    Route::post('/contract/getFood', 'ContractController@getFood');

    // Kontrak
    Route::get('/kontrak', 'KontrakController@index');
    Route::get('/kontrak/detail/{id}', 'DetailKontrakController@list_detail');
    Route::get('/kontrak/detail/{id}/tambah', 'DetailKontrakController@tambah_page');
    Route::get('/kontrak/detail/{id}/{id_dk}/ubah', 'DetailKontrakController@ubah_page');

    // Kontrak Batalkan
    Route::post('/kontrak/batalkan', 'KontrakController@batalkan');

    //kontrak aktifkan
    Route::post('/kontrak/aktifkan', 'KontrakController@aktifkan');

    // Kontrak Pegawai
    Route::get('/kontrak/detail/{id}/{id_dk}/pegawai', 'PegawaiKontrakController@index');
    Route::post('/kontrak/detail/{id}/{id_dk}/pegawai', 'PegawaiKontrakController@tambah');
    Route::post('/kontrak/detail/{id}/{id_dk}/pegawai/hapus', 'PegawaiKontrakController@hapus');
    Route::post('/kontrak/detail/status_change', 'PegawaiKontrakController@status_change');

    // Kontrak Order
    Route::get('/kontrak/order/{id}', 'OrderKontrakController@index')->name('home.order');
    Route::get('/kontrak/order/{id}/tambah', 'OrderKontrakController@tambah_page');
    Route::post('/kontrak/order/{id}/tambah', 'OrderKontrakController@tambah_action');
    Route::post('/kontrak/order/{id}/hapus', 'OrderKontrakController@hapus');
    Route::get('/kontrak/order/{id}/detail/{tanggal}', 'OrderKontrakController@detail');
    Route::get('/kontrak/order/{id}/detail/pegawai/{id_pegawai}', 'OrderKontrakController@detail_pegawai');

    Route::get('/kontrak/detail', function () {
        return redirect('/kontrak');
    });

    Route::post('/kontrak/tambah', 'KontrakController@tambah');

    // Mengecheck apakah ada yang terbaru
    Route::post('/kontrak/tambah/terbaru', 'KontrakController@terbaru');

    Route::post('/kontrak/detail', 'KontrakController@detail');
    Route::post('/kontrak/ubah', 'KontrakController@ubah');
    Route::post('/kontrak/hapus', 'KontrakController@hapus');

    Route::post('/kontrak/detail/{id}/tambah', 'DetailKontrakController@tambah_detail');
    Route::post('/kontrak/detail/{id}/{id_dk}/ubah', 'DetailKontrakController@ubah_detail');
    Route::post('/kontrak/detail/{id}/hapus', 'DetailKontrakController@hapus_detail');
    Route::post('/kontrak/detail/{id}/detail', 'DetailKontrakController@data_detail');

    // Order
    Route::get('/contract/order/{id}', 'OrderController@index');
    Route::get('/contract/order', function () {
        return redirect()->back();
    });

    Route::get('/contract/order/{id}/tambah', 'OrderController@tambah');
    Route::get('/contract/order/{id}/detail', 'OrderController@detail');
    Route::post('/contract/order/{id}/tambah', 'OrderController@tambah_action');

    //invoice
    Route::get('/kontrak/faktur/{id}', 'InvoiceController@index');
    Route::post('/faktur/tambah', 'InvoiceController@tambah');
    Route::post('/faktur/edit', 'InvoiceController@edit');
    Route::post('/faktur/delete', 'InvoiceController@delete');
    Route::get('/kontrak/faktur/print/{id}', 'InvoiceController@printview');
    Route::get('/kontrak/faktur/print_pegawai/{id}', 'InvoiceController@printview_pegawai');
    Route::get('/kontrak/faktur/detail/{id}', 'InvoiceController@detail');

    Route::get('/kontrak/faktur/email/{id}', 'InvoiceController@sendEmail');

    //payment
    Route::get('/pembayaran', 'PaymentController@index');
    Route::get('pembayaran/get_invoice', 'PaymentController@get_invoice');
    Route::post('pembayaran/tambah', 'PaymentController@tambah');
    Route::post('pembayaran/edit', 'PaymentController@edit');
    Route::post('pembayaran/delete', 'PaymentController@delete');

    // Vendor
    Route::get('/vendoor', 'VendorController@index');
    Route::post('/addVendoor', 'VendorController@add');
    Route::post('/editVendoor', 'VendorController@edit');
    Route::post('/deleteVendoor', 'VendorController@delete');

    // // Food
    // Route::get('/makanan', 'MakananController@index')->name('makanan_index');
    // Route::get('/makanan/tambah', function () {
    //     return redirect('/makanan');
    // });
    // Route::post('/makanan/tambah', 'MakananController@tambah')->name('makanan_tambah');
    // Route::get('/makanan/hapus', function () {
    //     return redirect('/makanan');
    // });
    // Route::post('/makanan/hapus', 'MakananController@hapus')->name('makanan_hapus');
    // Route::get('/makanan/ubah', function () {
    //     return redirect('/makanan');
    // });
    // Route::post('/makanan/ubah', 'MakananController@ubah')->name('makanan_ubah');
    // Route::get('/makanan/detail', function () {
    //     return redirect('/makanan');
    // });
    // Route::post('/makanan/detail', 'MakananController@detail')->name('makanan_detail');



    // Menu
    Route::get('/menu', 'MenuController@index');

    Route::get('/menu_makanan', 'MenuMakananController@index');
    Route::get('/menu_makanan/tambah', function () {
        return redirect('/menu_makanan');
    });
    Route::post('/menu_makanan/tambah', 'MenuMakananController@tambah')->name('menu_makanan_tambah');
    Route::get('/menu_makanan/hapus', function () {
        return redirect('/menu_makanan');
    });
    Route::post('/menu_makanan/hapus', 'MenuMakananController@hapus')->name('menu_makanan_hapus');
    Route::get('/menu_makanan/ubah', function () {
        return redirect('/menu_makanan');
    });
    Route::post('/menu_makanan/ubah', 'MenuMakananController@ubah')->name('menu_makanan_ubah');
    Route::post('/menu_makanan/detail', 'MenuMakananController@detail')->name('menu_makanan_detail');

    Route::post('/menu_makanan/change', 'MenuMakananController@change');

    //Menu Plan
    Route::get('/menu_plan', 'MenuPlanController@index')->name('index.menu_plan');
    Route::get('/menu_plan/tambah', 'MenuPlanController@tambah_page')->name('tambah.plan');
    Route::post('/menu_plan/tambah/data', 'MenuPlanController@list_menu');
    Route::get('/menu_plan/ubah/{id}', 'MenuPlanController@ubah_page');
    Route::post('/menu_plan/tambah/cek', 'MenuPlanController@check_data');
    Route::post('/menu_plan/delete', 'MenuPlanController@delete')->name('delete.menu_plan');
    Route::post('/plan/tambah', 'MenuPlanController@tambah')->name('tambah.menu_plan');
    Route::post('/plan/ubah', 'MenuPlanController@ubah')->name('ubah.menu_plan');


    // Schedule Menu_ID
    Route::post('/menu_makanan/tambah/notifikasi', 'MenuMakananController@tambah_notifikasi');

    // Makanan Paket
    Route::get('/makanan_paket', 'MenuController@makanan_paket')->name('home.paket');
    Route::get('/makanan_paket/tambah', 'MenuController@tambah_paket_page')->name('tambah.paket');
    Route::post('/paket/tambah', 'MenuController@tambah_paket');
    Route::get('/makanan_paket/edit/{id}', 'MenuController@ubah_page');
    Route::post('/update/paket/{id}', 'MenuController@ubah')->name('ubah.paket');
    Route::post('/makanan_paket/delete', 'MenuController@delete');
    Route::post('/makanan_paket/change', 'MenuController@change');
    Route::get('/makanan_paket/read/{id}', 'MenuController@read');

    //promo
    Route::get('/promo', 'PromoController@index');
    Route::post('/promo/add', 'PromoController@add');
    Route::post('/promo/delete', 'PromoController@delete');
    Route::post('/promo/ubah', 'PromoController@ubah');

    //user
    Route::get('/user', 'UserController@index');
    Route::post('/user/edituser', 'UserController@edit');

    //profile
    Route::get('/profile/{id}', 'ProfileController@index');
    Route::post('/profile/edit', 'ProfileController@ubah');
    Route::get('/profile/password/{id}', 'ProfileController@pass');
    Route::post('/profile/password/edit/{id}', 'ProfileController@ubahpass');

    //Laporan
    Route::get('/laporan', 'LaporanController@index');
    Route::get('/laporan/detail/{id}', 'LaporanController@detail');

    //config
    Route::get('/laporan/config', 'ConfigController@index');
    Route::post('/laporan/addconfig', 'ConfigController@add');
    Route::post('/laporan/deleteconfig', 'ConfigController@delete');
    Route::post('/laporan/editconfig', 'ConfigController@edit');

    // Load Picture
    Route::get('/image/{image}', function ($image = null) {
        $file = Storage::disk('local')->get('/storage/images/' . $image);
        $mimetype = Storage::disk('local')->mimeTypore('/storage/images/' . $image);
        return response($file, 200)->header('Content-Type', $mimetype);
    });


    //Vendor Access
    Route::get('/menuku', 'Vendor\VendorController@menuku');
    Route::get('/orderku', 'Vendor\OrderController@orderku');

    // Notifikasi
    Route::get('/notifikasi', 'NotifikasiController@index');
    Route::post('/notifikasi', 'NotifikasiController@send');
    Route::post('/notifikasi/addnotifikasi', 'NotifikasiController@add');
    route::post('/notifikasi/deletenotifikasi', 'NotifikasiController@delete');
    Route::post('/notifikasi/editnotifikasi', 'NotifikasiController@edit');

    // Feedback
    Route::get('/feedback', 'FeedbackController@index');

    // Rekening
    Route::get('/rekening', 'RekeningController@index');
    Route::post('/rekening/add', 'RekeningController@add');
    Route::post('/rekening/edit', 'RekeningController@edit');
    Route::post('/rekening/delete', 'RekeningController@delete');
});
