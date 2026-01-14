@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/lucide/lucide.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/node-waves/waves.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
	{{ session('success') }}
	<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
	<div class="clearfix">
		<h1 class="app-page-title">
			<span class="text-primary">{{ $employees->total() }}</span> Employee
		</h1>

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item">
					<a href="{{ route('index') }}">Dashboard</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Employee</li>
			</ol>
		</nav>
	</div>

	<a href="{{ route('employees.create') }}" class="btn btn-primary waves-effect waves-light">
		<i class="fi fi-rr-plus me-1"></i> Add Employee
	</a>
</div>

<div class="card d-flex flex-row flex-wrap align-items-center h-auto mb-5">
	<ul class="nav nav-underline me-auto px-3 gap-2">
		<li class="nav-item">
			<a class="nav-link border-3 py-3 px-2 active" href="javascript:void(0);">Employee</a>
		</li>
		<li class="nav-item">
			<a class="nav-link border-3 py-3 px-2" href="{{ route('leave') }}">Leave Request</a>
		</li>
	</ul>

	<div class="d-flex ps-3">
		<div class="d-flex align-items-center me-4">
			<button class="btn btn-link p-0 me-3 text-primary" type="button">
				<i class="fi fi-rr-apps text-sm"></i>
			</button>
			<button class="btn btn-link p-0 text-body" type="button">
				<i class="fi fi-br-list text-sm"></i>
			</button>
		</div>

		<div class="vr"></div>

		{{-- ✅ SEARCH (FIXED ROUTE) --}}
		<form class="d-flex align-items-center h-100 w-150px w-lg-300px position-relative"
			action="{{ route('employees.index') }}" method="GET">
			<button type="submit" class="btn btn-sm border-0 position-absolute start-0 ms-3 p-0">
				<i class="fi fi-rr-search"></i>
			</button>

			<input type="text"
				name="q"
				value="{{ $q ?? '' }}"
				class="form-control form-control-lg ps-5 rounded-start-0 border-0 shadow-none bg-transparent"
				placeholder="Search Employee">
		</form>
	</div>
</div>

<div class="row">
	@forelse($employees as $employee)

	@php
	$fullName = trim(($employee->first_name ?? '').' '.($employee->surname ?? ''));

	$statusBadge = 'bg-success-subtle text-success';
	$statusText = 'Active';

	if(!empty($employee->date_of_exit)){
	$statusBadge = 'bg-danger-subtle text-danger';
	$statusText = 'Exited';
	}

	// ✅ PHOTO (works if stored in storage/app/public)
	$photoUrl = !empty($employee->photo)
	? asset('storage/'.$employee->photo)
	: asset('assets/images/avatar/avatar-large3.jpg');

	$hired = $employee->date_of_joining
	? \Carbon\Carbon::parse($employee->date_of_joining)->format('d M Y')
	: '-';
	@endphp

	<div class="col-xxl-3 col-lg-4 col-md-6 mb-4">
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between border-0 pb-0 p-3">
				<span class="badge {{ $statusBadge }}">{{ $statusText }}</span>

				<div class="btn-group">
					<button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect dropdown-toggle"
						type="button" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="fi fi-rr-menu-dots"></i>
					</button>

					<ul class="dropdown-menu dropdown-menu-end">
						{{-- ✅ EDIT --}}
						<li>
							<a class="dropdown-item" href="{{ route('employees.edit', $employee->id) }}">
								Edit
							</a>
						</li>

						{{-- ✅ SOFT DELETE --}}
						<li>
							<form method="POST"
								action="{{ route('employees.destroy', $employee->id) }}"
								onsubmit="return confirm('Are you sure you want to delete this employee?')">
								@csrf
								@method('DELETE')

								<button type="submit" class="dropdown-item text-danger">
									Delete
								</button>
							</form>
						</li>
					</ul>
				</div>
			</div>

			<div class="card-body p-2 pt-0">
				<div class="text-center mb-3">
					<div class="avatar avatar-xxl rounded-4 mx-auto mb-3">
						<img src="{{ $photoUrl }}" alt="">
					</div>

					<h5 class="mb-0 fw-bold">{{ $fullName ?: '-' }}</h5>
					<p class="text-primary mb-0">
						{{ $employee->designation_id ? 'Designation #'.$employee->designation_id : '—' }}
					</p>
				</div>

				<div class="p-3 bg-light rounded">
					<div class="d-flex gap-3">
						<div class="w-50">
							<span class="text-1xs">Employee Code</span>
							<h6 class="mb-0">{{ $employee->employee_code ?: '-' }}</h6>
						</div>
						<div class="w-50">
							<span class="text-1xs">Hired Date</span>
							<h6 class="mb-0">{{ $hired }}</h6>
						</div>
					</div>

					<hr class="border-dashed">

					<div class="d-grid gap-2">
						<span class="d-block text-dark">
							<i class="fi fi-rr-building me-2 text-primary"></i>
							{{ $employee->department_id ? 'Department #'.$employee->department_id : '—' }}
						</span>

						<span class="d-block text-dark">
							<i class="fi fi-rr-phone-call me-2 text-primary"></i>
							{{ $employee->mobile ?: '-' }}
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	@empty
	<div class="col-12">
		<div class="alert alert-warning">
			No employees found.
		</div>
	</div>
	@endforelse
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="float-end">
			{{ $employees->links() }}
		</div>
	</div>
</div>

@endsection