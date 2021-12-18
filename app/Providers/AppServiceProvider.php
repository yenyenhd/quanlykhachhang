<?php

namespace App\Providers;
use App\Models\Store;
use App\Models\User;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;



use Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*',function($view) {
            
            $app_store = Store::all();
            $setting = Setting::where('status', 1)->first();
            $app_product = Product::all()->count();
            $app_order = Order::all()->count();
            $app_customer = Customer::all()->count();
            
            $view->with('app_store', $app_store)->with('setting', $setting)->with('app_product', $app_product)->with('app_order', $app_order)->with('app_customer', $app_customer);

        });
    }
}
