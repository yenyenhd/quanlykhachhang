<!doctype html>
<html lang="en">
<head>
  	<title>Đăng nhập</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{ asset('public/backend/login/css/login.css') }}">

</head>
<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-7 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
		      	        <div class="d-flex">
		      		        <div class="w-100">
		      			        <h3 class="mb-4">Đăng nhập</h3>
		      		        </div>
							<div class="w-100">
								<p class="social-media d-flex justify-content-end">
									<a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a>
									<a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a>
								</p>
							</div>
		            	</div>
						<form action="{{ route('admin.login') }}" method="POST">
							{{ csrf_field() }}
		      		        <div class="form-group">
		      			        <div class="icon d-flex align-items-center justify-content-center">
                                      <span class="fa fa-user"></span>
                                </div>
		      			        <input type="text" class="form-control rounded-left @error('username') is-invalid @enderror" placeholder="Username" id="username" name="username" value="{{ old('username') }}">
								@if($errors->has('username'))
                                	<div class="error-text txt-red">
                                        {{$errors->first('username')}}
                                    </div>
                                @endif
		      		        </div>
	                        <div class="form-group">
	            	            <div class="icon d-flex align-items-center justify-content-center">
                                    <span class="fa fa-lock"></span>
                                </div>
	                            <input type="password" class="form-control rounded-left @error('password') is-invalid @enderror" placeholder="Password" id="password" name="password" value="{{ old('password') }}" >
								@if($errors->has('password'))
								<div class="error-text txt-red">
									{{$errors->first('password')}}
								</div>
								@endif 
							</div>
	                        <div class="form-group d-flex align-items-center">
	            	            <div class="w-100">
                                    <label class="checkbox-wrap checkbox-primary mb-0">Ghi nhớ đăng nhập
                                        <input type="checkbox"  name="remember_me">
                                        <span class="checkmark"></span>
                                    </label>
								</div>
                                <div class="w-100 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary rounded submit">Đăng nhập</button>
                                </div>
                            </div>
                            
	                    </form>
						@if(session('message'))
                            <div class="alert alert-danger">
                                {{session('message')}}

                            </div>
                            @endif
	                </div>
				</div>
			</div>
		</div>
	</section>

    <script src="{{ asset('public/vendor/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('public/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/backend/login/js/popper.js') }}"></script>
    <script src="{{ asset('public/backend/login/js/main.js') }}"></script>
   

</body>
</html>

