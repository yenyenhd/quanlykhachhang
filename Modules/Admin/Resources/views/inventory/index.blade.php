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
            <div class="welcome-text">
                <h3>Tồn kho</h3>
            </div>
        </div>
        
    </div>
</div>

<div class="inventory report row" style="margin: 15px 0;">

</div>


   

@endsection
@section('js')
<script>
     if (window.location.pathname.indexOf('inventory') > -1) {
        inventory();
    }
    
    function inventory() {
        store_id = $('#store-id').val();
        $.ajax({
            url: '{{url('/inventory/list')}}',
            method: 'GET',
            data:{store_id:store_id},
            success:function(data){
                $('.inventory').html(data);
                
            }   
        });
    }
    $("#store-id").change(function(){
        inventory();
        });
  
</script>    
@endsection