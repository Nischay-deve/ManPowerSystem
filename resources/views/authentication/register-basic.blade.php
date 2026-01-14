@extends('layouts.auth')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/lucide/lucide.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/node-waves/waves.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
@endpush

@section('content')
<div class="auth-wrapper min-vh-100 px-2"
	style="background-image: url({{ asset('assets/images/auth/auth.webp') }}); background-size: cover; background-position: center; background-repeat: no-repeat;">
	<div class="row g-0 min-vh-100">
		<div class="col-xl-5 col-lg-6 ms-auto px-sm-4 align-self-center py-4">
			<div class="card card-body p-4 p-sm-5 maxw-450px m-auto rounded-4">

				<div class="mb-4 text-center">
					<a href="{{ route('login') }}">
						<img class="visible-light" src="{{ asset('assets/images/logo-full.svg') }}">
						<img class="visible-dark" src="{{ asset('assets/images/logo-full-white.svg') }}">
					</a>
				</div>

				<div class="text-center mb-4">
					<h5 class="mb-1">Welcome to GXON</h5>
					<p>Sign up to create your secure admin.</p>
				</div>

				{{-- ✅ ERROR DISPLAY --}}
				@if ($errors->any())
				<div class="alert alert-danger text-center">
					{{ $errors->first() }}
				</div>
				@endif

				<form method="POST" action="{{ route('register.submit') }}">
					@csrf

					{{-- NAME --}}
					<div class="mb-4">
						<label class="form-label">Name</label>
						<input
							type="text"
							class="form-control"
							name="name"
							value="{{ old('name') }}"
							required>
					</div>

					{{-- EMAIL --}}
					<div class="mb-4">
						<label class="form-label">Email Address</label>
						<input
							type="email"
							class="form-control"
							name="email"
							value="{{ old('email') }}"
							required>
					</div>

					{{-- PASSWORD --}}
					<div class="mb-4">
						<label class="form-label">Password</label>
						<input
							type="password"
							class="form-control"
							name="password"
							required>
					</div>

					{{-- CONFIRM PASSWORD (CRITICAL) --}}
					<div class="mb-4">
						<label class="form-label">Confirm Password</label>
						<input
							type="password"
							class="form-control"
							name="password_confirmation"
							required>
					</div>

					{{-- TERMS --}}
					<div class="mb-4">
						<div class="form-check mb-0">
							<input
								class="form-check-input"
								type="checkbox"
								name="terms"
								required>
							<label class="form-check-label">
								I agree to <a href="javascript:void(0);">privacy policy & terms</a>
							</label>
						</div>
					</div>

					{{-- SUBMIT --}}
					<div class="mb-3">
						<button type="submit"
							class="btn btn-primary waves-effect waves-light w-100">
							Sign Up
						</button>
					</div>

					<p class="mb-5 text-center">
						Have an account?
						<a href="{{ route('login') }}">Sign In here</a>
					</p>

				</form>
			</div>
		</div>
	</div>
</div>
@endsection