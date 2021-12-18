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

Route::group(['prefix' => 'laravel-filemanager' ], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::get('/', 'AdminController@index')->name('index');
Route::post('/login', 'AdminController@login')->name('admin.login');
Route::get('/logout', 'AdminController@logout')->name('admin.logout');
Route::get('/404', 'AdminController@error')->name('login');
Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', 'AdminController@dashboard')->name('dashboard');
    Route::post('/filter-by-date','AdminController@filter_by_date');
    Route::post('/dashboard-filter','AdminController@dashboard_filter');
    Route::post('/days-order','AdminController@days_order');

    // Đơn hàng
    Route::prefix('order')->group(function () {
        Route::get('/', 'OrderController@index')->name('list.order');
        Route::get('/create', 'OrderController@create')->name('order.add');
        Route::get('/autocomplete_products', 'OrderController@autocomplete_products')->name('autocomplete');
        Route::post('/cms_check_barcode/{barcode}', 'OrderController@cms_check_barcode');
        Route::get('/cms_select_product', 'OrderController@cms_select_product');
        Route::post('/create-customer', 'OrderController@create_customer');
        Route::post('/save_orders/{store_id}', 'OrderController@save_orders');
        Route::get('/detail/{code}', 'OrderController@order_detail')->name('order.detail');
        Route::post('/update-orders/{order_code}', 'OrderController@update_orders');
        Route::get('/print-order/{order_code}', 'OrderController@print_order')->name('print.order');
        Route::post('/export-csv','OrderController@export_csv')->name('order.export_csv');
        Route::get('/{action}/{id}', 'OrderController@action')->name('order.action');

    });

    // Product
     Route::prefix('product')->group(function () {
        Route::get('/', 'ProductController@index')->name('list.product');
        Route::get('/create', 'ProductController@create')->name('product.add');
        Route::post('/create', 'ProductController@store')->name('product.store');
        Route::get('/update/{id}', 'ProductController@edit')->name('product.edit');
        Route::post('/update/{id}', 'ProductController@update')->name('product.update');
        Route::get('/{action}/{id}', 'ProductController@action')->name('product.action');
        Route::post('/export-csv','ProductController@export_csv')->name('product.export_csv');
        Route::post('/create-category', 'ProductController@create_category');
        Route::post('/save-category', 'ProductController@save_category');
        Route::post('/del-category/{id}', 'ProductController@delete_category');

    });
    // Customer
    Route::prefix('customer')->group(function () {
        Route::get('/', 'CustomerController@index')->name('list.customer');
        Route::get('/list', 'CustomerController@list_customer');
        Route::get('/create', 'CustomerController@create')->name('customer.add');
        Route::post('/create', 'CustomerController@store')->name('customer.store');
        Route::get('/detail/{id}', 'CustomerController@detail_customer')->name('customer.detail');
        Route::post('/update/{id}', 'CustomerController@update')->name('customer.update');
        Route::get('/{action}/{id}', 'CustomerController@action')->name('customer.action');
        Route::post('/export-csv','CustomerController@export_csv')->name('customer.export_csv');
    });

    // Loyalty
   
        Route::prefix('loyalty')->group(function () {
            Route::group(['middleware' => ['role:admin']], function () {
                Route::get('/setting', 'LoyaltyController@setting')->name('setting.loyalty');
                Route::post('/save-setting', 'LoyaltyController@save_setting');
                Route::post('/update-setting', 'LoyaltyController@update_setting');
               
                Route::post('/create-customer-card', 'LoyaltyController@create_customer_card');
                Route::post('/update-customer-card', 'LoyaltyController@update_customer_card');
                Route::get('/delete-customer-card/{id}', 'LoyaltyController@delete_card')->name('delete.card');
            });
            Route::get('/customer-card', 'LoyaltyController@setting_card')->name('setting.card');
            Route::get('/customer-info', 'LoyaltyController@customer_info')->name('customer.card');
    
        
    });
    
    // Inventory
    Route::prefix('inventory')->group(function () {
        Route::get('/', 'InventoryController@index')->name('list.inventory');
        Route::get('/list', 'InventoryController@inventory');
    });

    // Revenue
    Route::prefix('revenue')->group(function () {
        Route::get('/', 'RevenueController@index')->name('list.revenue');
        Route::get('/list', 'RevenueController@paging_revenue');
       
    });
    Route::group(['middleware' => ['role:admin']], function () {
        Route::prefix('user')->group(function () {
            Route::get('/', 'AdminUserController@index')->name('list.user');
            Route::get('/create', 'AdminUserController@create')->name('user.add');
            Route::post('/create', 'AdminUserController@store')->name('user.store');
            Route::get('/update/{id}', 'AdminUserController@edit')->name('user.edit');
            Route::post('/update/{id}', 'AdminUserController@update')->name('user.update');
            Route::get('/destroy/{id}', 'AdminUserController@destroy')->name('user.destroy');
        });
    
        // Role
        Route::prefix('role')->group(function () {
            Route::get('/', 'AdminRoleController@index')->name('list.role');
            Route::get('/create', 'AdminRoleController@create')->name('role.add');
            Route::post('/create', 'AdminRoleController@store')->name('role.store');
            Route::get('/update/{id}', 'AdminRoleController@edit')->name('role.edit');
            Route::post('/update/{id}', 'AdminRoleController@update')->name('role.update');
            Route::get('/destroy/{id}', 'AdminRoleController@destroy')->name('role.destroy');
        });
    
        // Permission
        Route::prefix('permission')->group(function () {
            Route::get('/', 'AdminPermissionController@create')->name('permission.add');
            Route::post('/', 'AdminPermissionController@store')->name('permission.store');
            Route::post('/save', 'AdminPermissionController@save')->name('permission.save');
        });
    });

    // User
    
    
});
 

