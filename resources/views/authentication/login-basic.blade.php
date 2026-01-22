@extends('layouts.auth')

@push('styles')
<!-- begin::GXON Required Stylesheet -->
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/lucide/lucide.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/node-waves/waves.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}">
<!-- end::GXON Required Stylesheet -->

<!-- begin::GXON CSS Stylesheet -->
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
<!-- end::GXON CSS Stylesheet -->
@endpush

@push('scripts')
<!-- begin::GXON Page Scripts -->
<script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<!-- end::GXON Page Scripts -->
@endpush

@section('content')
<div class="auth-frame-wrapper">
	<div class="row g-0 h-100">
		{{-- LEFT SIDE FRAME --}}
		<div class="col-lg-6 d-none d-lg-block">
			<div class="auth-frame"
				style="background-image: url('{{ asset('assets/images/auth/auth-frame.webp') }}');">
				<div class="clearfix">
					<div class="auth-content">
						<h1 class="display-6 text-white fw-bold">Welcome Back!</h1>
						<p class="text-white">
							With WORKX, manage your workforce smarter. From employee records to day-to-day operations, WORKX gives you clarity, control, and confidence to scale your organization.
						</p>
					</div>

					<div class="auth-imgs position-relative">
						<img src="{{ asset('assets/images/auth/img1.png') }}" alt="" class="img-fluid">
						<img src="{{ asset('assets/images/auth/img2.png') }}" alt=""
							class="img-fluid position1 position-absolute">
						<img src="{{ asset('assets/images/auth/img3.png') }}" alt=""
							class="img-fluid position2 position-absolute">
					</div>
				</div>
			</div>
		</div>

		{{-- RIGHT SIDE FORM --}}
		<div class="col-lg-6 align-self-center">
			<div class="p-4 p-sm-5 maxw-450px m-auto">
				<div class=" text-center">
					<a href="{{ route('index') }}" aria-label="GXON logo">
						<img
							class="visible-light" style="height: 100px;width:100px;"
							src="{{ asset('assets/images/auth/Workx_logo.png') }}"
							alt="GXON logo">
					</a>
				</div>

				<div class="text-center mb-5">
					<h5 class="">Welcome to WORKX</h5>
					<p>Sign in to access your secure admin dashboard.</p>
				</div>

				{{-- ✅ ERROR MESSAGE --}}
				@if ($errors->any())
				<div class="alert alert-danger text-center">
					{{ $errors->first() }}
				</div>
				@endif

				<form method="POST" action="{{ route('login.submit') }}">
					@csrf

					{{-- EMAIL --}}
					<div class="mb-4">
						<label class="form-label" for="loginEmail">Email Address</label>
						<input
							type="email"
							name="email"
							class="form-control"
							id="loginEmail"
							placeholder="info@example.com"
							value="{{ old('email') }}"
							required>
					</div>

					{{-- PASSWORD --}}
					<div class="mb-4">
						<label class="form-label" for="loginPassword">Password</label>
						<input
							type="password"
							name="password"
							class="form-control"
							id="loginPassword"
							placeholder="********"
							required>
					</div>

					{{-- REMEMBER ME --}}
					<div class="mb-4">
						<div class="d-flex justify-content-between">
							<div class="form-check mb-0">
								<input
									class="form-check-input"
									type="checkbox"
									id="rememberMe"
									name="remember"
									{{ old('remember') ? 'checked' : '' }}>
								<label class="form-check-label" for="rememberMe"> Remember Me </label>
							</div>
							<!-- <a href="{{ route('forgot-password-basic') }}">Forgot Password?</a> -->
						</div>
					</div>

					{{-- SUBMIT --}}
					<div class="mb-3">
						<button type="submit" class="btn btn-primary waves-effect waves-light w-100">
							Login
						</button>
					</div>

					<!-- <p class="mb-5 text-center">
						Don’t have an account?
						<a href="{{ route('register-basic') }}">Sign Up here</a>
					</p> -->

					<!-- <div class="border-bottom position-relative my-3 text-center">
						<span class="px-3 position-absolute translate-middle top-50 start-50 bg-body">
							Or Continue With
						</span>
					</div> -->

					<!-- <div class="d-flex gap-2 justify-content-center mt-5">
						<a href="javascript:void(0);" class="btn btn-icon btn-subtle-facebook rounded-circle waves-effect waves-light">
							<i class="fa-brands fa-facebook-f"></i>
						</a>
						<a href="javascript:void(0);" class="btn btn-icon btn-subtle-twitter rounded-circle waves-effect waves-light">
							<i class="fa-brands fa-x-twitter"></i>
						</a>
						<a href="javascript:void(0);" class="btn btn-icon btn-subtle-github rounded-circle waves-effect waves-light">
							<i class="fa-brands fa-github"></i>
						</a>
					</div> -->

				</form>
			</div>
		</div>
	</div>
</div>
@endsection