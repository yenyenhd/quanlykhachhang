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
    <h3>Danh sách đơn hàng</h3>
    <div class="row customer-act act">
        
        <div class="col-sm-4">
            @can('order add')
            <a href="{{ route('order.add') }}"><button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Tạo đơn hàng
            </button></a>
            @endcan
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                   
                    <form action="{{route('order.export_csv')}}" method="POST">
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
                            <th>Khách hàng</th>
                            <th>Trạng thái</th>
                            <th>Tổng tiền</th>
                            <th>Nợ</th>
                            <th></th>

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
                            <td>{{  $order->customer->name }}</td>

                            <td>
                                
                                @if ($order->status == 1)
                                {{ 'Hoàn thành' }}
                                @else 
                                {{ 'Đang chờ xử lý' }}
                                @endif
                            </td>
                            <td>{{  number_format($order->total_money) }} đ</td>
                            <td>{{  number_format($order->lack) }} đ</td>
                            <td>
                                @can('order delete')
                                <a style="color: red; font-size: 20px;" href="" data-url="{{ route('order.action', ['delete',$order->id]) }}" class="action-delete"><i class="ti-trash"></i></a>
                                @endcan
                           </td>

                        </tr>
                       @endforeach

                    </tbody>

                </table>

            </div>
        </div>
    </div>

@endsection
