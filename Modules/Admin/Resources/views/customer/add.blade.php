@extends('admin::layouts.master')
@section('title')
    <title>Thêm khách hàng</title>
@endsection
@section('css')
    <style>
        .error-text{
            color: red;
            font-style:italic;
        }
    </style>
@endsection
@section('content')
    <div class="content-header">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Thêm khách hàng</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('list.customer') }}">Customer</a></li>
                    <li class="breadcrumb-item active">create</li>

                </ol>
            </div>
        </div>
    </div>
    @if(session('message'))
        <div class="alert alert-success">
            {{session('message')}}
        </div>
     @endif

        <div class="row mt-4">
            <div class="col-md-9">
                <form action="{{ route('customer.store') }} " method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Tên khách hàng</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nhập tên khách hàng...">
                            @if($errors->has('name'))
                            <div class="error-text">
                              {{$errors->first('name')}}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Số điện thoại</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone">
                          @if($errors->has('phone'))
                          <div class="error-text">
                              {{$errors->first('phone')}}
                          </div>
                      @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email">
                          @if($errors->has('email'))
                          <div class="error-text">
                              {{$errors->first('email')}}
                          </div>
                      @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Địa chỉ</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address">
                          @if($errors->has('address'))
                          <div class="error-text">
                              {{$errors->first('address')}}
                          </div>
                      @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Ngày sinh</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control @error('birthday') is-invalid @enderror" id="datepicker" name="birthday" placeholder="yyyy-mm-dd">
                          @if($errors->has('birthday'))
                          <div class="error-text">
                              {{$errors->first('birthday')}}
                          </div>
                      @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Ghi chú</label>
                        <div class="col-sm-9">
                          <textarea class="form-control" name="note" id="" cols="30" rows=""></textarea>
                     
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Giới tính</label>
                        <div class="col-sm-9">
                            <input type="radio" name="gender" value="0"> Nam
                            <input type="radio" name="gender" value="1"> Nữ
                          
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </form>

            </div>
        </div>

@endsection
