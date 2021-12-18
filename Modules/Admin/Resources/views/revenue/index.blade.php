@extends('admin::layouts.master')

@section('css')
<style>

input[type="file"] {
    display: none;
}

</style>
@endsection
@section('content')

<div class="breadcrumbs mb-30">
    <div class="row customer-act act">
        <h6 style="float: left;">
            <label style="font-size: 30px;">Báo cáo</label>
            <label style="color: #307ecc; padding-left: 10px;">
                <input type="radio" name="revenue" value="1" checked>
                <span class="lbl">Báo cáo tổng hợp</span>
            </label>
            <label style="color: #307ecc;">
                <input type="radio" name="revenue" value="2">
                <span class="lbl">Theo khách hàng</span>
            </label>
            <label style="color: #307ecc;">
                <input type="radio" name="revenue" value="3">
                <span class="lbl">Theo nhân viên bán hàng</span>
            </label>
            <label style="color: #307ecc;">
                <input type="radio" name="revenue" value="4">
                <span class="lbl">Theo cửa hàng</span>
            </label>
        </h6>
        
    </div>
</div>
<div class="breadcrumbs mb-15">
    <div class="row customer-act act">
        <div class="col-sm-5">
            <div class="input-daterange input-group">
                
                <input type="text" name="date" class="input-sm form-control" id="search-date-from" placeholder="Từ ngày"
                       name="start"/>
                <span class="input-group-addon"> Đến </span>
                <input type="text" name="date" class="input-sm form-control" id="search-date-to" placeholder="Đến ngày"
                       name="end"/>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="page-header float-right">
                <div class="btn-group order-btn-calendar">
                    <button type="button" onclick="revenue_week()" class="btn btn-default">Tuần</button>
                    <button type="button" onclick="revenue_month()" class="btn btn-default">Tháng</button>
                    <button type="button" onclick="revenue_quarter()" class="btn btn-default">Quý</button>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="revenue report row" style="margin: 15px 0;"></div>




   

@endsection
@section('js')
<script>
     if (window.location.pathname.indexOf('revenue') > -1) {
        paging_revenue();
    }
    function paging_revenue() {
        var type = $('[name=revenue]:checked').val();
        var date_from = $('#search-date-from').val();
        var date_to = $('#search-date-to').val();
        $.ajax({
            url: '{{url('/revenue/list')}}',
            method: 'GET',
            data:{type:type,date_from:date_from,date_to:date_to  },
            success:function(data){
                $('.revenue').html(data);
                
            }   
        });
    }
    $('[name=revenue]').on('change', function () {
        paging_revenue();
    });
    $('[name=date]').on('change', function () {
        paging_revenue();
    });
    function set_current_week() {
        var curr = new Date;
        var first = curr.getDate() - curr.getDay();
        var last = first + 6;
        var firstday = new Date(curr.setDate(first)).toISOString().split('T')[0];
        var lastday = new Date(curr.setDate(last)).toISOString().split('T')[0];
        $('#search-date-from').val(firstday);
        $('#search-date-to').val(lastday);
    }

    function set_current_month() {
        var date = new Date;
        var first = new Date(date.getFullYear(), date.getMonth(), 2);
        var last = new Date(date.getFullYear(), date.getMonth() + 1, 1);
        var firstday = first.toISOString().split('T')[0];
        var lastday = last.toISOString().split('T')[0];
        $('#search-date-from').val(firstday);
        $('#search-date-to').val(lastday);
    }

    function set_current_quarter() {
        var d = new Date();
        var quarter = Math.floor((d.getMonth() / 3));
        var firstDate = new Date(d.getFullYear(), quarter * 3, 1);
        $('#search-date-from').datepicker("setDate", firstDate);
        $('#search-date-to').datepicker("setDate", new Date(firstDate.getFullYear(), firstDate.getMonth() + 3, 0));
    }

    function revenue_week() {
        set_current_week();
        paging_revenue();
    }

    function revenue_month() {
        set_current_month();
        paging_revenue();
    }

    function revenue_quarter() {
        set_current_quarter();
        paging_revenue();
    }
</script>    
@endsection