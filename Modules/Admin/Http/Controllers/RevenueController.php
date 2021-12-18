<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Models\Store;


class RevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('admin::revenue.index');
    }

    public function paging_revenue(Request $request)
    {
        $data = $request->all();
        $total_all_order = Order::where('status',1)->count();
        $total_all_customer = Customer::all()->count();
        $total_all_user = User::all()->count();
        $total_all_store = Store::all()->count();


        $output = '';
        if(isset($data['date_from']) && isset($data['date_to'])){
            $list_orders = Order::where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->get();
            $total_money = Order::where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('total_money');
            $total_origin_price = Order::where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('total_origin_price');
        }else{
            $list_orders = Order::where('status', 1)->get();
            $total_origin_price = Order::where('status', 1)->sum('total_origin_price');
            $total_money = Order::where('status', 1)->sum('total_money');
        }
        $total_profit = $total_money - $total_origin_price;
        if ($data['type'] == 1) {
            if (isset($data['date_from']) && isset($data['date_to'])) {
                $total_orders = Order::where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->count();
            }else{
                $total_orders = Order::where('status', 1)->count();
            }
           
            $output.='
                <div class="col-md-12 padd-0" style="display: inherit;">
                    <div class="col-md-3 padd-right-0">
                        <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                            <div class="infobox-icon">
                                <i class="fa fa-shopping-cart cgreen" style="font-size: 45px; color: rgb(64, 228, 64);" aria-hidden="true"></i>
                            </div>
                            <div class="infobox-data">
                                <h3 class="infobox-title cgreen"
                                    style="font-size: 25px; color: rgb(64, 228, 64);">'.$total_orders.'/'.$total_all_order.'</h3>
                                <span class="infobox-data-number text-center" style="font-size: 14px; color: #555;">Số đơn</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 padd-right-0">
                        <div class="report-box " style="border: 1px dotted #ddd; border-radius: 0">
                            <div class="infobox-icon">
                                <i class="fa fa-dollar orange" style="font-size: 45px; color: rgb(185, 14, 14);"></i>
                            </div>
                            <div class="infobox-data">
                                <h3 class="infobox-title orange"
                                    style="font-size: 25px; color: rgb(185, 14, 14);">'.number_format($total_money).'</h3>
                                <span class="infobox-data-number text-center"
                                    style="font-size: 14px; color: #555;">Tổng doanh số</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 padd-right-0">
                        <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                            <div class="infobox-icon">
                                <i class="fa fa-refresh blue" style="font-size: 45px; color: rgb(52, 106, 223);" aria-hidden="true"></i>
                            </div>
                            <div class="infobox-data">
                                <h3 class="infobox-title blue"
                                    style="font-size: 25px; color: rgb(52, 106, 223);">'.number_format($total_origin_price).'</h3>
                                <span class="infobox-data-number text-center"
                                    style="font-size: 14px; color: #555;">Tổng tiền vốn</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 padd-right-0">
                        <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                            <div class="infobox-icon">
                                <i class="fa fa-money cred" style="font-size: 45px; color: rgb(241, 228, 42);"></i>
                            </div>
                            <div class="infobox-data">
                                <h3 class="infobox-title cred"
                                    style="font-size: 25px; color: rgb(241, 228, 42);">'.number_format($total_profit).'</h3>
                                <span class="infobox-data-number text-center" style="font-size: 14px; color: #555;">Tổng lợi nhuận</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 card mb-4 mt-4">
                <div class="card-body">
                <div class="table-responsive ">
                <table class="table table-bordered table-striped" >
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày bán</th>
                        <th>Khách hàng</th>
                        <th>Số lượng</th>
                        <th>Chiết khấu</th>
                        <th>Doanh số</th>
                        <th>Tiền vốn</th>
                        <th>Lợi nhuận</th>
                        <th>Tổng Nợ</th>
                    </tr>
                </thead>
                <tbody>';
            if (!$list_orders->isEmpty()) {
                foreach ($list_orders as $key => $order) {
                    $output.='
                        <tr>
                            <td>'.$order->code.'</td>
                            <td>'.$order->sell_date .'</td>
                            <td>'.$order->customer->name.'</td>
                            <td>'.$order->quantity.'</td>
                            <td>'.number_format($order->coupon).' đ</td>
                            <td>'.number_format($order->total_money).' đ</td>
                            <td>'.number_format($order->total_origin_price).' đ</td>
                            <td>'.number_format($order->total_money-$order->total_origin_price).' đ</td>
                            <td>'.number_format($order->lack).' đ</td>
                        </tr>
                       
                    ';
                }
            }
            $output.=' </tbody>
            </table>
            </div>
                
            </div>
        </div>
            ';
        }else if($data['type'] == 2){
            $list_customers = Customer::all();
            
            if (isset($data['date_from']) && isset($data['date_to'])) {
                $total_customers = Order::where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->distinct('customer_id')->count();
            
            }else{
                $total_customers = Order::where('status', 1)->distinct('customer_id')->count();
            }
           
            $output.='
            <div class="col-md-12 padd-0" style="display: inherit;">
            <div class="col-md-3 padd-right-0">
                <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                    <div class="infobox-icon">
                        <i class="fa fa-users cgreen" style="font-size: 45px; color: rgb(64, 228, 64);" aria-hidden="true"></i>
                    </div>
                    <div class="infobox-data">
                        <h3 class="infobox-title cgreen"
                            style="font-size: 25px; color: rgb(64, 228, 64);">'.$total_customers.'/'.$total_all_customer.'</h3>
                        <span class="infobox-data-number text-center" style="font-size: 14px; color: #555;">Số khách hàng</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 padd-right-0">
                <div class="report-box " style="border: 1px dotted #ddd; border-radius: 0">
                    <div class="infobox-icon">
                        <i class="fa fa-dollar orange" style="font-size: 45px; color: rgb(185, 14, 14);"></i>
                    </div>
                    <div class="infobox-data">
                        <h3 class="infobox-title orange"
                            style="font-size: 25px; color: rgb(185, 14, 14);">'.number_format($total_money).'</h3>
                        <span class="infobox-data-number text-center"
                            style="font-size: 14px; color: #555;">Tổng doanh số</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 padd-right-0">
                <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                    <div class="infobox-icon">
                        <i class="fa fa-refresh blue" style="font-size: 45px; color: rgb(52, 106, 223);" aria-hidden="true"></i>
                    </div>
                    <div class="infobox-data">
                        <h3 class="infobox-title blue"
                            style="font-size: 25px; color: rgb(52, 106, 223);">'.number_format($total_origin_price).'</h3>
                        <span class="infobox-data-number text-center"
                            style="font-size: 14px; color: #555;">Tổng tiền vốn</span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 padd-right-0">
                <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                    <div class="infobox-icon">
                        <i class="fa fa-money cred" style="font-size: 45px; color: rgb(241, 228, 42);"></i>
                    </div>
                    <div class="infobox-data">
                        <h3 class="infobox-title cred"
                            style="font-size: 25px; color: rgb(241, 228, 42);">'.number_format($total_profit).'</h3>
                        <span class="infobox-data-number text-center" style="font-size: 14px; color: #555;">Tổng lợi nhuận</span>
                    </div>
                </div>
            </div>
            </div>
                <div class="col-md-12 card mb-4 mt-4">
                <div class="card-body">
                <div class="table-responsive ">
                <table class="table table-bordered table-striped" >
                <thead>
                    <tr>
                        <th>Tên khách hàng</th>
                        <th>Số đơn</th>
                        <th>Số sản phẩm</th>
                        <th>Chiết khấu</th>
                        <th>Doanh số</th>
                        <th>Tiền vốn</th>
                        <th>Lợi nhuận</th>
                        <th>Tổng Nợ</th>
                    </tr>
                </thead>
                <tbody>';
                if (!$list_customers->isEmpty()) {
                    foreach ($list_customers as $key => $cus) {
                        if (isset($data['date_from']) && isset($data['date_to'])) {
                            $total_order = Order::where('customer_id', $cus->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->count();
                            $total_product = Order::where('customer_id', $cus->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('quantity');
                            $total_discount = Order::where('customer_id', $cus->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('coupon');
                            $total_lack = Order::where('customer_id', $cus->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('lack');
                            $total_money = Order::where('customer_id', $cus->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('total_money');
                            $total_origin_price = Order::where('customer_id', $cus->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('total_origin_price');
                            

                        }else{
                            $total_order = Order::where('customer_id', $cus->id)->where('status', 1)->count();
                            $total_product = Order::where('customer_id', $cus->id)->where('status', 1)->sum('quantity');
                            $total_discount = Order::where('customer_id', $cus->id)->where('status', 1)->sum('coupon');
                            $total_lack = Order::where('customer_id', $cus->id)->where('status', 1)->sum('lack');
                            $total_money = Order::where('customer_id', $cus->id)->where('status', 1)->sum('total_money');
                            $total_origin_price = Order::where('customer_id', $cus->id)->where('status', 1)->sum('total_origin_price');

                        }
                        $total_profit = $total_money - $total_origin_price;
                            $output.='
                            <tr>
                                <td>'.$cus->name.'</td>
                                <td>'.$total_order.'</td>
                                <td>'.$total_product.'</td>
                                <td>'.number_format($total_discount).' đ</td>
                                <td>'.number_format($total_money).' đ</td>
                                <td>'.number_format($total_origin_price).' đ</td>
                                <td>'.number_format($total_profit).' đ</td>
                                <td>'.number_format($total_lack).' đ</td>

                            </tr>
                        
                        ';
                        }   
                    
                }
                $output.=' </tbody>
            </table>
            </div>
                
            </div>
        </div>
            ';

        }else if($data['type'] ==3){
            $list_users = User::all();
            if (isset($data['date_from']) && isset($data['date_to'])) {
                $total_users = Order::where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->distinct('user_id')->count();
            
            }else{
                $total_users = Order::where('status', 1)->distinct('user_id')->count();
            }
           
            $output.='
            <div class="col-md-12 padd-0" style="display: inherit;">
                <div class="col-md-3 padd-right-0">
                    <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                        <div class="infobox-icon">
                            <i class="fa fa-users cgreen" style="font-size: 45px; color: rgb(64, 228, 64);" aria-hidden="true"></i>
                        </div>
                        <div class="infobox-data">
                            <h3 class="infobox-title cgreen"
                                style="font-size: 25px; color: rgb(64, 228, 64);">'.$total_users.'/'.$total_all_user.'</h3>
                            <span class="infobox-data-number text-center" style="font-size: 14px; color: #555;">Số nhân viên</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 padd-right-0">
                    <div class="report-box " style="border: 1px dotted #ddd; border-radius: 0">
                        <div class="infobox-icon">
                            <i class="fa fa-dollar orange" style="font-size: 45px; color: rgb(185, 14, 14);"></i>
                        </div>
                        <div class="infobox-data">
                            <h3 class="infobox-title orange"
                                style="font-size: 25px; color: rgb(185, 14, 14);">'.number_format($total_money).'</h3>
                            <span class="infobox-data-number text-center"
                                style="font-size: 14px; color: #555;">Tổng doanh số</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 padd-right-0">
                    <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                        <div class="infobox-icon">
                            <i class="fa fa-refresh blue" style="font-size: 45px; color: rgb(52, 106, 223);" aria-hidden="true"></i>
                        </div>
                        <div class="infobox-data">
                            <h3 class="infobox-title blue"
                                style="font-size: 25px; color: rgb(52, 106, 223);">'.number_format($total_origin_price).'</h3>
                            <span class="infobox-data-number text-center"
                                style="font-size: 14px; color: #555;">Tổng tiền vốn</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 padd-right-0">
                    <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                        <div class="infobox-icon">
                            <i class="fa fa-money cred" style="font-size: 45px; color: rgb(241, 228, 42);"></i>
                        </div>
                        <div class="infobox-data">
                            <h3 class="infobox-title cred"
                                style="font-size: 25px; color: rgb(241, 228, 42);">'.number_format($total_profit).'</h3>
                            <span class="infobox-data-number text-center" style="font-size: 14px; color: #555;">Tổng lợi nhuận</span>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-md-12 card mb-4 mt-4">
                <div class="card-body">
                <div class="table-responsive ">
                <table class="table table-bordered table-striped" >
                <thead>
                    <tr>
                        <th>Tên nhân viên</th>
                        <th>Số đơn</th>
                        <th>Số sản phẩm</th>
                        <th>Chiết khấu</th>
                        <th>Doanh số</th>
                        <th>Tiền vốn</th>
                        <th>Lợi nhuận</th>
                        <th>Tổng nợ</th>
                    </tr>
                </thead>
                <tbody>';
                if (!$list_users->isEmpty()) {
                    foreach ($list_users as $key => $user) {
                        if (isset($data['date_from']) && isset($data['date_to'])) {
                            $total_order = Order::where('user_id', $user->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->count();
                            $total_product = Order::where('user_id', $user->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('quantity');
                            $total_discount = Order::where('user_id', $user->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('coupon');
                            $total_lack = Order::where('user_id', $user->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('lack');
                            $total_money = Order::where('user_id', $user->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('total_money');
                            $total_origin_price = Order::where('user_id', $user->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('total_origin_price');

                        }else{
                            $total_order = Order::where('user_id', $user->id)->where('status', 1)->count();
                            $total_product = Order::where('user_id', $user->id)->where('status', 1)->sum('quantity');
                            $total_discount = Order::where('user_id', $user->id)->where('status', 1)->sum('coupon');
                            $total_lack = Order::where('user_id', $user->id)->where('status', 1)->sum('lack');
                            $total_money = Order::where('user_id', $user->id)->where('status', 1)->sum('total_money');
                            $total_origin_price = Order::where('user_id', $user->id)->where('status', 1)->sum('total_origin_price');

                        }
                        $total_profit = $total_money - $total_origin_price;

                            $output.='
                            <tr>
                                <td>'.$user->name.'</td>
                                <td>'.$total_order.'</td>
                                <td>'.$total_product.'</td>
                                <td>'.number_format($total_discount).' đ</td>
                                <td>'.number_format($total_money).' đ</td>
                                <td>'.number_format($total_origin_price).' đ</td>
                                <td>'.number_format($total_profit).' đ</td>
                                <td>'.number_format($total_lack).' đ</td>
                            </tr>
                        
                        ';
                        }   
                    
                }
                $output.=' </tbody>
            </table>
            </div>
                
            </div>
        </div>
            ';

        }else if($data['type'] == 4){
            $list_stores = Store::all();
            if (isset($data['date_from']) && isset($data['date_to'])) {
                $total_stores = Order::where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->distinct('store_id')->count();
            
            }else{
                $total_stores = Order::where('status', 1)->distinct('store_id')->count();
            }
           
            $output.='
            <div class="col-md-12 padd-0" style="display: inherit;">
                <div class="col-md-3 padd-right-0">
                    <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                        <div class="infobox-icon">
                            <i class="fa fa-history cgreen" style="font-size: 45px; color: rgb(64, 228, 64);" aria-hidden="true"></i>
                        </div>
                        <div class="infobox-data">
                            <h3 class="infobox-title cgreen"
                                style="font-size: 25px; color: rgb(64, 228, 64);">'.$total_stores.'/'.$total_all_store.'</h3>
                            <span class="infobox-data-number text-center" style="font-size: 14px; color: #555;">Số cửa hàng</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 padd-right-0">
                    <div class="report-box " style="border: 1px dotted #ddd; border-radius: 0">
                        <div class="infobox-icon">
                            <i class="fa fa-dollar orange" style="font-size: 45px; color: rgb(185, 14, 14);"></i>
                        </div>
                        <div class="infobox-data">
                            <h3 class="infobox-title orange"
                                style="font-size: 25px; color: rgb(185, 14, 14);">'.number_format($total_money).'</h3>
                            <span class="infobox-data-number text-center"
                                style="font-size: 14px; color: #555;">Tổng doanh số</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 padd-right-0">
                    <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                        <div class="infobox-icon">
                            <i class="fa fa-refresh blue" style="font-size: 45px; color: rgb(52, 106, 223);" aria-hidden="true"></i>
                        </div>
                        <div class="infobox-data">
                            <h3 class="infobox-title blue"
                                style="font-size: 25px; color: rgb(52, 106, 223);">'.number_format($total_origin_price).'</h3>
                            <span class="infobox-data-number text-center"
                                style="font-size: 14px; color: #555;">Tổng tiền vốn</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 padd-right-0">
                    <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                        <div class="infobox-icon">
                            <i class="fa fa-money cred" style="font-size: 45px; color: rgb(241, 228, 42);"></i>
                        </div>
                        <div class="infobox-data">
                            <h3 class="infobox-title cred"
                                style="font-size: 25px; color: rgb(241, 228, 42);">'.number_format($total_profit).'</h3>
                            <span class="infobox-data-number text-center" style="font-size: 14px; color: #555;">Tổng lợi nhuận</span>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-md-12 card mb-4 mt-4">
                <div class="card-body">
                <div class="table-responsive ">
                <table class="table table-bordered table-striped" >
                <thead>
                    <tr>
                        <th>Tên cửa hàng</th>
                        <th>Số đơn</th>
                        <th>Số sản phẩm</th>
                        <th>Chiết khấu</th>
                        <th>Doanh số</th>
                        <th>Tiền vốn</th>
                        <th>Lợi nhuận</th>
                        <th>Tổng nợ</th>
                    </tr>
                </thead>
                <tbody>';
                if (!$list_stores->isEmpty()) {
                    foreach ($list_stores as $key => $store) {
                        if (isset($data['date_from']) && isset($data['date_to'])) {
                            $total_order = Order::where('store_id', $store->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->count();
                            $total_product = Order::where('store_id', $store->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('quantity');
                            $total_discount = Order::where('store_id', $store->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('coupon');
                            $total_lack = Order::where('store_id', $store->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('lack');
                            $total_money = Order::where('store_id', $store->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('total_money');
                            $total_origin_price = Order::where('store_id', $store->id)->where('status', 1)->where('sell_date', '>=', $data['date_from'])->where('sell_date', '<=', $data['date_to'])->sum('total_origin_price');
                        }else{
                            $total_order = Order::where('store_id', $store->id)->where('status', 1)->count();
                            $total_product = Order::where('store_id', $store->id)->where('status', 1)->sum('quantity');
                            $total_discount = Order::where('store_id', $store->id)->where('status', 1)->sum('coupon');
                            $total_lack = Order::where('store_id', $store->id)->where('status', 1)->sum('lack');
                            $total_money = Order::where('store_id', $store->id)->where('status', 1)->sum('total_money');
                            $total_origin_price = Order::where('store_id', $store->id)->where('status', 1)->sum('total_origin_price');
                            

                        }
                        $total_profit = $total_money - $total_origin_price;

                            $output.='
                            <tr>
                                <td>'.$store->name.'</td>
                                <td>'.$total_order.'</td>
                                <td>'.$total_product.'</td>
                                <td>'.number_format($total_discount).' đ</td>
                                <td>'.number_format($total_money).' đ</td>
                                <td>'.number_format($total_origin_price).' đ</td>
                                <td>'.number_format($total_profit).' đ</td>
                                <td>'.number_format($total_lack).' đ</td>
                            </tr>
                        
                        ';
                        }   
                    
                }
                $output.=' </tbody>
            </table>
            </div>
                
            </div>
        </div>
            ';

        }

        echo $output;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
