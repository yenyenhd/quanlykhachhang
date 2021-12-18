@extends('admin::layouts.master')
@section('title')
    <title>Khách hàng</title>
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
                <h3>Danh sách khách hàng</h3>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('list.customer') }}">Customer</a></li>
                <li class="breadcrumb-item active">list</li>

            </ol>
        </div>

    </div>
</div>
<div class="breadcrumbs">
    <div class="row customer-act act">
        <div class="col-sm-4">
            @can('customer add')
            <a href="{{ route('customer.add') }}"><button type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tạo khách hàng
            </button></a>
            @endcan
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                   
                    <form action="{{route('customer.export_csv')}}" method="POST">
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
<div class="content mt-3">
    
    <div class="card mb-4 mt-4">
        <div class="card-body">
            <div class="table-responsive list-customer-ajax">
            </div>
        </div>
       
    </div>

</div> 
@endsection
@section('js')
<script>
     if (window.location.pathname.indexOf('customer') > -1) {
        list_customer();
    }
    function list_customer() {
    
        $.ajax({
            url: '{{url('/customer/list')}}',
            method: 'GET',
           
            success:function(data){
                $('.list-customer-ajax').html(data);
                
            }   
        });
    }
   
   
</script>    
@endsection





