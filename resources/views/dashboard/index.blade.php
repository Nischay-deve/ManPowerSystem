@extends('layouts.app')
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
<link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
<!-- end::GXON CSS Stylesheet -->
@endpush

@push('scripts')
<!-- begin::GXON Page Scripts -->
<script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
<script src="{{ asset('assets/libs/sortable/Sortable.min.js') }}"></script>
<script src="{{ asset('assets/libs/chartjs/chart.js') }}"></script>
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<!-- end::GXON Page Scripts -->
@endpush

@section('content')

<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
	<div class="clearfix">
		<h1 class="app-page-title">Dashboard</h1>
		<span>
			{{ now()->format('D, M d, Y') }} - {{ now()->addMonth()->format('D, M d, Y') }}
		</span>
	</div>
</div>

<div class="row">

	<div class="col-xxl-11 gap-1">
		<div class="row">
			<div class="col-6 col-md-4 col-lg">
				<div class="card bg-secondary bg-opacity-05 shadow-none border-0">
					<div class="card-body">
						<div class="avatar bg-secondary shadow-secondary rounded-circle text-white mb-3">
							<i class="fi fi-sr-users"></i>
						</div>
						<h3>{{ $stats['totalWorkforce'] ?? 0 }}</h3>
						<h6 class="mb-0">Total Workforce</h6>
						<small class="fw-medium">
							<span class="text-success">
								<i class="fi fi-rr-arrow-small-up scale-3x"></i>
								{{ ($stats['joinedChangePct'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['joinedChangePct'] ?? 0 }}%
							</span> Last Month
						</small>
					</div>
				</div>
			</div>

			<div class="col-6 col-md-4 col-lg">
				<div class="card bg-info bg-opacity-05 shadow-none border-0">
					<div class="card-body">
						<div class="avatar bg-info shadow-info rounded-circle text-white mb-3">
							<i class="fi fi-sr-user-add"></i>
						</div>
						<h3>{{ $stats['joinedToday'] ?? 0 }}</h3>
						<h6 class="mb-0">Joined Today</h6>
						<small class="fw-medium">
							<span class="text-success">
								<i class="fi fi-rr-arrow-small-up scale-3x"></i>
								{{ ($stats['joinedChangePct'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['joinedChangePct'] ?? 0 }}%
							</span> Last Month
						</small>
					</div>
				</div>
			</div>

			<div class="col-6 col-md-4 col-lg">
				<div class="card bg-secondary bg-opacity-05 shadow-none border-0">
					<div class="card-body">
						<div class="avatar bg-warning shadow-warning rounded-circle text-white mb-3">
							<i class="fi fi-sr-delete-user"></i>
						</div>
						<h3>{{ $stats['activeWorkforce'] ?? 0 }}</h3>
						<h6 class="mb-0">Active Workforce</h6>
						<small class="fw-medium">
							<span class="text-danger">
								<i class="fi fi-rr-arrow-small-down scale-3x"></i>
								{{ ($stats['activeChangePct'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['activeChangePct'] ?? 0 }}%
							</span> Last Month
						</small>
					</div>
				</div>
			</div>

			<div class="col-6 col-md-6 col-lg">
				<div class="card bg-success bg-opacity-05 shadow-none border-0">
					<div class="card-body">
						<div class="avatar bg-success shadow-success rounded-circle text-white mb-3">
							<i class="fi fi-sr-shopping-bag"></i>
						</div>
						<h3>{{ $stats['inactiveWorkforce'] ?? 0 }}</h3>
						<h6 class="mb-0">Inactive</h6>
						<small class="fw-medium">
							<span class="text-success">
								<i class="fi fi-rr-arrow-small-down scale-3x"></i> +0%
							</span> Last Month
						</small>
					</div>
				</div>
			</div>

			<div class="col-12 col-md-6 col-lg">
				<div class="card bg-danger bg-opacity-05 shadow-none border-0">
					<div class="card-body">
						<div class="avatar bg-danger shadow-danger rounded-circle text-white mb-3">
							<i class="fi fi-sr-clock-three"></i>
						</div>
						<h3>{{ $stats['exitedWorkforce'] ?? 0 }}</h3>
						<h6 class="mb-0">Exited</h6>
						<small class="fw-medium">
							<span class="text-danger">
								<i class="fi fi-rr-arrow-small-down scale-3x"></i>
								{{ ($stats['exitedChangePct'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['exitedChangePct'] ?? 0 }}%
							</span> Overall
						</small>
					</div>
				</div>
			</div>

			<div class="col-12 col-md-8 col-lg">
				<div class="card bg-danger bg-opacity-05 shadow-none border-0">
					<div class="card-body">
						<div class="avatar bg-danger shadow-danger rounded-circle text-white mb-3">
							<i class="fi fi-sr-clock-three"></i>
						</div>
						<h3>{{ $stats['exitedToday'] ?? 0 }}</h3>
						<h6 class="mb-0">Exited Today</h6>
						<small class="fw-medium">
							<span class="text-danger">
								<i class="fi fi-rr-arrow-small-down scale-3x"></i> 0%
							</span> Today
						</small>
					</div>
				</div>
			</div>
		</div>
	</div>

	{{-- Recent Job Application (dynamic using employees) --}}
	<div class="card">
		<div class="card-header d-flex align-items-center justify-content-between border-0 pb-0">
			<h6 class="card-title mb-0">Recent Job Application</h6>
			<a href="javascript:void(0);" class="btn-link">View All</a>
		</div>

		<div class="card-body pb-3">
			<ul class="list-group list-group-hover list-group-smooth list-group-unlined list-group-outer">

				@forelse($recentApplicants as $app)
				@php
				$name = trim(($app->first_name ?? '') . ' ' . ($app->surname ?? ''));
				$designation = $app->designation?->title ?? '-';
				@endphp
				<li class="list-group-item d-flex justify-content-between align-items-center">
					<div class="avatar rounded-circle me-1">
						<img src="{{ asset('assets/images/avatar/avatar1.jpg') }}" alt="">
					</div>
					<div class="ms-2 me-auto">
						<h6 class="mb-0">{{ $name ?: '-' }}</h6>
						<small>{{ $designation }}</small>
					</div>
					<div class="dropdown select-status">
						<button class="btn btn-sm btn-secondary btn-sm dropdown-toggle waves-effect waves-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
							Select Status
						</button>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" data-class="btn-outline-light" data-selected="true">Pending</a></li>
							<li><a class="dropdown-item" data-class="btn-subtle-primary">Approved</a></li>
							<li><a class="dropdown-item" data-class="btn-subtle-secondary">Rejected</a></li>
						</ul>
					</div>
				</li>
				@empty
				<li class="list-group-item">
					<div class="alert alert-warning mb-0">No records found.</div>
				</li>
				@endforelse

			</ul>
		</div>
	</div>

</div>

<div class="col-xxl-12 col-lg-7">
	<div class="card overflow-hidden">
		<div class="card-header d-flex flex-wrap gap-3 align-items-center justify-content-between border-0 pb-0">
			<h6 class="card-title mb-0">Employee’s Leave</h6>
			<div id="dt_EmployeeLeave_Search"></div>
		</div>

		<div class="card-body px-3 pt-2 pb-0 gradient-layer">
			<table id="dt_EmployeeLeave" class="table display table-row-rounded">
				<thead class="table-light">
					<tr>
						<th class="minw-150px">Name</th>
						<th class="minw-200px">Designations</th>
						<th class="minw-150px">Days</th>
						<th class="minw-150px">Date</th>
						<th>Status</th>
					</tr>
				</thead>

				<tbody>
					@forelse($leaveEmployees as $emp)
					@php
					$name = trim(($emp->first_name ?? '') . ' ' . ($emp->surname ?? ''));
					$designation = $emp->designation?->title ?? '-';
					@endphp
					<tr>
						<td>{{ $name ?: '-' }}</td>
						<td>{{ $designation ?: '-' }}</td>
						<td>-</td>
						<td>-</td>
						<td>
							<div class="dropdown select-status">
								<button class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
									Pending
								</button>
								<ul class="dropdown-menu dropdown-menu-end">
									<li><a class="dropdown-item" data-selected="true">Pending</a></li>
									<li><a class="dropdown-item">Approved</a></li>
									<li><a class="dropdown-item">Rejected</a></li>
								</ul>
							</div>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="5">
							<div class="alert alert-warning mb-0">No employees found.</div>
						</td>
					</tr>
					@endforelse
				</tbody>

			</table>
		</div>
	</div>
</div>

@endsection