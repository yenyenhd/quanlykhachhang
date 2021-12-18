@extends('admin::layouts.master')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h3>Thêm sản phẩm</h3>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('list.product') }}">Product</a></li>
            <li class="breadcrumb-item active">create</li>

        </ol>
    </div>
</div>
 @if(session('message'))
    <div class="alert alert-success">
        {{session('message')}}
    </div>
 @endif
<div class="row mt-4">
    <div class="col-md-12">
        <form action="{{ route('product.store') }} " method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Tên sản phẩm</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nhập tên sản phẩm...." value="{{ old('name') }}">
                            @if($errors->has('name'))
                            <div class="error-text">
                                {{$errors->first('name')}}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Mô tả sản phẩm</label>
                        <div class="col-sm-9">
                            <textarea class="form-control @error('description') is-invalid @enderror" rows="5" id="description" placeholder="Mô tả...." name="description">{{ old('description') }}</textarea>
                            @if($errors->has('description'))
                            <div class="error-text">
                                {{$errors->first('description')}}
                            </div>
                            @endif
                          </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Hình ảnh</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control-file @error('avatar_path') is-invalid @enderror" name="avatar_path" >
                            @if($errors->has('avatar_path'))
                                <div class="error-text">
                                    {{$errors->first('avatar_path')}}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Danh mục sản phẩm</label>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-10">
                                    <select class="form-control @error('category_id') is-invalid @enderror" name="category_id">
                                        <option>--Chọn danh mục--</option>
                                        {{!!$htmlOption!!}}
                                        
                                        
                                    </select>
                                </div>
                                
                                <div class="col-sm-2" style="margin-left: -23px">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#list-prd-group"
                                            style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                    </button>
                                </div>
                            </div>
                            
                            @if($errors->has('category_id'))
                            <div class="error-text">
                                {{$errors->first('category_id')}}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Cửa hàng</label>
                        <div class="col-sm-9">
                            <select style="width:100%" class="js-example-basic-multiple" name="store[]" multiple="multiple" id="store-id">
                                @foreach ($app_store as $key => $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Số lượng</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" placeholder="Nhập số lượng...." value="{{ old('quantity') }}">
                            @if($errors->has('quantity'))
                            <div class="error-text">
                                {{$errors->first('quantity')}}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Giá gốc</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control @error('origin_price') is-invalid @enderror" name="origin_price" placeholder="Nhập giá gốc...." value="{{ old('origin_price') }}">
                            @if($errors->has('origin_price'))
                            <div class="error-text">
                                {{$errors->first('origin_price')}}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Giá bán</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control @error('sell_price') is-invalid @enderror" name="sell_price" placeholder="Nhập giá bán...." value="{{ old('sell_price') }}">
                            @if($errors->has('sell_price'))
                            <div class="error-text">
                                {{$errors->first('sell_price')}}
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="form-group row">
                    <div class="col-sm-9">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="list-prd-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Quản lý danh mục</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                
            </div>
            <div class="modal-body">
                <div class="tabtable">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tab-setting" role="tablist"
                        style="background-color: #EFF3F8; padding: 5px 0 0 15px;">
                        <li role="presentation" class="active" style="margin-right: 3px;"><a href="#list-groups"
                                                                                             aria-controls="list-group"
                                                                                             role="tab"
                                                                                             data-toggle="tab"><i
                                    class="fa fa-list"></i> Danh sách danh mục</a></li>
                        <li role="presentation"><a href="#create-groups" aria-controls="create-group" role="tab"
                                                   data-toggle="tab"><i class="fa fa-plus"></i> Tạo mới danh mục</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content" style="padding:10px; border: 1px solid #ddd; border-top: none;">
                        <div role="tabpanel" class="tab-pane active" id="list-groups">
                            <div class="success_alert" id="success_alert"></div>
                            <div class="prd_group-body">
                                <form>
                                    {{ csrf_field() }}
                                    <table id="myTable" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-left">Tên danh mục</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($list_category))
                                        @php $i = 0; @endphp
                                            @foreach ($list_category as $key => $item)
                                            @php $i++; @endphp
                                                <tr class='tr-item-{{ $item->id }} '>
                                                    <td>{{ $i }}</td>
                                                    <td class="text-edit">{{ $item->name }}</td>
                                                    <td class="text-center">
                                                        <i class="fa fa-pencil-square-o edit-item" title="Sửa" onclick="cms_edit_prd('group', {{ $item->id }})"
                                                                               style="color:blue; margin-right: 10px; cursor: pointer;"></i>
                                                        <i class="fa fa-trash-o delete-item" title="Xóa" onclick="Delete({{ $item->id }})"
                                                                                style="color:red; margin-right: 10px; cursor: pointer;"></i>
                                                        
                                                        
                                                    </td>
                                                </tr>
                                                <tr class='edit-tr-item-{{ $item->id }} ' style='display: none;'>
                                                    <td>{{ $key }}</td>
                                                    <td class="text-edit">
                                                        <input type="text" class="form-control edit-name-{{ $item->id }}"
                                                            value="{{ $item->name }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <i class='fa fa-floppy-o' title='Lưu' onclick='save_category_product({{ $item->id }})'
                                                                               style='color: #EC971F; cursor: pointer; margin-right: 10px;'></i>
                                                        <i class='fa fa-undo' onclick='cms_undo_item({{ $item->id }})' title='Hủy' 
                                                            style='color: green; cursor: pointer; margin-right: 5px;'></i></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="2" class="text-center">Không có dữ liệu</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </form>
                                
                            </div>
                            
                        </div>

                        <!-- Tab Function -->
                        <div role="tabpanel" class="tab-pane" id="create-groups">
                            <div class="success_alert" id="success_alert"></div>
                            <form>
                                {{ csrf_field() }}
                                <div class="row form-horizontal">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 col-form-label">Tên danh mục</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="name" placeholder="Nhập tên danh mục...." value="{{ old('name') }}">
                                                
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 col-form-label">Danh mục cha</label>
                                            <div class="col-sm-9">
                                                <select id="parent_id" class="form-control">
                                                    <option value="0">Chọn danh mục cha</option>
                                                    {{!!$htmlOption!!}}
                                                </select>
                                            
                                            </div>
                                        </div>
            
                                        <div class="form-group">
                                            <div class="col-md-8 col-md-offset-4">
                                                <button type="button" class="btn btn-primary "
                                                        onclick="create_category();"><i class="fa fa-floppy-o"></i> Lưu
                                                    
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
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
    CKEDITOR.replace('description', options);
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
    
</script>

<script>
function cms_edit_prd($module, id) {
    $('.prd_' + $module + '-body tr.tr-item-' + id).hide();
    $('.prd_' + $module + '-body tr.edit-tr-item-' + id).show();
}
function cms_undo_item(id) {
    $('tr.edit-tr-item-' + id).hide();
    $('tr.tr-item-' + id).show();
}

// Thêm danh mục
function create_category() {
    'use strict';
    var name = $('#name').val();
    var parent_id = $('#parent_id').val();
    var _token = $('input[name="_token"]').val();


    if (name.length == 0) {
        alert('Nhập tên danh mục.');
    } else {
        
        $.ajax({
            url: '{{url('/product/create-category')}}',
            method: 'POST',
            data:{_token:_token, name:name, parent_id:parent_id},
            success:function(){
                
                $('.success_alert').append('<div class="alert alert-success">Thêm danh mục thành công.</div>');
                setTimeout(function() {
                    $('.success_alert').fadeOut(1000);
                    
                }, 1000);
                location.reload();
            }

        });
        
    }
}
// Sửa danh mục
function save_category_product($id) {
    var id = $id;
    var name = $('.edit-name-' + id).val();
    var _token = $('input[name="_token"]').val();
    if (name.length == 0) {
        alert('Nhập tên danh mục sản phẩm.');
    } else {
        $.ajax({
            url: '{{url('/product/save-category')}}',
            method: 'POST',
            data:{ name:name, id:id,_token:_token},
            success:function(){
                
                $('.success_alert').append('<div class="alert alert-success">Cập nhật danh mục thành công.</div>');
                setTimeout(function() {
                    $('.success_alert').fadeOut(1000);
                    
                }, 1000);
                location.reload();

            }

        });
    }
}

function Delete($id){
        var id = $id;
        var _token = $('input[name="_token"]').val();
        
        $.ajax({
            url: '{{url('/product/del-category')}}' + '/' +id,
            method: 'POST',
            data:{_token:_token, id:id},
            success:function(data){
                
                $('#success_alert').html(data);
                setTimeout(function() {
                    location.reload();
                }, 3000);
                
            }

        });
    }
</script>

@endsection
