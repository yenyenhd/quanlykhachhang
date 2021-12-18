<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Loyalty;
use DB;
use Auth;
use App\Http\Requests\RequestCustomer;
use App\Exports\ExportCustomer;
use Excel;
use App\Traits\Delete;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use Delete;
    public function index()
    {
        return view('admin::customer.index');
    }
    public function list_customer()
    {
        $customers = Customer::all();
        $order = Order::all();
        $output = '';
        $output.='
        <table class="table table-bordered table-striped" >
                <thead>
                <tr>
                    <th class="text-center">Mã KH</th>
                    <th class="text-center">Tên KH</th>
                    <th class="text-center">Điện thoại</th>
                    <th class="text-center">Địa chỉ</th>
                    <th class="text-center">Lần cuối mua hàng</th>
                    <th class="text-center" style="background-color: #fff;">Tổng tiền hàng</th>
                    <th class="text-center">Tổng nợ</th>
                    <th></th>
                </tr>
                </thead>
                    <tbody class="ajax-loadlist-customer">
                    ';

                    if (isset($customers)) {
                        foreach ($customers as $cus) {
                            $max_date = Order::where('customer_id', $cus->id)->max('sell_date');
                            $total_money = Order::where('customer_id', $cus->id)->sum('total_money');
                            $total_lack = Order::where('customer_id', $cus->id)->sum('lack');
                        
                            $output.='
                                <tr id="tr-item-'.$cus->id.' }}">
                                    <td class="text-center tr-detail-item"
                                        style="cursor: pointer; color: #1b6aaa;"><a href="'.route('customer.detail', [ 'id' => $cus->id]).'">'.$cus->code.'</a></td>
                                    <td class="text-center tr-detail-item"
                                        style="cursor: pointer; color: #1b6aaa;"><a href="'.route('customer.detail', [ 'id' => $cus->id]).'">'.$cus->name.'</a></td>
                                    <td class="text-center">'.$cus->phone.'</td>
                                    <td class="text-center">'.$cus->address.'</td>
                                    <td class="text-center">'.$max_date .'</td>
                                    <td class="text-right"
                                        style="font-weight: bold; background-color: #f9f9f9;">'.number_format($total_money).' đ</td>
                                    <td class="text-right">'.number_format($total_lack).' đ</td>
                                    <td class="text-center">
                                    
                                        <a style="color: red; font-size: 20px;" href="" data-url="'.route('customer.action', ['delete',$cus->id]).'" class="action-delete"> <i class="fa fa-trash-o"></i></a>
                                       
                                    </td>
                                </tr> ';
                        }
                    }else {
                        $output.='
                        <tr>
                            <td colspan="8" class="text-center">Không có dữ liệu</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
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
        return view('admin::customer.add');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(RequestCustomer $request)
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
        $customer->note = $data['note'];
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
        return redirect('customer/create')->with('message', 'Thêm khách hàng thành công');
        
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function detail_customer($id)
    {
        $customer = Customer::find($id);
        $orders = Order::where('customer_id', $id)->get();
        return view('admin::customer.detail', compact('customer', 'orders'));
    }
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->birthday = $request->birthday;
        $customer->gender = $request->gender;
        $customer->note = $request->note;

        $customer->user_update = Auth::id();
        $customer->save();
        return redirect('customer/')->with('message', 'Cập nhật khách hàng thành công');

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function action($action, $id)
    {
        if($action){
            $customer = Customer::find($id);
            switch ($action)
            {
                case 'delete':
                    $this->deleteTrait($id, $customer );
                    break;
            }

        }
        return redirect('customer');
    }
    public function export_csv(){
        return Excel::download(new ExportCustomer , 'customer.xlsx');
    }
}
