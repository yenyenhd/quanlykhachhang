@extends('admin::layouts.master')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Cập nhật sản phẩm</h4>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('list.product') }}">Product</a></li>
            <li class="breadcrumb-item active">update</li>

        </ol>
    </div>
</div>
 @if(session('message'))
    <div class="alert alert-success">
        {{session('message')}}
    </div>
 @endif
 <div class="row mt-4">
    <div class="col-md-9">
      <form action=" {{ route('product.update', ['id' => $product->id]) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
      
          <div class="form-group row">
              <label for="" class="col-sm-3 col-form-label">Tên sản phẩm</label>
              <div class="col-sm-9">
                  <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nhập tên sản phẩm...." value="{{ $product->name }}">
                  @if($errors->has('name'))
                  <div class="error-text">
                      {{$errors->first('name')}}
                  </div>
                  @endif
              </div>
          </div>
          <div class="form-group row">
              <label for="" class="col-sm-3 col-form-label">Mô tả sản phẩm</label>
              <div class="col-sm-9">
                  <textarea class="form-control @error('description') is-invalid @enderror" rows="5" id="description" placeholder="Mô tả...." name="description">{!! $product->description !!}</textarea>
                  @if($errors->has('description'))
                  <div class="error-text">
                      {{$errors->first('description')}}
                  </div>
                  @endif
                </div>
          </div>
        <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Hình ảnh</label>
            <div class="col-sm-9">
                <input type="file" class="form-control-file @error('avatar_path') is-invalid @enderror" name="avatar_path" >
                <div class="col-sm-4">
                  <div class="row">
                      <img class="avatar_image" src="{{ asset('public'.$product->avatar_path) }}" width="200px" alt="">
                  </div>
              </div>
                @if($errors->has('avatar_path'))
                <div class="error-text">
                    {{$errors->first('avatar_path')}}
                </div>
                @endif
            </div>
        </div>
        
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-3 col-form-label">Danh mục sản phẩm</label>
            <div class="col-sm-9">
                <select class="form-control @error('category_id') is-invalid @enderror" name="category_id">
                    <option value="0">Chọn loại sản phẩm</option>
                    {{!!$htmlOption!!}}
                  
                </select>
                @if($errors->has('category_id'))
                <div class="error-text">
                    {{$errors->first('category_id')}}
                </div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Số lượng</label>
            <div class="col-sm-9">
                <input type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" placeholder="Nhập số lượng...." value="{{ $product->quantity }}">
                @if($errors->has('quantity'))
                <div class="error-text">
                    {{$errors->first('quantity')}}
                </div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Giá gốc</label>
            <div class="col-sm-9">
                <input type="number" class="form-control @error('origin_price') is-invalid @enderror" name="origin_price"  value="{{ $product->origin_price }}">
                @if($errors->has('origin_price'))
                <div class="error-text">
                    {{$errors->first('origin_price')}}
                </div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Giá bán</label>
            <div class="col-sm-9">
                <input type="number" class="form-control @error('sell_price') is-invalid @enderror" name="sell_price" value="{{ $product->sell_price }}">
                @if($errors->has('sell_price'))
                <div class="error-text">
                    {{$errors->first('sell_price')}}
                </div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-9">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </div>
           
        
      </form>
    </div>
</div>
@endsection
