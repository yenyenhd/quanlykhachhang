<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Setting;
use App\Models\Loyalty;
use App\Models\CustomerCard;
use App\Models\Customer;
use App\Models\Card;





class LoyaltyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function setting()
    {
        $setting = Setting::first();
        return view('admin::loyalty.setting', compact('setting'));
    }

    public function save_setting(Request $request)
    {
        $data = $request->all();
        $setting = new Setting;
        $setting->money_point = $data['money_point'];
        $setting->point_money = $data['point_money'];
        $setting->status = $data['status'];

        $setting->save();
    }
    public function update_setting(Request $request)
    {
        $data = $request->all();
        $setting = Setting::first();
        $setting->money_point = $data['money_point'];
        $setting->point_money = $data['point_money'];
        $setting->status = $data['status'];

        $setting->save();
    }
    public function setting_card()
    {
        $cards = Card::orderBy('total_money', 'ASC')->get();
        return view('admin::loyalty.card', compact('cards'));
    }
    public function create_customer_card(Request $request)
    {
        $data = $request->all();
        $card = new Card();
        $card->name = $data['name'];
        $card->total_money = $data['total_money'];
        $card->save();
    }
    public function update_customer_card(Request $request)
    {
        $data = $request->all();
        $card = Card::find($data['card_id']);
        $card->total_money = $data['total_money'];
        $card->save();
    }
    public function delete_card($id)
    {
        $cart = Card::find($id)->delete();
        return redirect()->back();
    }
    public function customer_info()
    {
        $customers = Loyalty::all();
        $cards = Card::all();
        return view('admin::loyalty.customer', compact('customers', 'cards'));
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
