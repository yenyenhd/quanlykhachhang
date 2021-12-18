<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Loyalty;
use App\Models\OrderDetail;

use App\Models\Customer;
use Carbon\Carbon;
use PDF;
use App\Exports\ExportOrder;
use Excel;
use App\Traits\Delete;
use Auth;
class OrderController extends Controller
{
    use Delete;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $orders = Order::orderby('id', 'DESC')->get();
        return view('admin::order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $list_users = User::all();
        $list_customers = Customer::all();
        $inventory = Inventory::all();
        return view('admin::order.add', compact('list_users', 'list_customers', 'inventory'));
    }

    public function autocomplete_products(Request $request)
    {
        $data = $request->get('term');
        $products = Product::where('status',1)->where('code','LIKE','%'.$data.'%')->orwhere('name','LIKE','%'.$data.'%')->get();
        echo json_encode($products);
    }
    public function cms_check_barcode($keyword)
    {
        $products = Product::where('status',1)->where('code', $keyword)->get();
        if (count($products) == 1)
            echo $products[0]['id'];
        else
            echo 0;
    }

    public function cms_select_product(Request $request)
    {
        $data = $request->all();
        $product = Product::where('id', $data['id'])->first();
        $countitem = Product::where('id', $data['id'])->count();
        $inventory = Inventory::where(['store_id' => $data['store_id'], 'product_id' => $data['id']])->first();
        if (isset($product) && $countitem != 0) {
            ob_start(); ?>
            <tr data-id="<?php echo $product->id; ?>">
                <td class="text-center seq"><?php echo $data['seq']; ?></td>
                <td><?php echo $product['code']; ?></td>
                <td><?php echo $product['name']; ?></td>
                <td class="text-center" style="max-width: 30px;">
                    <input style="max-height: 22px;" type="text" class="txtNumber form-control quantity_product_order text-center" value="1" onkeyup="myFunction()">
                    <input class="stock_product" type="hidden" value="<?php echo $inventory->quantity ?>">
                </td>
                <td class="text-center price-order"><?php echo number_format($product['sell_price']); ?></td>
                <td style="display: none;"
                    class="text-center price-order-hide"><?php echo $product['sell_price']; ?></td>
                <td class="text-center total-money"></td>
                <td class="text-center"><i style="cursor:pointer" class="fa fa-trash-o del-pro-order"></i></td>
            </tr>
            
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            echo $html;
        }
    }

    public function create_customer(Request $request)
    {

        $data = $request->all();
        $customer = new Customer();
        $customer->name = $data['name'];
        $customer->phone = $data['phone'];
        $customer->email = $data['email'];
        $customer->address = $data['address'];
        $data['birthday'] = gmdate("Y-m-d H:i:s", strtotime(str_replace('/', '-', $data['birthday'])) + 7 * 3600);
        $customer->birthday = $data['birthday'];
        $customer->gender = $data['gender'];
        $customer->user_init = Auth::id();
        $customer->user_update = Auth::id();

        Customer::where('code', 'like','KH%')->max('code');
        $max_customer_code = Customer::get()->count();
        $max_code = (int)(str_replace('KH', '', $max_customer_code)) + 1;
        if ($max_code < 10) {
            $data['code'] = 'KH00000' . ($max_code);
        } else if ($max_code < 100) {
            $data['code'] = 'KH0000' . ($max_code);
        } else if ($max_code < 1000){
            $data['code'] = 'KH000' . ($max_code);
        } else if ($max_code < 10000) {
            $data['code'] = 'KH00' . ($max_code);
        } else if ($max_code < 100000) {
            $data['code'] = 'KH0' . ($max_code);
        } else if ($max_code < 1000000) {
            $data['code'] = 'KH' . ($max_code);
        }
        $customer->code = $data['code'];
        $customer->save();

        
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function save_orders($store_id, Request $request)
    {
        if($store_id==Auth::user()->store_id){
            $output='';
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $today = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
            $order = $request->all();
            $detail_order_temp = $order['detail_order'];
            if (empty($order['sell_date'])) {
                $order['sell_date'] = $today;
            } else {
                $order['sell_date'] = $order['sell_date'];
            }
           
            $user_id = Auth::id();
            $total_price = 0;
            $total_origin_price = 0;
            $total_quantity = 0;
           
            $setting_point = Setting::where('status', 1)->first();
            $loyalty = Loyalty::all();
            $order['coupon'] = ($order['coupon']=='NaN') ? 0 : $order['coupon'];
            
            if(isset($setting_point)){
                $point_money = $setting_point->point_money;
            }else{
                $point_money = 0;
            }

            if ($order['status'] == 1)
                foreach ($order['detail_order'] as $item) {
                    $inventory = Inventory::where(['store_id' => $store_id, 'product_id' => $item['id']])->first();
                    if(!empty($inventory)){
                        $quantity = $inventory['quantity'] - $item['quantity'];
                        Inventory::where(['store_id' => $store_id, 'product_id' => $item['id']])->update([
                            'quantity' => $quantity
                        ]);

                        $product = Product::find($item['id']);
                        $slg_pro = Inventory::where('product_id', $item['id'])->sum('quantity');
                        $sold = $product['sold'] + $item['quantity'];
                        Product::find($item['id'])->update(['quantity'=>$slg_pro, 'sold' => $sold]);
                        $item['price'] = $product['sell_price'];
                        $total_price += $item['price']*$item['quantity'];
                       
                        $total_origin_price += $product['origin_price']*$item['quantity'];
                        $total_quantity +=$item['quantity'];
                        $detail_order[] = $item;
                    }    
                }
            else
                foreach ($order['detail_order'] as $item) {
                    $product = Product::find($item['id']);
                    $item['price'] = $product['sell_price'];
                    $total_price += $item['price']*$item['quantity'];
                   
                    $total_origin_price += $product['origin_price']*$item['quantity'];
                    $total_quantity +=$item['quantity'];
                    $detail_order[] = $item;
                }

            if(isset($setting_point)){
                $point = $total_price/$setting_point->money_point;
            }else{
                $point = 0;
            }
            
            if($loyalty->count() > 0){
                foreach($loyalty as $item){
                    $loy = Loyalty::where('customer_id',$order['customer_id'])->first();
                    if($loy){
                        if($order['use_point'] > $loy->total_point){
                            $order['use_point'] = 0;
                            $output.='
                            <div class="alert alert-danger" role="alert">Điểm không đủ. Vui lòng chọn lại.</div>';
                        }else{
                            $order['use_point'] = ($order['use_point']=='') ? 0 : $order['use_point'];
                        }

                    }else{
                        $order['use_point'] = 0;
                    }
                }
            }else{
                $order['use_point'] = 0;
            }
            $order['total_price'] = $total_price;
            $order['total_origin_price'] = $total_origin_price;
            $order['total_money'] = $total_price-$order['coupon'] - $order['use_point']*$point_money;
            $order['quantity'] = $total_quantity;
            $order['lack'] = $order['total_money'] - $order['customer_pay'] > 0 ? $order['total_money'] - $order['customer_pay'] : 0;
            $order['user_id'] = Auth::id();
            $order['store_id'] = $store_id;
            $order['point'] = $point;
            $order['detail_order'] = json_encode($detail_order);
            Order::where('code', 'like','HD%')->max('code');
            $max_order_code = Order::get()->count();
            $max_code = (int)(str_replace('HD', '', $max_order_code)) + 1;
            if ($max_code < 10) {
                $order['code'] = 'HD00000' . ($max_code);
            } else if ($max_code < 100) {
                $order['code'] = 'HD0000' . ($max_code);
            } else if ($max_code < 1000){
                $order['code'] = 'HD000' . ($max_code);
            } else if ($max_code < 10000) {
                $order['code'] = 'HD00' . ($max_code);
            } else if ($max_code < 100000) {
                $order['code'] = 'HD0' . ($max_code);
            } else if ($max_code < 1000000) {
                $order['code'] = 'HD' . ($max_code);
            }

            $id = Order::insertGetId($order);

            // Insert table OrderDetail
            $detail = array();
            $detail['order_code'] = $order['code'];
            foreach($detail_order_temp as $item){
                $order_detail = $detail;
               
                $product = Product::find($item['id']);
                $order_detail['product_id'] = $item['id'];
                $order_detail['price'] = $product['sell_price'];
                $order_detail['quantity'] = $item['quantity'];
                OrderDetail::create($order_detail);

            }
            $percent_discount = $order['coupon']/$total_price;

            // Report
            $report = Report::all();
            if ($order['status'] == 1){
               
                // Report

                if($report->count() > 0){
                    foreach ($report as $item) {
                        $res = Report::where(['store_id' => $store_id, 'order_date' => $order['sell_date']])->first();
                    }
                        
                    if($res && $res->count() > 0){
                        $sales = $res->sales;
                        $profit = $res->profit;
                        $quantity = $res->quantity;
                        $sales +=  $order['total_money'];
                        $profit+= ($order['total_money'] - $order['total_origin_price']);
                        $quantity+= $order['quantity'];
                        $dataUpdate = [
                            'sales' => $sales,
                            'profit' => $profit,
                            'quantity' => $quantity,
                            'total_order' => $res->total_order + 1,
                        ];
                        Report::where(['store_id' => $store_id, 'order_date' => $order['sell_date']])->update($dataUpdate);
                    }else{
                        
                        Report::create([
                            'store_id' => $store_id,
                            'order_date' => $order['sell_date'],
                            'sales' => $order['total_money'],
                            'profit' =>$order['total_money'] - $order['total_origin_price'],
                            'quantity' => $total_quantity,
                            'total_order' => 1,
                        ]);
                    }
                   
                }else{
                    Report::create([
                        'store_id' => $store_id,
                        'order_date' => $order['sell_date'],
                        'sales' => $order['total_money'],
                        'profit' =>$order['total_money'] - $order['total_origin_price'],
                        'quantity' => $total_quantity,
                        'total_order' => 1,
                    ]);
                }

                // Điểm
                if($loyalty->count() > 0){
                    foreach ($loyalty as $item) {
                        $loy = Loyalty::where('customer_id', $order['customer_id'])->first();
                    }
                        if($loy){
                            $money = $loy->total_money;
                            $point = $loy->total_point;
                            $money +=  $order['total_money'];
                            $point+= ($order['point'] - $order['use_point']);
                            $dataUpdate = [
                                'total_money' => $money,
                                'total_point' => $point,
                            ];
                            Loyalty::where('customer_id', $order['customer_id'])->update($dataUpdate);
                        }else{
                            Loyalty::create([
                                'customer_id' => $order['customer_id'],
                                'total_money' => $order['total_money'],
                                'total_point' => $order['point'],
                            ]);
                        }
                        
                }else{
                    Loyalty::create([
                        'customer_id' => $order['customer_id'],
                        'total_money' => $order['total_money'],
                        'total_point' => $order['point'],
                    ]);
                }
                
                
            }
            
        }
        echo $output;
    }
    public function order_detail($code)
    {

        $order = Order::where('code', $code)->first();
        $order_detail = OrderDetail::where('order_code', $code)->get();
        return view('admin::order.order_detail', compact('order_detail', 'order'));
    }
    public function update_orders($order_code, Request $request)
    {
        $data = $request->all();
        $order_detail = OrderDetail::where('order_code', $order_code)->get();
        $order = Order::where('code', $order_code)->first();
        $order->status = $data['status'];
        $order->save();
        
        // Update kho
        foreach ($order_detail as $item) {
            $inventory = Inventory::where(['store_id' => $data['store_id'], 'product_id' => $item->product_id])->first();
            if (!empty($inventory)) {
                $quantity = $inventory['quantity'] - $item['quantity'];
                Inventory::where(['store_id' => $data['store_id'], 'product_id' => $item->product_id])->update([
                    'quantity' => $quantity
                ]);

                $product = Product::find($item->product_id);
                $slg_pro = Inventory::where('product_id', $item->product_id)->sum('quantity');
                $sold = $product->sold + $item->quantity;
                Product::find($item->product_id)->update(['quantity'=>$slg_pro, 'sold' => $sold]);
            }
        }

        // Update điểm
        $loyalty = Loyalty::all();
        if($loyalty->count() > 0){
            foreach ($loyalty as $item) {
                $loy = Loyalty::where('customer_id', $order['customer_id'])->first();
            }
                if($loy){
                    $money = $loy->total_money;
                    $point = $loy->total_point;
                    $money +=  $order['total_money'];
                    $point+= ($order['point'] - $order['use_point']);
                    $dataUpdate = [
                        'total_money' => $money,
                        'total_point' => $point,
                    ];
                    Loyalty::where('customer_id', $order['customer_id'])->update($dataUpdate);
                }else{
                    Loyalty::create([
                        'customer_id' => $order['customer_id'],
                        'total_money' => $order['total_money'],
                        'total_point' => $order['point'],
                    ]);
                }
                
            
        }else{
            Loyalty::create([
                'customer_id' => $order['customer_id'],
                'total_money' => $order['total_money'],
                'total_point' => $order['point'],
            ]);
        }

        // Update report
        $report = Report::all();
        if($report->count() > 0){
            foreach ($report as $item) {
                $res = Report::where(['store_id' => $data['store_id'], 'order_date' => $order['sell_date']])->first();
            }
                
            if($res){
                $sales = $res->sales;
                $profit = $res->profit;
                $quantity = $res->quantity;
                $total_order = $res->total_order;
                $sales +=  $order['total_money'];
                $profit+= ($order['total_money'] - $order['total_origin_price']);
                $quantity+= $order['quantity'];

                $dataUpdate = [
                    'sales' => $sales,
                    'profit' => $profit,
                    'quantity' => $quantity,
                    'total_order' => $total_order + 1,
                ];
                Report::where(['store_id' => $data['store_id'], 'order_date' => $order['sell_date']])->update($dataUpdate);
            }else{
                Report::create([
                    'store_id' => $data['store_id'],
                    'order_date' => $order['sell_date'],
                    'sales' => $order['total_money'],
                    'profit' =>$order['total_money'] - $order['total_origin_price'],
                    'quantity' => $order['quantity'],
                    'total_order' => 1,
                ]);
            }
                
        }else{
            Report::create([
                'store_id' => $data['store_id'],
                'order_date' => $order['sell_date'],
                'sales' => $order['total_money'],
                'profit' =>$order['total_money'] - $order['total_origin_price'],
                'quantity' => $order['quantity'],
                'total_order' => 1,
            ]);
        }
    }

    public function print_order($order_code){
		$pdf = \App::make('dompdf.wrapper');
		$pdf->loadHTML($this->print_order_convert($order_code));
		return $pdf->stream();
	}

    public function print_order_convert($order_code){
		$order_details = OrderDetail::where('order_code',$order_code)->get();
		$order = Order::where('code',$order_code)->first();
		
		$customer = Customer::where('id',$order->customer_id)->first();

		$order_details_product = OrderDetail::where('order_code', $order_code)->get();


		$output = '';

            $output.='<style>body{
                font-family: DejaVu Sans;
            }
            .table-styling{
                border:1px solid #000;
            }
            .table-styling tbody tr td{
                border:1px solid #000;
            }
            </style>
            <h1><centerCông ty TNHH một thành viên ABCD</center></h1>
            <h4><center>Độc lập - Tự do - Hạnh phúc</center></h4>
            <h3><center>Hóa đơn</center></h3>

            <p>Người đặt hàng</p>
            <table class="table-styling">
            <thead>
            <tr>
            <th>Tên khách đặt</th>
            <th>Số điện thoại</th>
            <th>Email</th>
            </tr>
            </thead>
            <tbody>';

		$output.='		
		<tr>
		<td>'.$customer->name.'</td>
		<td>'.$customer->phone.'</td>
		<td>'.$customer->address.'</td>

		</tr>';

		$output.='				
		</tbody>

		</table>

		<p>Đơn hàng đặt</p>
		<table class="table-styling">
		<thead>
		<tr>
		<th>Mã sản phẩm</th>
		<th>Tên sản phẩm</th>
		<th>Số lượng</th>
		<th>Giá sản phẩm</th>
		<th>Thành tiền</th>
		</tr>
		</thead>
		<tbody>';

		foreach($order_details_product as $key => $product){

			$output.='		
			<tr>
			<td>'.$product->product->code.'</td>
			<td>'.$product->product->name.'</td>
            <td>'.$product->quantity.'</td>
			<td>'.number_format($product->price,0,',','.').' đ'.'</td>
			<td>'.number_format($product->price*$product->quantity,0,',','.').' đ'.'</td>
			</tr>';
		}

		$output.= '<tr>
		<td colspan="2">
		<p>Ngày: '.$order->sell_date.'</p>
		<p>Tiền hàng: '.number_format($order->total_price,0,',','.').' đ'.'</p>
		<p>Giảm giá: '.number_format($order->coupon,0,',','.').' đ'.'</p>
		<p>Điểm đã dùng: '.$order->use_point.' điểm'.'</p>
		<p>Tổng cộng: '.number_format($order->total_money,0,',','.').' đ'.'</p>
		<p>Khách đã trả: '.number_format($order->customer_pay,0,',','.').' đ'.'</p>
		<p>Khách còn nợ: '.number_format($order->lack,0,',','.').' đ'.'</p>
		</td>
		</tr>';
		$output.='				
		</tbody>

		</table>

		<p>Ký tên</p>
		<table>
		<thead>
		<tr>
		<th width="200px">Người lập phiếu</th>
		<th width="800px">Người nhận</th>

		</tr>
		</thead>
		<tbody>';

		$output.='				
		</tbody>

		</table>

		';


		return $output;

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
    public function action($action, $id)
    {
        if($action){
            $order = Order::find($id);
            switch ($action)
            {
                case 'delete':
                    $this->deleteTrait($id, $order );
                    break;
            }

        }
        return redirect('order');
    }
    public function export_csv(){
        return Excel::download(new ExportOrder , 'order.xlsx');
    }

}
