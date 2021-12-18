@extends('admin::layouts.master')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Role add</h4>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('list.role') }}">Role</a></li>
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
    <form action="{{ route('role.store') }} " method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="col-md-9">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nhập tên vai trò...." value="{{ old('name') }}">
                    @if($errors->has('name'))
                        <div class="error-text">
                            {{$errors->first('name')}}
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 col-form-label">Guard name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control @error('guard_name') is-invalid @enderror" name="guard_name" placeholder="Guard name...." value="{{ old('guard_name') }}">
                    @if($errors->has('guard_name'))
                        <div class="error-text">
                            {{$errors->first('guard_name')}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <label for="">
                        <input type="checkbox" name="" class="check_all"> All
                    </label>
                </div>
                @foreach($permissionParent as $parentItem)
                <div class="card border-primary mb-3 col-md-12">
                    <div class="card-header">
                       <label>
                           <input type="checkbox" value="" class="checkbox_wrapper">
                       </label>
                     Module {{ $parentItem->name }}
                     </div>
                    <div class="row">
                         @foreach($parentItem->permissionChildren as $childItem)
                        <div class="card-body text-primary col-md-3">
                            <h5 class="card-title">
                            <label>
                                <input type="checkbox" class="checkbox_children" name="permission_id[]" value="{{ $childItem->id }}">
                            </label>{{ $childItem->name }}
                            </h5>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
                <div class="form-group row">
                    <div class="col-sm-9">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
@section('js')
<script src="{{ asset('public/backend/js/role.js') }}"></script>

<script>
   CKEDITOR.replace('description', options);
</script>
@endsection
