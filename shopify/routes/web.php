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

Route::middleware(['auth.shop'])->group(function () {
    Route::get('/', 'ShopifyController@index')->name('home');
    
    Route::match(['get', 'post'], '/import_csv', 'ShopifyController@import_csv')->name('import_csv');
    
//    Route::get('/ftp_import', function() {
//        Artisan::call('importproducts:items');
//        return redirect()->back();
//    })->name('ftp_import');
    
    Route::get('/ftp_import', function() { return view('ftp_import'); })->name('ftp_import');
    Route::get('/ajax_ftp_import', 'ShopifyController@ajax_ftp_import')->name('ajax_ftp_import');
    
    Route::get('/settings/show', 'SettingsController@show')->name('settings');
    Route::post('/settings/update', 'SettingsController@update')->name('settings_update');
    
    Route::resource('product', 'ProductController')->except([
        'index', 'show', 'create', 'store'
    ]);
});