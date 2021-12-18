@extends('admin::layouts.master')

@section('content')
    <div class="content-header">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>User update</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('list.user') }}">User</a></li>
                    <li class="breadcrumb-item active">update</li>

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
                <form action="{{ route('user.update', [$user->id]) }} " method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Họ tên</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nhập họ tên...." value="{{ $user->name }}">
                            @if($errors->has('name'))
                                <div class="error-text">
                                    {{$errors->first('name')}}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ $user->username }}">
                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Cửa hàng</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="store_id">
                                <option>--Chọn cửa hàng--</option>
                                @foreach ($list_stores as $item)
                                <option @php if($item->id == $user->store_id) echo "selected";  @endphp value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}">
                            @if($errors->has('email'))
                                <div class="error-text">
                                    {{$errors->first('email')}}
                                </div>
                            @endif
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Nhập password...." value="{{ old('password') }}">
                            @if($errors->has('password'))
                                <div class="error-text">
                                    {{$errors->first('password')}}
                                </div>
                            @endif
                        </div>
                      </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Chọn vai trò</label>
                        <div class="col-sm-9">
                            @foreach($roles as $role)
                            <div class="col-sm-3">
                                <label>
                                <input type="checkbox" {{ $roleOfUser->contains('id', $role->id) ? 'checked' : '' }} name="role_id[]" value="{{ $role->id }}">
                                {{ $role->name }}
                                </label>
                            </div>
                             @endforeach
                        </div>
                    </div>
                      <div class="form-group row">
                        <div class="col-sm-9">
                          <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                      </div>
                </form>

            </div>
        </div>

@endsection
