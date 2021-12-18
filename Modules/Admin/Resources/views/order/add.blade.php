@extends('admin::layouts.master')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h3>Thêm đơn hàng</h3>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <div class="right-action text-right">
            <div class="btn-groups">
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

 <form>
    @csrf
<div class="row mt-4">
    
        <div class="col-md-7">
            <div class="order-search" style="margin: 10px 0px; position: relative;">
                <input type="text" class="form-control" placeholder="Nhập mã sản phẩm hoặc tên sản phẩm"
                       id="search-pro-box">
            </div>
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
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="pro_search_append">
                    </tbody>
                </table>
                <div class="alert alert-success" style="margin-top: 30px;" role="alert">Gõ mã hoặc tên sản phẩm vào hộp
                    tìm kiếm để thêm hàng vào đơn hàng
                </div>
                
                
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">Khách hàng</label>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-10">
                            <select class="form-control" id="customer_id">
                                <option>--Tìm khách hàng--</option>
                                @foreach ($list_customers as $item)
                                <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }}</option>
                            @endforeach
                            </select>
                        </div>
                        
                        <div class="col-sm-2" style="margin-left: -23px">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#list-prd-group"
                                    style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                            </button>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">Ngày bán</label>
                <div class="col-sm-9">
                    <input id="date-order" class="form-control datepk" type="text" placeholder="Hôm nay"
                    style="border-radius: 0 !important;">
                    
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">Ghi chú</label>
                <div class="col-sm-9">
                    <textarea name="" id="note-order" rows="3" style="border-radius: 0;"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <h4 class="lighter" style="margin-top: 0;">
                    <i class="fa fa-info-circle blue"></i>
                    Thông tin thanh toán
                </h4>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">Hình thức</label>
                <div class="col-sm-9">
                    <input type="radio" class="payment-method" name="method-pay" value="1" checked>
                                    Tiền mặt &nbsp;
                    <input type="radio" class="payment-method" name="method-pay" value="2"> Thẻ ATM &nbsp;
                    
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">Tiền hàng</label>
                <div class="col-sm-9">
                    <div class="total-money">
                        0
                    </div>
                    
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">Giảm giá</label>
                <div class="col-sm-9">
                    <input type="text"
                    class="form-control text-right txtMoney discount-order"
                    placeholder="0" onkeyup="myFunction()" style="border-radius: 0 !important;">
                    
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">Sử dụng điểm</label>
                <div class="col-sm-9">
                    <input type="text"
                    class="form-control text-right txtMoney point-order"
                    placeholder="0" onkeyup="myFunction()" style="border-radius: 0 !important;">
                    <input type="hidden" class="setting_point" value="{{ $setting->point_money }}">
                    
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">Tổng cộng</label>
                <div class="col-sm-9">
                    <div class="total-after-discount">
                        0
                    </div>
                    
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">KH đã trả</label>
                <div class="col-sm-9">
                    <input type="text"
                    class="form-control text-right txtMoney customer-pay"
                    placeholder="0" style="border-radius: 0 !important;">
                    
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 col-form-label">KH còn nợ</label>
                <div class="col-sm-9">
                    <div class="debt">0</div>
                    
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <div class="btn-groups pull-right" style="margin-bottom: 50px;">
                        <button type="button" class="btn btn-primary"  onclick="save_orders(0)">
                            <i class="fa fa-floppy-o"></i> Lưu tạm
                        </button>
                        <button type="button" class="btn btn-primary"  onclick="save_orders(1)"><i
                                class="fa fa-check"></i> Lưu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    
    
</div>
</form>
<div class="modal fade" id="list-prd-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Tạo mới khách hàng</h4>
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
                                <label for="" class="col-sm-3 col-form-label">Tên khách hàng</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="name" placeholder="Name...." value="{{ old('name') }}">
                                    <span style="color: red; font-style: italic; font-size: 14px" class="error error_name"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Số điện thoại</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="phone" placeholder="Phone..." value="{{ old('phone') }}">
                                    <span style="color: red; font-style: italic; font-size: 14px" class="error error_phone"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="email" placeholder="Email...." value="{{ old('email') }}">
                                    <span style="color: red; font-style: italic; font-size: 14px" class="error error_email"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Địa chỉ</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="address" placeholder="Address...." value="{{ old('address') }}">
                                    <span style="color: red; font-style: italic; font-size: 14px" class="error error_address"></span>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Ngày sinh</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="birthday" placeholder="Birthday....">
                                    <span style="color: red; font-style: italic; font-size: 14px" class="error error_birthday"></span>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label">Giới tính</label>
                                <div class="col-sm-9">
                                    <input type="radio" name="gender" value="0"> Nam
                                    <input type="radio" name="gender" value="1"> Nữ
                                    <span style="color: red; font-style: italic;" class="error error_gender"></span>

                                </div>
                            </div>
                            

                        </div>
                    </div>
                </form>
                  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary "
                                                        onclick="create_customer();"><i class="fa fa-floppy-o"></i> Lưu
                                                    
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
   function cms_adapter_ajax($param) {
        $.ajax({
            url: $param.url,
            type: $param.type,
            data: $param.data,
            async: true,
            success: $param.callback
        });
    }

    $(function () {
        $("#search-pro-box").autocomplete({
            minLength: 1,
            source: 'autocomplete_products/',
            focus: function (event, ui) {
                $("#search-pro-box").val(ui.item.code);
                return false;
            },
            select: function (event, ui) {
                cms_select_product_sell(ui.item.id);
                $("#search-pro-box").val('');
                return false;
            }
        }).keyup(function (e) {
            if(e.which === 13) {
                cms_autocomplete_enter_sell();
                $("#search-pro-box").val('');
                $(".ui-menu-item").hide();
            }
        })
            .autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div>" + item.code + " - " + item.name + "</div>")
                .appendTo(ul);
        };
    });
    function cms_autocomplete_enter_sell() {
        $barcode = $("#search-pro-box").val();
        var $param = {
            'type': 'POST',
            'url': 'cms_check_barcode/' + $barcode,
            'data': null,
            'callback': function (data) {
                if (data > 0) {
                    cms_select_product_sell(data);
                    $(this).val('');
                }
            }
        };
        cms_adapter_ajax($param);
    }
    function cms_select_product_sell($id) {
        var $id = $id;
        var $store_id = $('#store-id').val();
        
        if ($('tbody#pro_search_append tr').length != 0) {
        $flag = 0;
        $('tbody#pro_search_append tr').each(function () {
            $id_temp = $(this).attr('data-id');
            if ($id == $id_temp) {
                value_input = $(this).find('input.quantity_product_order');
                value_input.val(parseInt(value_input.val()) + 1);
               
                $flag = 1;
                cms_load_infor_order();
                return false;
            }
        });
        if ($flag == 0) {
            
            var $seq = parseInt($('td.seq').last().text()) + 1;
            var $param = {
                'type': 'get',
                'url': 'cms_select_product/',
                'data': {'id': $id, 'seq': $seq, 'store_id':$store_id},
                'callback': function (data) {
                    $('#pro_search_append').append(data);
                    cms_load_infor_order();
                }
            };
            cms_adapter_ajax($param);
        }
        } else {
        var $param = {
            'type': 'get',
            'url': 'cms_select_product/',
            'data': {'id': $id, 'seq': 1, 'store_id':$store_id},
            'callback': function (data) {
                $('#pro_search_append').append(data);
                cms_load_infor_order();
            }
        };
        cms_adapter_ajax($param);
        }
    }
    function cms_select_product_import($id) {
        if ($('tbody#pro_search_append tr').length != 0) {
            $flag = 0;
            $('tbody#pro_search_append tr').each(function () {
                $id_temp = $(this).attr('data-id');
                if ($id == $id_temp) {
                    value_input = $(this).find('input.quantity_product_import');
                    value_input.val(parseInt(value_input.val()) + 1);
                    $flag = 1;
                    cms_load_infor_import();
                    return false;
                }
            });
            if ($flag == 0) {
                var $seq = parseInt($('td.seq').last().text()) + 1;
                var $param = {
                    'type': 'POST',
                    'url': 'import/cms_select_product/',
                    'data': {'id': $id, 'seq': $seq},
                    'callback': function (data) {
                        $('#pro_search_append').append(data);
                        cms_load_infor_import();
                    }
                };
                cms_adapter_ajax($param);
            }
        } else {
            var $param = {
                'type': 'POST',
                'url': 'import/cms_select_product/',
                'data': {'id': $id, 'seq': 1},
                'callback': function (data) {
                    $('#pro_search_append').append(data);
                    cms_load_infor_import();
                }
            };
            cms_adapter_ajax($param);
        }
    }
    function cms_load_infor_order() {
        $total_money = 0;
        $point_money = $('.setting_point').val();
        $('tbody#pro_search_append tr').each(function () {
            $quantity_product = $(this).find('input.quantity_product_order').val();
            $price = $(this).find('td.price-order-hide').text();
            $total = $price * $quantity_product;
            $total_money += $total;
            $(this).find('td.total-money').text($total);
        });
        $('div.total-money').text(($total_money));

        if ($('input.discount-order').val() == '')
            $discount = 0;
        else
            $discount = $('input.discount-order').val();

        if ($('input.point-order').val() == '')
            $point = 0;
        else
            $point = $('input.point-order').val();

        if ($discount > $total_money) {
            $('input.discount-order').val($total_money);
            $discount = $total_money;
        }
        $discount_point = $point*$point_money;
        if ($discount_point > $total_money) {
            $('input.point-order').val($total_money);
            $point = $total_money/$point_money;
        }

        $total_after_discount = $total_money - $discount - $discount_point;
        $('.total-after-discount').text($total_after_discount);
        $('input.customer-pay').val($total_after_discount);
        $('div.debt').text(0);
    }

    $('body').on('click', '.del-pro-order', function () {
        $(this).parents('tr').remove();
        cms_load_infor_order();
        cms_load_infor_import();
        $seq = 0;
        $('tbody#pro_search_append tr').each(function () {
            $seq += 1;
            value_input = $(this).find('td.seq').text($seq);
        });
    });

</script>

<script>
    $( function() {
          $( "#birthday" ).datepicker({
              prevText:"Tháng trước",
              nextText:"Tháng sau",
              dateFormat:"yy-mm-dd",
              dayNamesMin: [ "Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật" ],
              duration: "slow",
              changeMonth: true,
            changeYear: true,
            yearRange: "-70:+0",
          });
        } );
    // Thêm khách hàng
    function create_customer() {
        var name = $('#name').val();
        var phone = $('#phone').val();
        var email = $('#email').val();
        var address = $('#address').val();
        var birthday = $('#birthday').val();
        var gender = $("input[type='radio'][name='gender']:checked").val();
        var _token = $('input[name="_token"]').val();
      

        if (name.length == 0) {
        $('.error_name').text('Vui lòng nhập tên khách hàng!');
        } else {
            $('.error_name').text('');
        }
        if (phone.length == 0) {
            $('.error_phone').text('Vui lòng nhập số điện thoại!');
        } else {
            $('.error_phone').text('');
        }
        if (email.length == 0) {
            $('.error_email').text('Vui lòng nhập email!');
        } else {
            $('.error_email').text('');
        }
        if (address.length == 0) {
            $('.error_address').text('Vui lòng nhập địa chỉ!');
        } else {
            $('.error_address').text('');
        }
        if (birthday.length == 0) {
            $('.error_birthday').text('Vui lòng nhập ngày sinh!');
        } else {
            $('.error_birthday').text('');
        }

        if (name && phone && email && address && birthday) {
        
            $.ajax({
                url: '{{url('/order/create-customer')}}',
                method: 'POST',
                data:{_token:_token, name:name, phone:phone,email:email,address:address, birthday:birthday, gender:gender },
                success:function(){
                    
                    $('.success_alert').append('<div class="alert alert-success">Thêm khách hàng thành công.</div>');
                    setTimeout(function() {
                        $('.success_alert').fadeOut(1000);
                        
                    }, 1000);
                    location.reload();
                }

            });
        
        }
    }
</script>

<script>
    $( function() {
          $( "#date-order" ).datepicker({
              prevText:"Tháng trước",
              nextText:"Tháng sau",
              dateFormat:"yy-mm-dd",
              dayNamesMin: [ "Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật" ],
              duration: "slow",
              
          });
    } );
    function save_orders(type) {

        if ($('tbody#pro_search_append tr').length == 0) {
            $('.error_alert').html('Xin vui lòng chọn ít nhất 1 sản phẩm cần xuất trước khi lưu đơn hàng. Xin cảm ơn!').parent().fadeIn().delay(1000).fadeOut('slow');
        } else {
            swal({
                  title: "Xác nhận đơn hàng",
                  text: "Bạn có muốn lưu đơn hàng?",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonClass: "btn-success",
                  confirmButtonText: "Lưu",

                    cancelButtonText: "Hủy",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm){
                    if (isConfirm) {
                        var customer_id = $('#customer_id').val();
                        var store_id = $('#store-id').val();
                        var sell_date = $('#date-order').val();
                        var note = $('#note-order').val();
                        var payment_method = $("input:radio[name ='method-pay']:checked").val();
                        var coupon = cms_decode_currency_format(typeof $('input.discount-order').val() === 'undefined' ? 0 : $('input.discount-order').val());
                        var use_point = (typeof $('input.point-order').val() === 'undefined' ? 0 : $('input.point-order').val());

                        var customer_pay = cms_decode_currency_format($('.customer-pay').val());
                        
                        var detail_order = [];
                        $('tbody#pro_search_append  tr').each(function () {
                            var id = $(this).attr('data-id');
                            var value_input = $(this).find('input.quantity_product_order').val();
                            var stock_product = $('.stock_product').val();
                            if (parseInt(value_input) > parseInt(stock_product)) {
                                alert('Số lượng hàng không đủ!');
                            } else {
                                detail_order.push(
                                    {id: id, quantity: value_input, price: 0, coupon: 0,use_point:0 }
                                );
                            }
                        });
                
                        if (type == "0")
                            var status = 0;
                        else
                            var status = 1;

                        $.ajax({
                            url: '{{url('/order/save_orders')}}' + '/' +store_id,
                            method: 'POST',
                            headers:{
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data:{customer_id:customer_id, sell_date:sell_date, note:note, payment_method:payment_method,
                                coupon:coupon, customer_pay:customer_pay, detail_order:detail_order, status:status, use_point:use_point},
                            success:function(data){
                                if (type == 1) {
                                    swal("Thành công", "Đơn hàng đã được lưu thành công!", "success");
                                } else if (type == 0) {
                                    swal("Thành công", "Đơn hàng đã được lưu tạm thành công!", "success");
                                    
                                } 
                                $('#success_alert').html(data);
                            

                            }

                        });
                        window.setTimeout(function(){ 
                             window.location.href = "{{url('/order')}}";
                        } ,3000);

                    } else {
                        swal("Đóng", "Hủy lưu đơn hàng!", "error");

                }
              
            });   
           
        }
    }
</script>

@endsection
