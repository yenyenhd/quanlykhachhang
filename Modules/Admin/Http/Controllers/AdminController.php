<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Requests\RequestLogin;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Https\Social;
use Socialite;
use App\Models\Report;
use App\Models\Product;
use App\Models\Post;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Visitor;



use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::check()){
            return redirect('dashboard');
        }else{
            return view('login');
        }
    }

    public function login(RequestLogin $request)
    {
        
        $remember = $request->has('remember_me') ? true : false;
        if(Auth::attempt(['username' => $request->username, 'password' => $request->password], $remember))
        {
            return redirect('dashboard');
        }
        else
        {
            return redirect('/')->with('message', 'Tài khoản hoặc mật khẩu không đúng!');
        }
    }

    public function logout()
    {
        Auth::logout();
        return view('login');
    }
    public function dashboard(Request $request)
    {
        $user_ip_address = $request->ip(); 
        $early_last_month = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->startOfMonth()->toDateString();

        $end_of_last_month = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->endOfMonth()->toDateString();

        $early_this_month = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth()->toDateString();

        $oneyears = Carbon::now('Asia/Ho_Chi_Minh')->subdays(365)->toDateString();

        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

        $visitor_of_lastmonth = Visitor::whereBetween('date_visited',[$early_last_month,$end_of_last_month])->get(); 
        $visitor_last_month_count = $visitor_of_lastmonth->count();
    
            //total this month
        $visitor_of_thismonth = Visitor::whereBetween('date_visited',[$early_this_month,$now])->get(); 
        $visitor_this_month_count = $visitor_of_thismonth->count();
    
            //total in one year
        $visitor_of_year = Visitor::whereBetween('date_visited',[$oneyears,$now])->get(); 
        $visitor_year_count = $visitor_of_year->count();
    
            //total visitors
        $visitors = Visitor::all();
        $visitors_total = $visitors->count();
    
            //current online
        $visitors_current = Visitor::where('ip_address',$user_ip_address)->get();  
        $visitor_count = $visitors_current->count();
    
        if($visitor_count<1){
            $visitor = new Visitor;
            $visitor->ip_address = $user_ip_address;
            $visitor->date_visited = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
            $visitor->save();
        }
    
            //total 
        $product = Product::all()->count();
        $order = Order::all()->count();
        $customer = Customer::all()->count();
    
       
        return view('admin::dashboard', compact('visitors_total','visitor_count','visitor_last_month_count',
        'visitor_this_month_count','visitor_year_count','product','order','customer'));
    }
   
    public function error()
    {
        return view('admin::404');
    }

    

    // Dashboard
    public function filter_by_date(Request $request){
        $data = $request->all();
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
    
        $get = Report::whereBetween('order_date',[$from_date,$to_date])->orderBy('order_date','ASC')->get();
    
        foreach($get as $key => $val){
            $chart_data[] = array(
                'period' => $val->order_date,
                'order' => $val->total_order,
                'sales' => $val->sales,
                'profit' => $val->profit,
                'quantity' => $val->quantity
            );
        }
    
        echo $data = json_encode($chart_data);  
    
    }
    public function dashboard_filter(Request $request){

        $data = $request->all();
    
            // $today = Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
           // $tomorrow = Carbon::now('Asia/Ho_Chi_Minh')->addDay()->format('d-m-Y H:i:s');
           // $lastWeek = Carbon::now('Asia/Ho_Chi_Minh')->subWeek()->format('d-m-Y H:i:s');
           // $sub15days = Carbon::now('Asia/Ho_Chi_Minh')->subdays(15)->format('d-m-Y H:i:s');
           // $sub30days = Carbon::now('Asia/Ho_Chi_Minh')->subdays(30)->format('d-m-Y H:i:s');
    
        $dauthangnay = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth()->toDateString();
        $dau_thangtruoc = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->startOfMonth()->toDateString();
        $cuoi_thangtruoc = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->endOfMonth()->toDateString();
    

        $sub7days = Carbon::now('Asia/Ho_Chi_Minh')->subdays(7)->toDateString();
        $sub365days = Carbon::now('Asia/Ho_Chi_Minh')->subdays(365)->toDateString();
    
        $dauthang9 = Carbon::now('Asia/Ho_Chi_Minh')->subMonth(2)->startOfMonth()->toDateString();
        $cuoithang9 = Carbon::now('Asia/Ho_Chi_Minh')->subMonth(2)->endOfMonth()->toDateString();
    
    
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
    
        if($data['dashboard_value']=='7ngay'){
    
            $get = Report::whereBetween('order_date',[$sub7days,$now])->orderBy('order_date','ASC')->get();
    
        }elseif($data['dashboard_value']=='thangtruoc'){
    
            $get = Report::whereBetween('order_date',[$dau_thangtruoc,$cuoi_thangtruoc])->orderBy('order_date','ASC')->get();
    
        }elseif($data['dashboard_value']=='thangnay'){
    
            $get = Report::whereBetween('order_date',[$dauthangnay,$now])->orderBy('order_date','ASC')->get();
    
        }elseif ($data['dashboard_value']=='thang9') {
    
            $get = Report::whereBetween('order_date',[$dauthang9,$cuoithang9])->orderBy('order_date','ASC')->get();
    
        }else{
            $get = Report::whereBetween('order_date',[$sub365days,$now])->orderBy('order_date','ASC')->get();
        }
    
    
        foreach($get as $key => $val){
    
            $chart_data[] = array(
                'period' => $val->order_date,
                'order' => $val->total_order,
                'sales' => $val->sales,
                'profit' => $val->profit,
                'quantity' => $val->quantity
            );
        }
    
        echo $data = json_encode($chart_data);
    
    }
    public function days_order(){

        $sub60days = Carbon::now('Asia/Ho_Chi_Minh')->subdays(60)->toDateString();
    
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
    
        $get = Report::whereBetween('order_date',[$sub60days,$now])->orderBy('order_date','ASC')->get();
    
    
        foreach($get as $key => $val){
    
           $chart_data[] = array(
            'period' => $val->order_date,
            'order' => $val->total_order,
            'sales' => $val->sales,
            'profit' => $val->profit,
            'quantity' => $val->quantity
        );
    
       }
    
       echo $data = json_encode($chart_data);
    }
    
}
