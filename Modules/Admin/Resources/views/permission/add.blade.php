@extends('admin::layouts.master')

@section('content')
    <div class="content-header">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Permission add</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('permission.add') }}">Permission</a></li>
                    <li class="breadcrumb-item active">create</li>

                </ol>
            </div>
        </div>
    </div>
    @if(session('message'))
        <div class="alert alert-success">
            {{session('message')}}
        </div>
     @endif
        <div class="row mt-4">
            <div class="col-md-9">
                <form action="{{ route('permission.store') }} " method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Tên module</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="module_name" placeholder="Nhập tên module...." value="{{ old('name') }}">
                            @if($errors->has('name'))
                                <div class="error-text">
                                    {{$errors->first('name')}}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-9">
                        <button type="submit" class="btn btn-success">Thêm</button>
                      </div>
                    </div>
                    
                </form>

            </div>
            <div class="col-md-9">
                <form action="{{ route('permission.save') }} " method="POST">
                {{ csrf_field() }}
                    <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-3 col-form-label">Chọn module</label>
                      <div class="col-sm-9">
                        <select class="form-control" name="parent_id">
                            <option name="module" value="0">Chọn module</option>
                            {{!!$htmlOption!!}}
                            
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword3" class="col-sm-3 col-form-label"></label>
                        @foreach(config('permission.module_children') as $moduleItemChildren)
                        <div class="col-md-2">
                          <label>
                            <input type="checkbox" name="module_children[]" value="{{ $moduleItemChildren }}">
                            {{ $moduleItemChildren }}
                          </label>
                        </div>
                        @endforeach
                      
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-9">
                        <button type="submit" class="btn btn-success">Thêm</button>
                      </div>
                    </div>
                  </form>
        
            </div>
        </div>

@endsection
