@extends('admin::layouts.master')

@section('content')
    <h1>Hello Admin</h1>
    <style type="text/css">
        p.title_thongke {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }
    </style>

<div class="row">
    <form autocomplete="off" style="width:100%">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="inputEmail4">Từ ngày</label>
                <input type="text" class="form-control"id="datepicker">
            </div>
            <div class="form-group col-md-3">
                <label for="inputEmail4">Đến ngày</label>
                <input type="text" class="form-control"id="datepicker2">
            </div>
            <div class="form-group col-md-6">
                <label for="inputAddress">Lọc theo</label>
                <select class="dashboard-filter form-control" >
                    <option>--Chọn--</option>
                    
                    <option value="7ngay">7 ngày qua</option>
                    <option value="thangtruoc">tháng trước</option>
                    <option value="thangnay">tháng này</option>
                    <option value="365ngayqua">365 ngày qua</option>
                </select>
                </div>
        </div>
        <input type="button" id="btn-dashboard-filter" class="btn btn-primary btn-sm" value="Lọc kết quả">
    </form>

    <div class="col-md-12">
        <div id="chart" style="height: 250px;"></div>
    </div>
</div>
<div class="row mt-30">
    <div class="col-md-12 "><h3 class="text-center">Thống kê truy cập</h3></div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Đang online</th>
                <th scope="col">Tổng tháng trước</th>
                <th scope="col">Tổng tháng này</th>
                <th scope="col">Tổng một năm</th>
                <th scope="col">Tổng truy cập</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $visitor_count }}</td>
                <td>{{ $visitor_last_month_count }}</td>
                <td>{{ $visitor_this_month_count }}</td>
                <td>{{ $visitor_year_count }}</td>
                <td>{{ $visitors_total }}</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="row mt-30">
    <div class="col-md-12 col-xs-12">
		<h3 class="text-center">Thống kê tổng sản phẩm, khách hàng, đơn hàng</h3>
		<div id="donut"></div>	
	</div>
</div>

@endsection
@section('js')
<script type="text/javascript">
   
    $( function() {
      $( "#datepicker" ).datepicker({
          prevText:"Tháng trước",
          nextText:"Tháng sau",
          dateFormat:"yy-mm-dd",
          dayNamesMin: [ "Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật" ],
          duration: "slow"
      });
      $( "#datepicker2" ).datepicker({
          prevText:"Tháng trước",
          nextText:"Tháng sau",
          dateFormat:"yy-mm-dd",
          dayNamesMin: [ "Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật" ],
          duration: "slow"
      });
    } );
   
</script>
<script type="text/javascript">
    $(document).ready(function(){
    
        $('#btn-dashboard-filter').click(function(){
        
            var _token = $('input[name="_token"]').val();
            var from_date = $('#datepicker').val();
            var to_date = $('#datepicker2').val();

            $.ajax({
               url:"{{url('/filter-by-date')}}",
               method:"POST",
               dataType:"JSON",
               data:{from_date:from_date,to_date:to_date,_token:_token},
               
               success:function(data){
                    chart.setData(data);
                }   
           });
   
       });
    
        var chart = new Morris.Bar({  
            element: 'chart',
            //option chart
            lineColors: ['#819C79', '#fc8710','#FF6541', '#A4ADD3', '#766B56'],
            parseTime: false,
            hideHover: 'auto',
            xkey: 'period',
            ykeys: ['order','sales','profit','quantity'],
            labels: ['đơn hàng','doanh số','lợi nhuận','số lượng']
        
        });
        $('.dashboard-filter').change(function(){
            var dashboard_value = $(this).val();
            var _token = $('input[name="_token"]').val();
            // alert(dashboard_value);
            $.ajax({
                url:"{{url('/dashboard-filter')}}",
                method:"POST",
                dataType:"JSON",
                data:{dashboard_value:dashboard_value,_token:_token},
                
                success:function(data)
                    {
                        chart.setData(data);
                    }   
                });
    
        });
        chart60daysorder();
        function chart60daysorder(){
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{url('/days-order')}}",
                method:"POST",
                dataType:"JSON",
                data:{_token:_token},
                
                success:function(data)
                    {
                        chart.setData(data);
                    }   
            });
        }
       
        var donut = Morris.Donut({
            element: 'donut',
            resize: true,
            colors: [
                '#a8328e',
                '#61a1ce',
                '#f5b942',
                '#4842f5'
                
            ],
        
            data: [
                {label:"Sản phẩm", value:<?php echo $app_product ?>},
                {label:"Đơn hàng", value:<?php echo $app_order ?>},
                {label:"Khách hàng", value:<?php echo $app_customer ?>} 
            ]
        });
    
    
    });
        
</script>

@endsection


