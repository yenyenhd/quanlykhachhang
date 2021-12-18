@extends('admin::layouts.master')
@section('title')
    <title>Thông tin khách hàng</title>
@endsection
@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0 mb-4">
        <div class="welcome-text">
            
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <div class="page-header float-right">
            <div class="page-title">
                @can('customer edit')
                <button type="button" class="btn btn-primary btn-hide-edit"
                            onclick="cms_edit_cusitem('customer')"><i class="fa fa-pencil-square-o"></i> Sửa
                    </button>
                @endcan
                    <a href="{{ route('list.customer') }}">
                        <button type="button" class="btn-back btn btn-primary"><i class="fa fa-arrow-left"></i> Trở lại
                        </button>
                    </a>
                    
                    <button type="button" class="btn btn-primary btn-show-edit" style="display:none;"
                            onclick="cms_undo_cusitem('customer')"><i class="fa fa-undo"></i> Hủy
                    </button>
            </div>
        </div>
    </div>
</div>
 @if(session('message'))
    <div class="alert alert-success">
        {{session('message')}}
    </div>
 @endif

    
       
<div class="customer-inner tr-item-customer" id="item-{{ $customer->id }}">
    <div class="form-row" style="margin-bottom: 15px">
        <h4>Thông tin khách hàng</h4>
    </div>
   
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="col-md-4 padd-0">Mã khách hàng</label>
            <div class="col-md-8 float-right">
                {{ $customer->code }}
            </div>
        </div>
        <div class="form-group col-md-6">
            <label class="col-md-4">Tên khách hàng</label>
            <div class="col-md-8 float-right">
                {{ $customer->name }}
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="col-md-4 padd-0">Điện thoại</label>
            <div class="col-md-8 float-right">
                {{ $customer->phone }}
            </div>
        </div>
        <div class="form-group col-md-6">
            <label class="col-md-4">Email</label>
            <div class="col-md-8 float-right">
                {{ $customer->email }}
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="col-md-4 padd-0">Giới tính</label>
            <div class="col-md-8 float-right">
                <input type="radio" disabled name="gender" @php echo ($customer['gender'] == '0') ? 'checked' : ''; @endphp >Nam
                &nbsp;
                <input type="radio" disabled name="gender" @php echo ($customer['gender'] == '1') ? 'checked' : ''; @endphp>Nữ
            </div>
        </div>
        <div class="form-group col-md-6">
            <label class="col-md-4 padd-0">Ngày sinh</label>
            <div class="col-md-8 float-right">
                {{ $customer->birthday }}
                
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="col-md-4 padd-0">Địa chỉ</label>

            <div class="col-md-8 float-right">
                {{ $customer->address }}

            </div>
        </div>
        <div class="form-group col-md-6">
            <label class="col-md-4">Ghi chú</label>
            <div class="col-md-8 float-right">
                {{ $customer->note }}
            </div>
        </div>
    </div>  
</div>

<div class="customer-inner tr-item-customer" id="item-{{ $customer->id }}">
    <h3>Lịch sử mua hàng</h3>
    <div class="card mb-4 mt-4">
  
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="display" style="min-width: 845px" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã đơn hàng</th>
                            <th>Kho xuất</th>
                            <th>Ngày bán</th>
                            <th>Trạng thái</th>
                            <th>Tổng tiền</th>
                            <th>Nợ</th>
                            
                        </tr>
                    </thead>
                    <tbody >
                        @php
                            $i = 0; 
                        @endphp
                        @foreach($orders as $order)
                           @php
                               $i++;
                           @endphp
                        <tr>
                            <td>{{ $i }}</td>
                            <td><a href="{{ route('order.detail', [ 'code' => $order->code]) }}">{{  $order->code }}</a></td>
                            <td>{{  $order->store->name }}</td>
                            <td>{{  $order->sell_date }}</td>
    
                            <td>
                                
                                @if ($order->status == 1)
                                {{ 'Hoàn thành' }}
                                @else 
                                {{ 'Đang chờ xử lý' }}
                                @endif
                            </td>
                            <td>{{  number_format($order->total_money) }} đ</td>
                            <td>{{  number_format($order->lack) }} đ</td>
                            
    
                        </tr>
                       @endforeach
    
                    </tbody>
    
                </table>
    
            </div>
        </div>
    </div>
</div>


<div class="customer-inner tr-edit-item-customer" style="display: none;">
    <h4 style="margin-bottom: 15px">Thông tin khách hàng</h4>
    <form class="col-md-9" action="{{ route('customer.update', [ 'id' => $customer->id]) }} " method="POST">
        {{ csrf_field() }}
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-3 col-form-label">Tên khách hàng</label>
            <div class="col-sm-9">
                <input type="text" name="name" class="form-control" value="{{ $customer->name }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-3 col-form-label">Mã khách hàng</label>
            <div class="col-sm-9">
                {{ $customer->code }}
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-3 col-form-label">Điện thoại</label>
            <div class="col-sm-9">
                <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
                <input type="text" name="email" class="form-control" value="{{ $customer->email }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-3 col-form-label">Giới tính</label>
            <div class="col-sm-9">
                <input type="radio"
                        name="gender" value="0" @php echo ($customer['gender'] == '0') ? 'checked' : ''; @endphp >Nam
                        &nbsp;
                <input type="radio"
                        name="gender" value="1" @php echo ($customer['gender'] == '1') ? 'checked' : ''; @endphp>
                        Nữ
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-3 col-form-label">Ngày sinh</label>
            <div class="col-sm-9">
                <input type="text" name="birthday"  id="datepicker" class="txttimes form-control"
                                value="{{ $customer->birthday }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-3 col-form-label">Địa chỉ</label>
            <div class="col-sm-9">
                <textarea name="address"
                class="form-control">{{ $customer->address }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-3 col-form-label">Ghi chú</label>
            <div class="col-sm-9">
                <textarea name="note" class="form-control">{{ $customer->note }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-9">
              <button type="submit" class="btn btn-primary">Cập nhật</button>
          </div>
    </form>
    
</div>

        

@endsection
@section('js')
<script>
    function cms_javascript_redirect(url) {
        window.location.assign(url);
    }
    function cms_javascrip_fullURL() {
        return window.location.href;
    }
    function cms_edit_cusitem(obj) {
        $('.btn-hide-edit').hide();
        $('.btn-show-edit').show();
        $('.tr-item-' + obj).hide();
        $('.tr-edit-item-' + obj).show();
    }
    function cms_undo_cusitem(obj) {
        $('.btn-hide-edit').show();
        $('.btn-show-edit').hide();
        $('.tr-item-' + obj).show();
        $('.tr-edit-item-' + obj).hide();
    }
    
</script>

@endsection
