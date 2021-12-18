<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{csrf_token()}}">
    @yield('title')
    <link rel="stylesheet" href="{{ asset('public/vendor/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/themify-icons/themify-icons.css') }}">
   

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('public/backend/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/css/sweetalert.css') }}" rel="stylesheet">




    @yield('css')
    <link href="{{ asset('public/vendor/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/vendor/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/vendor/jquery/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('public/vendor/morris/morris.css') }}" rel="stylesheet">
    




</head>

<body id="page-top">
    <div id="wrapper">
        @include('admin::partials.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">
                @include('admin::partials.topbar')
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include('admin::partials.footer')

        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    @include('admin::partials.modal')
   
    
    <script src="{{ asset('public/vendor/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('public/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    
    <script src="{{ asset('public/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/vendor/sweetalert2/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('public/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('public/vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/vendor/jquery/jquery-ui.js') }}"></script>
    <script src="{{ asset('public/vendor/raphael-min.js') }}"></script>
    <script src="{{ asset('public/vendor/morris/morris.min.js') }}"></script>

    <script src="{{ asset('public/backend/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('public/backend/js/ckeditor.js') }}"></script>
    <script src="{{ asset('public/backend/js/delete.js') }}"></script>
    <script src="{{ asset('public/backend/js/select2_tag.js') }}"></script>
    <script src="{{ asset('public/vendor/jquery/jquery.autocomplete.min.js') }}"></script>
    <script src="{{ asset('public/backend/js/respond.min.js') }}"></script>
    <script src="{{ asset('public/backend/js/sweetalert.js') }}"></script>

   

    @yield('js')
    <script>
        $(document).ready( function () {
        $('#myTable').DataTable();
        } );
    </script>
    <script type="text/javascript">
   
        $( function() {
          $( "#datepicker" ).datepicker({
              prevText:"Tháng trước",
              nextText:"Tháng sau",
              dateFormat:"yy-mm-dd",
              dayNamesMin: [ "Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật" ],
              duration: "slow",
              changeMonth: true,
            changeYear: true,
            yearRange: "-70:+0",
          });
          $( "#search-date-from" ).datepicker({
              prevText:"Tháng trước",
              nextText:"Tháng sau",
              dateFormat:"yy-mm-dd",
              dayNamesMin: [ "Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật" ],
              duration: "slow",

          });
          $( "#search-date-to" ).datepicker({
              prevText:"Tháng trước",
              nextText:"Tháng sau",
              dateFormat:"yy-mm-dd",
              dayNamesMin: [ "Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật" ],
              duration: "slow",
          });
        } );
        function cms_encode_currency_format(obs) {
            return obs.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function cms_decode_currency_format(obs) {
            return parseInt(obs.replace(/,/g, ''));
        }

        $("input.discount-import").keyup(function () {
            cms_load_infor_import();
        });

        function myFunction() {
            cms_load_infor_order();
        }
    $(".customer-pay").keyup(function () {
        var customer_pay;
        if ($('input.customer-pay').val() == '')
            customer_pay = 0;
        else
            customer_pay = cms_decode_currency_format($('input.customer-pay').val());

        var total_after_discount = cms_decode_currency_format($('.total-after-discount').text());
        var debt = total_after_discount - customer_pay;

        if (debt >= 0) {
            $('div.debt').text(cms_encode_currency_format(debt));
            $('label.debt').text('Nợ');
        }
        else {
            $('div.debt').text(cms_encode_currency_format(-debt));
            $('label.debt').text('Tiền thừa');
        }
    });
    </script>
   
   <script>
       if (window.location.pathname.indexOf('product') > -1) {
        $(".inventory-item").hide();
    }
   </script>
   
</body>
</html>
