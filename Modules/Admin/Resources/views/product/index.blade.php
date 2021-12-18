@extends('admin::layouts.master')
@section('title')
    <title>Sản phẩm</title>
@endsection
@section('css')
<style>

input[type="file"] {
    display: none;
}

</style>
@endsection
@section('content')
<div class="content-header">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h3>Danh sách sản phẩm</h3>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('list.product') }}">Product</a></li>
                <li class="breadcrumb-item active">list</li>

            </ol>
        </div>

    </div>
</div>
<div class="breadcrumbs mb-15">
    <div class="row customer-act act">
        <div class="col-sm-4">
            @can('product add')
            <a href="{{ route('product.add') }}"><button type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tạo sản phẩm
            </button></a>
            @endcan
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                   
                    <form action="{{route('product.export_csv')}}" method="POST">
                        @csrf
                        <button type="submit" value="Export CSV" name="export_csv" class="btn btn-success" ><i class="fa fa-download"></i> Xuất Excel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('message'))
    <div class="alert alert-success">
        {{session('message')}}
    </div>
 @endif
    <div class="card mb-4 mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="display" style="min-width: 845px" >
                    <thead>
                        <tr>
                            <th></th>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Danh mục</th>
                            <th>Giá bán</th>
                            <th>Trạng thái</th>
                            <th>Nổi bật</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->code }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ number_format($product->sell_price,0,',','.') }} vnd</td>
                            <td>
                                <a href="{{ route('product.action', ['active',$product->id]) }}" class="badge badge-pill {{ $product->getStatus($product->active)['class'] }}">{{ $product->getStatus($product->active)['name'] }}</a>
                            </td>
                            <td>
                                <a href="{{ route('product.action', ['product_hot', $product->id]) }}" class="badge badge-pill {{ $product->getHot($product->hot)['class'] }}">{{ $product->getHot($product->hot)['name'] }}</a>
                              </td>
                           <td>
                                @can('product edit')
                                <a style="color: blue; font-size: 20px; padding-right: 30px;" href="{{ route('product.edit', $product->id) }}"><i class="ti-pencil-alt"></i></a>
                                @endcan
                                @can('product delete')
                                <a style="color: red; font-size: 20px;" href="" data-url="{{ route('product.action', ['delete',$product->id]) }}" class="action-delete"><i class="ti-trash"></i></a>
                                @endcan
                           </td>
                        </tr>
                       @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection

