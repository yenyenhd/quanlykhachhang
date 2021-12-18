@extends('admin::layouts.master')

@section('css')
<style>

input[type="file"] {
    display: none;
}

</style>
@endsection
@section('content')

<div class="breadcrumbs mb-15">
    <div class="row customer-act act">
        <div class="col-sm-4">
            <h3>Khách hàng</h3>
            
        </div>
       
        
    </div>
</div>
<div class="success_alert"></div>


<div class="card mb-4 mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table id="myTable" class="display" style="min-width: 800px" >
                <thead>
                    <tr>
                        <th>Tên khách hàng</th>
                        <th>Tổng giá trị mua hàng</th>
                        <th>Tổng điểm</th>
                        <th>Hạng thẻ</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($customers))
                    @foreach($customers as $cus)
                        @foreach($cards as $card)
                            @php
                                $sodu =$cus->total_money -$card->total_money;
                                if( $sodu > 0  ){
                                    $hang = $card->name;
                                }else{
                                    $hang = "Chưa lên hạng";
                                }
                            @endphp
                        @endforeach
                    <tr>
                        <td><a href="{{ route('customer.detail', [ 'id' => $cus->customer_id]) }}">{{ $cus->customer->name }}</a></td>
                        <td>{{ number_format($cus->total_money) }} đ</td>
                        <td>{{ $cus->total_point }} điểm</td>
                        <td>{{ $hang }} </td>


                    
                    </tr>
                   
                   @endforeach

                   @endif
                </tbody>
            </table>

        </div>
    </div>
</div>
<div class="modal fade" id="list-prd-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Tạo mới hạng thẻ</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                
            </div>
            <div class="modal-body">
                <div class="success_alert" id="success_alert"></div>
                <form method="POST">
                    {{ csrf_field() }}
                    <div class="row form-horizontal">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Tên hạng thẻ</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="name" placeholder="Name...." value="{{ old('name') }}">
                                    <span style="color: red; font-style: italic; font-size: 14px" class="error error_name"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Tổng giá trị mua hàng</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="total_money" >
                                    <span style="color: red; font-style: italic; font-size: 14px" class="error error_total_money"></span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </form>
                  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary "
                                                        onclick="create_customer_card();"><i class="fa fa-floppy-o"></i> Lưu
                                                    
                </button>
                <button type="button" class="btn btn-default btn-sm btn-close" data-dismiss="modal"><i
                        class="fa fa-undo"></i> Đóng
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script>
        $('.add_setting').click(function(){
            var money_point = $('.money_point').val();
            var point_money = $('.point_money').val();
            
            
            $.ajax({
                url : '{{url('/loyalty/save-setting')}}',
                method: 'POST',
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{money_point:money_point, point_money:point_money},
                success:function(){
                    $('.success_alert').append('<div class="alert alert-success">Thiết lập tích điểm thành công.</div>');
    
                location.reload();

                }
            });


        });
        $(document).on('blur','.setting_edit',function(){

            var money_point = $('.money_point').val();
            var point_money = $('.point_money').val();
          
            $.ajax({
                url : '{{url('/loyalty/update-setting')}}',
                method: 'POST',
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{money_point:money_point, point_money:point_money},
                success:function(){
                    $('.success_alert').append('<div class="alert alert-success">Cập nhật thiết lập tích điểm thành công.</div>');
    
                location.reload();

                }
            });

            });

            
    </script>
    <script>
            function create_customer_card() {
                var name = $('#name').val();
                var total_money = $('#total_money').val();
                var _token = $('input[name="_token"]').val();
            

                if (name.length == 0) {
                $('.error_name').text('Vui lòng nhập tên hạng thẻ!');
                } else {
                    $('.error_name').text('');
                }
                if (total_money.length == 0) {
                    $('.error_total_money').text('Vui lòng nhập tổng giá trị mua hàng!');
                } else {
                    $('.error_total_money').text('');
                }
               

                if (name && total_money) {
                
                    $.ajax({
                        url: '{{url('/loyalty/create-customer-card')}}',
                        method: 'POST',
                        data:{_token:_token, name:name, total_money:total_money},
                        success:function(){
                            
                            $('.success_alert').append('<div class="alert alert-success">Thêm hạng thẻ tích điểm thành công.</div>');
                            setTimeout(function() {
                                $('.success_alert').fadeOut(1000);
                                
                            }, 1000);
                            location.reload();
                        }

                    });
                
                }
            }
            $(document).on('blur','.card_edit',function(){

                var card_id = $(this).data('card_id');
                var total_money =$(this).text();
                $.ajax({
                    url : '{{url('/loyalty/update-customer-card')}}',
                    method: 'POST',
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{card_id:card_id, total_money:total_money },
                    success:function(){
                        $('.success_alert').append('<div class="alert alert-success">Cập nhật thiết lập hạng thẻ thành công.</div>');

                    location.reload();

                    }
                });

                });
    </script>
@endsection
