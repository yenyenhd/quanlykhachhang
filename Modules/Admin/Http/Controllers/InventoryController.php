<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Store;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Inventory;
use App\Exports\ExportInventory;
use Excel;
use Auth;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('admin::inventory.index');
    }
    public function inventory(Request $request)
    {
        $data = $request->all();
        $list_product = Inventory::where('store_id', $data['store_id'])->get();
        $total_quantity = 0;
        $total_von = 0;
        $total_ban = 0;

        $output = '';
        foreach ($list_product as $key => $pro) {
            $total_quantity+=$pro->quantity;
            $total_von+=($pro->quantity)*($pro->product->origin_price);
            $total_ban+=($pro->quantity)*($pro->product->sell_price);
        }
        $output.='
        <div class="col-md-12 padd-0" style="display: inherit;">
        <div class="col-md-3 padd-right-0">
            <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                <div class="infobox-icon">
                    <i class="fa fa-clock-o cgreen" style="font-size: 45px; color: rgb(64, 228, 64);" aria-hidden="true"></i>
                </div>
                <div class="infobox-data">
                    <h3 class="infobox-title cgreen"
                        style="font-size: 25px; color: rgb(64, 228, 64);">'.gmdate("d/m/Y", time() + 7 * 3600).'</h3>
                    <span class="infobox-data-number text-center" style="font-size: 14px; color: #555;">Ngày lập</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 padd-right-0">
            <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                <div class="infobox-icon">
                    <i class="fa fa-tag blue" style="font-size: 45px; color: rgb(52, 106, 223);" aria-hidden="true"></i>
                </div>
                <div class="infobox-data">
                    <h3 class="infobox-title blue"
                        style="font-size: 25px; color: rgb(52, 106, 223);">'.$total_quantity.'</h3>
                    <span class="infobox-data-number text-center"
                          style="font-size: 14px; color: #555;">SL tồn kho</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 padd-right-0">
            <div class="report-box " style="border: 1px dotted #ddd; border-radius: 0">
                <div class="infobox-icon">
                    <i class="fa fa-refresh orange" style="font-size: 45px; color: rgb(185, 14, 14);"></i>
                </div>
                <div class="infobox-data">
                    <h3 class="infobox-title orange"
                        style="font-size: 25px; color: rgb(185, 14, 14);">'.number_format($total_von).'</h3>
                    <span class="infobox-data-number text-center"
                          style="font-size: 14px; color: #555;">Tổng vốn tồn kho</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 padd-right-0">
            <div class="report-box" style="border: 1px dotted #ddd; border-radius: 0">
                <div class="infobox-icon">
                    <i class="fa fa-shopping-cart cred" style="font-size: 45px; color: rgb(241, 228, 42);"></i>
                </div>
                <div class="infobox-data">
                    <h3 class="infobox-title cred"
                        style="font-size: 25px; color: rgb(241, 228, 42);">'.number_format($total_ban).'</h3>
                    <span class="infobox-data-number text-center" style="font-size: 14px; color: #555;">Tổng giá trị tồn kho</span>
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
                <th>Mã hàng</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Vốn tồn kho</th>
                <th>Giá trị tồn</th>
            </tr>
        </thead>
        <tbody>';
        if (!$list_product->isEmpty()) {
            foreach ($list_product as $key => $pro) {
                $total_quantity+=$pro->quantity;
                $total_von+=($pro->quantity)*($pro->product->origin_price);
                $total_ban+=($pro->quantity)*($pro->product->sell_price);

                $output.='
                    <tr>
                        <td>'.$pro->product->code.'</td>
                        <td>'.$pro->product->name .'</td>
                        <td>'.$pro->quantity.'</td>
                        <td>'.number_format(($pro->quantity)*($pro->product->origin_price)).' đ</td>
                        <td>'.number_format(($pro->quantity)*($pro->product->sell_price)).' đ</td>
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
