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

<script>
	document.addEventListener("DOMContentLoaded", function() {
		if (!(window.$ && $.fn.DataTable)) return;

		const tableId = '#dt_PageEmployeeLeave';

		// ✅ Prevent "Cannot reinitialise DataTable" warning
		if ($.fn.dataTable.isDataTable(tableId)) return;

		$(tableId).DataTable({
			pageLength: 10,
			lengthChange: true,
			ordering: true,
			searching: true,
			info: true,
			autoWidth: false,
			dom: "<'row align-items-center g-2'<'col-md-6'l><'col-md-6'f>>" +
				"<'row'<'col-12'tr>>" +
				"<'row align-items-center g-2'<'col-md-6'i><'col-md-6'p>>",
			initComplete: function() {
				$('#dt_PageEmployeeLeave_filter').appendTo('#dt_PageEmployeeLeave_Search');
			}
		});
	});
</script>
@endpush

@section('content')

<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
	<div class="clearfix">
		<h1 class="app-page-title">Dashboard</h1>
		<span>{{ now()->format('D, M d, Y | h:i A') }}</span>
	</div>
	<button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
		data-bs-target="#addEmployeeModal">
		<i class="fi fi-rr-plus me-1"></i> Add Employee
	</button>
</div>

<div class="row">

	<div class="col-xxl-12">
		<div class="row">

			<div class="col-6 col-md-4 col-lg">
				<div class="card bg-secondary bg-opacity-05 shadow-none border-0">
					<div class="card-body">
						<div class="avatar bg-secondary shadow-secondary rounded-circle text-white mb-3">
							<i class="fi fi-sr-users"></i>
						</div>
						<h3>{{ $totalEmployee ?? 0 }}</h3>
						<h6 class="mb-0">Total Employee</h6>
					</div>
				</div>
			</div>

			<div class="col-6 col-md-4 col-lg">
				<div class="card bg-info bg-opacity-05 shadow-none border-0">
					<div class="card-body">
						<div class="avatar bg-info shadow-info rounded-circle text-white mb-3">
							<i class="fi fi-sr-user-add"></i>
						</div>
						<h3>{{ $newEmployee ?? 0 }}</h3>
						<h6 class="mb-0">New Employee</h6>
					</div>
				</div>
			</div>

			<div class="col-6 col-md-4 col-lg">
				<div class="card bg-secondary bg-opacity-05 shadow-none border-0">
					<div class="card-body">
						<div class="avatar bg-warning shadow-warning rounded-circle text-white mb-3">
							<i class="fi fi-sr-delete-user"></i>
						</div>
						<h3>{{ $onLeave ?? 0 }}</h3>
						<h6 class="mb-0">On Leave</h6>
					</div>
				</div>
			</div>

			<div class="col-6 col-md-6 col-lg">
				<div class="card bg-success bg-opacity-05 shadow-none border-0">
					<div class="card-body">
						<div class="avatar bg-success shadow-success rounded-circle text-white mb-3">
							<i class="fi fi-sr-shopping-bag"></i>
						</div>
						<h3>{{ $jobApplicants ?? 0 }}</h3>
						<h6 class="mb-0">Job Applicants</h6>
					</div>
				</div>
			</div>

			<div class="col-12 col-md-6 col-lg">
				<div class="card bg-danger bg-opacity-05 shadow-none border-0">
					<div class="card-body">
						<div class="avatar bg-danger shadow-danger rounded-circle text-white mb-3">
							<i class="fi fi-sr-clock-three"></i>
						</div>
						<h3>{{ $overTime ?? 0 }}</h3>
						<h6 class="mb-0">Over Time</h6>
					</div>
				</div>
			</div>

		</div>
	</div>

	<div class="col-lg-12">
		<div class="card overflow-hidden">
			<div class="card-header d-flex gap-3 flex-wrap align-items-center justify-content-between border-0 pb-0">
				<h6 class="card-title mb-0">Recent Workforce</h6>
				<div class="d-flex gap-3 flex-wrap">
					<div id="dt_PageEmployeeLeave_Search"></div>
				</div>
			</div>

			<div class="card-body p-2">
				<table id="dt_PageEmployeeLeave" class="table display table-row-rounded">
					<thead class="table-light">
						<tr>
							<th class="minw-200px">Employee Name</th>
							<th class="minw-150px">Department</th>
							<th class="minw-200px">Designation</th>
							<th class="minw-150px">Date of Joining</th>
							<th class="minw-150px">Mobile Number</th>
							<th class="minw-150px">Category</th>
							<th class="minw-100px">Work Status</th>
							<th class="minw-100px text-end">Action</th>
						</tr>
					</thead>

					<tbody>
						@forelse($recentWorkforce as $emp)
						@php
						$name = trim(
						($emp->name ?? $emp->first_name ?? '') . ' ' . ($emp->surname ?? '')
						);

						$department = $emp->department?->name ?? '-';
						$designation = $emp->designation?->title ?? '-';

						$joining = $emp->date_of_joining
						? \Carbon\Carbon::parse($emp->date_of_joining)->format('d M Y')
						: '-';

						$mobile = $emp->mobile ?? '-';
						$category = $emp->category ?? '-';

						$workStatus = ((int)($emp->is_active ?? 0) === 1) ? 'Active' : 'Inactive';
						if (!empty($emp->date_of_exit)) $workStatus = 'Exited';

						$badgeClass = 'bg-secondary-subtle text-body';
						if ($workStatus === 'Active') $badgeClass = 'bg-success-subtle text-success';
						if ($workStatus === 'Exited') $badgeClass = 'bg-danger-subtle text-danger';
						@endphp

						<tr>
							<td>
								<div class="d-flex align-items-center mw-175px">
									<div class="avatar avatar-xxs rounded-circle">
										<img src="{{ asset('assets/images/avatar/avatar1.jpg') }}" alt="">
									</div>
									<div class="ms-2 me-auto">{{ $name ?: '-' }}</div>
								</div>
							</td>
							<td>
								<span class="text-success">{{ $department }}</span>
							</td>
							<td>{{ $designation }}</td>
							<td>{{ $joining }}</td>
							<td>{{ $mobile }}</td>
							<td>{{ $category }}</td>
							<td>
								<span class="badge badge-lg {{ $badgeClass }}">{{ $workStatus }}</span>
							</td>
							<td class="text-end">
								<div class="btn-group float-end">
									<button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect dropdown-toggle"
										type="button" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fi fi-rr-menu-dots"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li>
											<a class="dropdown-item" href="{{ route('employees.show', $emp->id) }}">View</a>
										</li>
										<li>
											<a class="dropdown-item" href="{{ route('employees.edit', $emp->id) }}">Edit</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						@empty
						<tr>
							<td colspan="8" class="text-center text-muted py-4">No workforce found.</td>
						</tr>
						@endforelse
					</tbody>

				</table>
			</div>
		</div>
	</div>

</div>

{{-- Keep your modal as it is --}}
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header py-3">
				<h5 class="modal-title">Add Employee</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form>
					@csrf
					<div class="mb-3">
						<label for="fullName" class="form-label">Full Name</label>
						<input type="text" class="form-control" id="fullName" placeholder="Enter full name">
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="email" class="form-label">Email Address</label>
							<input type="email" class="form-control" id="email" placeholder="example@email.com">
						</div>
						<div class="col-md-6 mb-3">
							<label for="phone" class="form-label">Phone Number</label>
							<input type="tel" class="form-control" id="phone" placeholder="+91 9876543210">
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="department" class="form-label">Department</label>
							<select class="form-select" id="department">
								<option selected disabled>Select Department</option>
								<option>HR</option>
								<option>Development</option>
								<option>Sales</option>
								<option>Marketing</option>
							</select>
						</div>
						<div class="col-md-6 mb-3">
							<label for="designation" class="form-label">Designation</label>
							<input type="text" class="form-control" id="designation" placeholder="e.g. Software Engineer">
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="joiningDate" class="form-label">Joining Date</label>
							<input type="date" class="form-control flatpickr-date" id="joiningDate">
						</div>
						<div class="col-md-6 mb-3">
							<label for="status" class="form-label">Employment Status</label>
							<select class="form-select" id="status">
								<option>Active</option>
								<option>Inactive</option>
								<option>Probation</option>
							</select>
						</div>
					</div>
					<div class="mb-3">
						<label for="address" class="form-label">Address</label>
						<textarea class="form-control" id="address" rows="2" placeholder="Enter address"></textarea>
					</div>
					<div class="mb-3">
						<label for="photo" class="form-label">Profile Photo</label>
						<input class="form-control" type="file" id="photo">
					</div>
					<div class="text-end">
						<button type="submit" class="btn btn-success">Add Employee</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection