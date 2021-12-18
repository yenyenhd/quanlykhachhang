<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Requests\RequestProduct;
use App\Models\Product;
use App\Models\CategoryProduct;
use App\Models\Inventory;
use App\Models\Store;

use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use App\Components\Recusive;
use App\Traits\Delete;
use App\Traits\StorageImage;
use Illuminate\Support\Facades\Storage;
use Log;
use Auth;
use App\Exports\ExportProduct;
use Excel;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use Delete;
    use StorageImage;
    public function index()
    {
        $products = Product::all();
        return view('admin::product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function getCategory($parent_id)
    {
        $data = CategoryProduct::all();
        $recusive = new Recusive($data);
        $htmlOption = $recusive->Recusive($parent_id);
        return $htmlOption;
    }
    public function create()
    {
        $htmlOption = $this->getCategory($parent_id = '');
        $list_category = CategoryProduct::all();
        return view('admin::product.add', compact('htmlOption', 'list_category'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(RequestProduct $request)
    {
        
        if(!empty($request->store)){
            foreach($request->store as $store) {
                
                $storeIds[] = $store;
            }
        }
        
        $dataInsert = [
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'slug' => str::slug($request->name),
            'user_id' => Auth::id(),
            'origin_price' => $request->origin_price,
            'sell_price' => $request->sell_price,

        ];
        $dataInsert['quantity'] = ($request->quantity)*count($storeIds);
        $dataImage = $this->storageUpload($request, 'avatar_path', 'product');
    
        if(!empty($dataImage)){
            $dataInsert['avatar_path'] = $dataImage['file_path'];
        }
        Product::where('code', 'like','SP%')->max('code');
        $max_product_code = Product::get()->count();
        $max_code = (int)(str_replace('SP', '', $max_product_code)) + 1;
        if ($max_code < 10) {
            $dataInsert['code'] = 'SP00000' . ($max_code);
        } else if ($max_code < 100) {
            $dataInsert['code'] = 'SP0000' . ($max_code);
        } else if ($max_code < 1000){
            $dataInsert['code'] = 'SP000' . ($max_code);
        } else if ($max_code < 10000) {
            $dataInsert['code'] = 'SP00' . ($max_code);
        } else if ($max_code < 100000) {
            $dataInsert['code'] = 'SP0' . ($max_code);
        } else if ($max_code < 1000000) {
            $dataInsert['code'] = 'SP' . ($max_code);
        }
        $product = Product::create($dataInsert);
        $product->stores()->attach($storeIds, ['quantity' => $request->quantity]);
    
        return redirect('product/create')->with('message', 'Thêm sản phẩm thành công');
    }
    
    public function create_category(Request $request)
    {
        $data = $request->all();
        $category = new CategoryProduct;
        $category->name = $data['name'];
        $category->slug = str::slug($data['name']);
        $category->parent_id = $data['parent_id'];
        $category->save();
       
    }
    public function save_category(Request $request)
    {
        $data = $request->all();
        $category = CategoryProduct::find($data['id']);
        $category->name = $data['name'];
        $category->slug = str::slug($data['name']);
        $category->save();
       
    }
    public function delete_category($id, Request $request )
    {
        $data = $request->all();
        $category = CategoryProduct::find($id);
        
        $countitem = CategoryProduct::where('parent_id', $category->id)->count();
        $countprd = Product::where('category_id', $category->id)->count();
        $output = '';
        if($countitem > 0) {
            $output.='
            <div class="alert alert-danger" role="alert">Không thể xóa danh mục khi có danh mục cấp con.</div>';
        }elseif($countprd > 0){
            $output.='
            <div class="alert alert-danger" role="alert">Không thể xóa danh mục khi có sản phẩm.</div>';
        }else{
            $category->delete();
            $output.='
            <div class="alert alert-success" role="alert">Xóa danh mục thành công.</div>';    
        }
        echo $output;  
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
        $product = Product::find($id);
        $htmlOption = $this->getCategory($product->category_id);
        return view('admin::product.edit', compact('product', 'htmlOption'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $dataUpdate = [
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'slug' => str::slug($request->name),
            'user_id' => Auth::id(),
            'quantity' => $request->quantity,
            'origin_price' => $request->origin_price,
            'sell_price' => $request->sell_price,

        ];
        $dataImage = $this->storageUpload($request, 'avatar_path', 'product');
        if(!empty($dataImage)){
            $dataUpdate['avatar_path'] = $dataImage['file_path'];
        }
        $product =  Product::find($id);
        $product->update($dataUpdate);
        
        return redirect('product')->with('message', 'Update sản phẩm thành công');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function action($action, $id)
    {
        if($action){
            $product = Product::find($id);
            switch ($action)
            {
                case 'delete':
                    $this->deleteTrait($id, $product );
                    break;
                case 'active':
                    $product->status = $product->status ? 0 : 1;
                    $product->save();
                    break;
                case 'product_hot':
                    $product->hot = $product->hot ? 0 : 1;
                    $product->save();
                    break;
            }

        }
        return redirect('product');
    }
    public function export_csv(){
        return Excel::download(new ExportProduct , 'product.xlsx');
    }
}
