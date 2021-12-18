@extends('admin::layouts.master')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h3>Đơn hàng: {{ $order['code'] }}</h3>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <div class="right-action text-right">
            <div class="btn-groups">
                @can('order add')
                <a style="color:#fff" href="{{ route('order.add') }}"><button type="button" class="btn btn-primary"><i
                    class="fa fa-plus"></i> Tạo đơn hàng mới
                @endcan
            </button>
        </a>
        <a target="_blank" href="{{ route('print.order', $order->code) }}">
            <button type="button" class="btn btn-primary"><i class="fa fa-print"></i> In đơn hàng</button>
        </a>
       
            <a href="{{ route('list.order') }}">
                <button type="button" class="btn-back btn btn-primary"><i class="fa fa-arrow-left"></i> Trở lại
                </button>
            </a>
            
            </div>
        </div>
    </div>
</div>
 @if(session('message'))
    <div class="alert alert-success ajax-success">
        {{session('message')}}
    </div>
 @endif
 <div class="success_alert" id="success_alert"></div>
 <div class="error_alert" id="error_alert"></div>

 <div class="row mt-4">
    
    <div class="col-md-7">
        
        <div class="product-results">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">STT</th>
                        <th>Mã hàng</th>
                        <th>Tên sản phẩm</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-center">Giá bán</th>
                        <th class="text-center">Thành tiền</th>
                       
                    </tr>
                </thead>
                <tbody id="pro_search_append">
                    @if(isset($order_detail))
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($order_detail as $item)
                    @php
                        $i++;
                    @endphp
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $item->product->code }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ number_format($item->price) }} đ</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price*$item->quantity) }} đ</td>


                    </tr>
                        
                    @endforeach
                    @endif
                </tbody>
            </table>
           
            
            
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">Mã hóa đơn: </label>
            <div class="col-sm-8">
                {{ $order->code }}
                
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">Khách hàng:</label>
            <div class="col-sm-8">
                {{ $order->customer->name }}
                
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">Ngày bán: </label>
            <div class="col-sm-8">
                {{ $order->sell_date }}
                
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">Tình trạng đơn hàng: </label>
            <div class="col-sm-8">
                @if ($order->status == 1)
                    Đã hoàn thành
                @else
                    Đang chờ xử lý
                @endif
                
                
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">Ghi chú</label>
            <div class="col-sm-8">
                <textarea name="" id="note-order" rows="3" style="border-radius: 0;">{{ $order->note }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <h4 class="lighter" style="margin-top: 0;">
                <i class="fa fa-info-circle blue"></i>
                Thông tin thanh toán
            </h4>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">Hình thức</label>
            <div class="col-sm-8">
                <input type="radio" disabled class="payment-method" @php echo ($order->payment_method ==1) ? "checked" : ""; @endphp name="method-pay" value="1">
                                Tiền mặt &nbsp;
                <input type="radio" disabled class="payment-method" @php echo ($order->payment_method ==2) ? "checked" : ""; @endphp name="method-pay" value="2"> Thẻ ATM &nbsp;
                
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">Tiền hàng:</label>
            <div class="col-sm-8">
                {{ number_format($order->total_price) }} đ
                
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">Giảm giá:</label>
            <div class="col-sm-8">
               - {{ number_format($order->coupon) }} đ
                
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">Sử dụng số điểm:</label>
            <div class="col-sm-8">
                {{ $order->use_point }} điểm
                
            </div>
        </div>

        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">Tổng cộng:</label>
            <div class="col-sm-8">
                {{ number_format($order->total_money) }} đ
                
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">KH đã trả:</label>
            <div class="col-sm-8">
                {{ number_format($order->customer_pay) }} đ
                
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPassword3" class="col-sm-4 col-form-label">KH còn nợ:</label>
            <div class="col-sm-8">
                {{ number_format($order->lack) }} đ
                
            </div>
        </div>
        @if ($order->status == 0) 
        <div class="form-group row">
            <div class="col-md-12">
                <div class="btn-groups pull-right" style="margin-bottom: 50px;">
                    <form>
                        @csrf
                        <button type="button" class="btn btn-primary btn-change-status"  data-order_code="{{$order->code}}"><i
                            class="fa fa-check"></i> Xử lý đơn hàng
                    </button>
                    </form>

                </div>
            </div>
        </div>
        @endif
        
    </div>


</div>
@endsection

@section('js')

<script>
     $('.btn-change-status').click(function(){
        var order_code = $(this).data('order_code');
        var status = 1;
        var store_id = $('#store-id').val();
        
        $.ajax({
            url: '{{url('/order/update-orders')}}' + '/' +order_code,
            method:"POST",

            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{order_code:order_code,status:status,store_id:store_id },
            success:function(data){
                $('#success_alert').append('<div class="alert alert-success"> đơn hàng thành công.</div>');
               

            }
        });
        window.setTimeout(function(){ 
            window.location.href = "{{url('/order')}}";
        } ,3000);


    });
</script>
@endsection
