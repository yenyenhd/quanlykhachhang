@extends('admin::layouts.master')

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
        <div class="col-sm-12 p-md-0">
            <div class="welcome-text">
                <h3>Thiết lập tích điểm</h3>
            </div>
        </div>

    </div>
</div>
<div class="success_alert"></div>

 <div class="row mt-4">
    <div class="col-md-12">
        <form >
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label">Tỷ lệ quy đổi điểm</label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control money_point {{ isset($setting) ? 'setting_edit' : '' }} " value="{{ (isset($setting->money_point) ? $setting->money_point : '') }}"> 
                        </div>
                        <label for="" class="col-sm-2 col-form-label"> = 1 điểm</label>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label">Tỷ lệ quy điểm ra tiền</label>
                       
                        <div class="col-sm-6">
                            <input type="number" class="form-control point_money {{ isset($setting) ? 'setting_edit' : '' }}" value="{{ (isset($setting->point_money) ? $setting->point_money : '') }}"> 
                        </div>
                        <label for="" class="col-sm-2 col-form-label"> = 1 điểm </label>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label">Áp dụng tích điểm</label>
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input {{ isset($setting) ? 'setting_edit' : '' }}" type="radio" name="status" value="0" @php if(isset($setting) && ($setting->status == 0)) echo "checked"; @endphp> 
                                <label class="form-check-label" for="exampleRadios1">
                                    Không
                                  </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input {{ isset($setting) ? 'setting_edit' : '' }}" type="radio" name="status" value="1" @php if(isset($setting) && ($setting->status == 1)) echo "checked"; @endphp>
                                <label class="form-check-label" for="exampleRadios1">
                                    Có
                                  </label>
                            </div>
                            
                            <span style="color: red; font-style: italic;" class="error error_status"></span>

                        </div>
                    </div>
                   
                    @if(!isset($setting))
                    <div class="form-group row">
                        <div class="col-sm-9">
                            <button type="button" class="btn btn-primary add_setting">Lưu</button>
                        </div>
                    </div>
                    @endif
                </div>
                
               
            </div>
        </form>
    </div>
</div>

@endsection
@section('js')
    <script>
        $('.add_setting').click(function(){
            var money_point = $('.money_point').val();
            var point_money = $('.point_money').val();
            var status = $("input[type='radio'][name='status']:checked").val();
            
            
            $.ajax({
                url : '{{url('/loyalty/save-setting')}}',
                method: 'POST',
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{money_point:money_point, point_money:point_money, status:status},
                success:function(){
                    $('.success_alert').append('<div class="alert alert-success">Thiết lập tích điểm thành công.</div>');
    
                location.reload();

                }
            });


        });
        $(document).on('blur','.setting_edit',function(){

            var money_point = $('.money_point').val();
            var point_money = $('.point_money').val();
            var status = $("input[type='radio'][name='status']:checked").val();
          
            $.ajax({
                url : '{{url('/loyalty/update-setting')}}',
                method: 'POST',
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{money_point:money_point, point_money:point_money,status:status },
                success:function(){
                    $('.success_alert').append('<div class="alert alert-success">Cập nhật thiết lập tích điểm thành công.</div>');
    
                location.reload();

                }
            });

            });
    </script>
@endsection
