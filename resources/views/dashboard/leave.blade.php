
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
	<link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
	<!-- end::GXON CSS Stylesheet -->
@endpush

@push('scripts')
	<!-- begin::GXON Page Scripts -->
	<script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
	<script src="{{ asset('assets/libs/chartjs/chart.js') }}"></script>
	<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
	<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
	<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
	<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
	<script src="{{ asset('assets/js/dashboard.js') }}"></script>
	<script src="{{ asset('assets/js/appSettings.js') }}"></script>
	<script src="{{ asset('assets/js/main.js') }}"></script>

	<script>
		document.addEventListener("DOMContentLoaded", function () {
			// Init DataTable (Static)
			if (window.$ && $.fn.DataTable) {
				$('#dt_PageDesignations').DataTable({
					pageLength: 10,
					lengthChange: true,
					ordering: true,
					searching: true,
					info: true,
					dom:
						"<'row align-items-center g-2'<'col-md-6'l><'col-md-6'f>>" +
						"<'row'<'col-12'tr>>" +
						"<'row align-items-center g-2'<'col-md-6'i><'col-md-6'p>>"
				});
			}
		});
	</script>
	<!-- end::GXON Page Scripts -->
@endpush

@section('content')

<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
	<div class="clearfix">
		<h1 class="app-page-title">Designations</h1>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item">
					<a href="{{ route('index') }}">Dashboard</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Designations</li>
			</ol>
		</nav>
	</div>

	<button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addDesignationModal">
		<i class="fi fi-rr-plus me-1"></i> Add Designation
	</button>
</div>

<div class="row">
	<!-- Stats Cards (Static) -->
	<div class="col-xxl-3 col-md-6">
		<div class="card card-action action-border-primary">
			<div class="card-body d-flex justify-content-between align-items-center">
				<div class="clearfix ps-2">
					<div class="d-flex text-dark align-items-end gap-1 lh-1 mb-1">
						<span class="fs-2 fw-bold">24</span>
					</div>
					<span class="text-primary">Total Designations</span>
				</div>
				<div class="clearfix">
					<div class="avatar avatar-md rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
						<i class="fi fi-rr-id-badge"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xxl-3 col-md-6">
		<div class="card card-action action-border-success">
			<div class="card-body d-flex justify-content-between align-items-center">
				<div class="clearfix ps-2">
					<div class="d-flex text-dark align-items-end gap-1 lh-1 mb-1">
						<span class="fs-2 fw-bold">19</span>
					</div>
					<span class="text-success">Active</span>
				</div>
				<div class="clearfix">
					<div class="avatar avatar-md rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center">
						<i class="fi fi-rr-check"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xxl-3 col-md-6">
		<div class="card card-action action-border-danger">
			<div class="card-body d-flex justify-content-between align-items-center">
				<div class="clearfix ps-2">
					<div class="d-flex text-dark align-items-end gap-1 lh-1 mb-1">
						<span class="fs-2 fw-bold">5</span>
					</div>
					<span class="text-danger">Inactive</span>
				</div>
				<div class="clearfix">
					<div class="avatar avatar-md rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center">
						<i class="fi fi-rr-ban"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xxl-3 col-md-6">
		<div class="card card-action action-border-secondary">
			<div class="card-body d-flex justify-content-between align-items-center">
				<div class="clearfix ps-2">
					<div class="d-flex text-dark align-items-end gap-1 lh-1 mb-1">
						<span class="fs-2 fw-bold">8</span>
					</div>
					<span class="text-secondary">Departments Covered</span>
				</div>
				<div class="clearfix">
					<div class="avatar avatar-md rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center">
						<i class="fi fi-rr-buildings"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Table -->
	<div class="col-lg-12">
		<div class="card overflow-hidden">
			<div class="card-header d-flex gap-3 flex-wrap align-items-center justify-content-between border-0 pb-0">
				<h6 class="card-title mb-0">Designation List</h6>
				<div class="d-flex gap-3 flex-wrap">
					<div id="dt_PageDesignations_Search"></div>
					<button type="button" class="btn btn-sm btn-outline-light btn-shadow waves-effect">
						<i class="fi fi-rr-download me-1"></i> Download Report
					</button>
					<select class="selectpicker" data-style="btn-sm btn-outline-light btn-shadow waves-effect">
						<option selected>2026</option>
						<option>2025</option>
						<option>2024</option>
						<option>2023</option>
					</select>
				</div>
			</div>

			<div class="card-body p-2">
				<table id="dt_PageDesignations" class="table display table-row-rounded">
					<thead class="table-light">
						<tr>
							<th class="minw-220px">Designation</th>
							<th class="minw-140px">Department</th>
							<th class="minw-140px">Level</th>
							<th class="minw-120px">Employees</th>
							<th class="minw-140px">Created</th>
							<th class="minw-120px">Status</th>
							<th class="minw-100px text-end">Action</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="avatar avatar-xxs rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
										<i class="fi fi-rr-user"></i>
									</div>
									<div class="ms-2">
										<div class="fw-semibold">Senior Software Engineer</div>
										<small class="text-muted">Backend / Full-stack</small>
									</div>
								</div>
							</td>
							<td>Development</td>
							<td>Senior</td>
							<td>14</td>
							<td>10 Jan 2026</td>
							<td><span class="badge bg-success-subtle text-success">Active</span></td>
							<td class="text-end">
								<div class="btn-group float-end">
									<button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fi fi-rr-menu-dots"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editDesignationModal">Edit</a></li>
										<li><a class="dropdown-item" href="javascript:void(0);">Delete</a></li>
									</ul>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="avatar avatar-xxs rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center">
										<i class="fi fi-rr-layers"></i>
									</div>
									<div class="ms-2">
										<div class="fw-semibold">UI/UX Designer</div>
										<small class="text-muted">Product design</small>
									</div>
								</div>
							</td>
							<td>Design</td>
							<td>Mid</td>
							<td>6</td>
							<td>22 Jan 2026</td>
							<td><span class="badge bg-success-subtle text-success">Active</span></td>
							<td class="text-end">
								<div class="btn-group float-end">
									<button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fi fi-rr-menu-dots"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editDesignationModal">Edit</a></li>
										<li><a class="dropdown-item" href="javascript:void(0);">Delete</a></li>
									</ul>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="avatar avatar-xxs rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center">
										<i class="fi fi-rr-megaphone"></i>
									</div>
									<div class="ms-2">
										<div class="fw-semibold">Digital Marketing Specialist</div>
										<small class="text-muted">Campaigns & ads</small>
									</div>
								</div>
							</td>
							<td>Marketing</td>
							<td>Junior</td>
							<td>5</td>
							<td>02 Feb 2026</td>
							<td><span class="badge bg-danger-subtle text-danger">Inactive</span></td>
							<td class="text-end">
								<div class="btn-group float-end">
									<button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fi fi-rr-menu-dots"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editDesignationModal">Edit</a></li>
										<li><a class="dropdown-item" href="javascript:void(0);">Delete</a></li>
									</ul>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="avatar avatar-xxs rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center">
										<i class="fi fi-rr-briefcase"></i>
									</div>
									<div class="ms-2">
										<div class="fw-semibold">Sales Manager</div>
										<small class="text-muted">Team lead</small>
									</div>
								</div>
							</td>
							<td>Sales</td>
							<td>Manager</td>
							<td>3</td>
							<td>14 Feb 2026</td>
							<td><span class="badge bg-success-subtle text-success">Active</span></td>
							<td class="text-end">
								<div class="btn-group float-end">
									<button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fi fi-rr-menu-dots"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editDesignationModal">Edit</a></li>
										<li><a class="dropdown-item" href="javascript:void(0);">Delete</a></li>
									</ul>
								</div>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Add Designation Modal (Static) -->
<div class="modal fade" id="addDesignationModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header py-3">
				<h5 class="modal-title">Add Designation</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form>
					@csrf

					<div class="mb-3">
						<label class="form-label">Designation Title</label>
						<input type="text" class="form-control" placeholder="Enter designation title">
					</div>

					<div class="row">
						<div class="col-md-6 mb-3">
							<label class="form-label">Department</label>
							<select class="form-select">
								<option selected disabled>Select department</option>
								<option>Development</option>
								<option>Design</option>
								<option>Marketing</option>
								<option>Sales</option>
								<option>HR</option>
								<option>Finance</option>
							</select>
						</div>
						<div class="col-md-6 mb-3">
							<label class="form-label">Level</label>
							<select class="form-select">
								<option selected>Junior</option>
								<option>Mid</option>
								<option>Senior</option>
								<option>Lead</option>
								<option>Manager</option>
								<option>Director</option>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 mb-3">
							<label class="form-label">Status</label>
							<select class="form-select">
								<option selected>Active</option>
								<option>Inactive</option>
							</select>
						</div>
						<div class="col-md-6 mb-3">
							<label class="form-label">Employees (count)</label>
							<input type="number" class="form-control" placeholder="e.g. 10">
						</div>
					</div>

					<div class="mb-3">
						<label class="form-label">Notes</label>
						<textarea class="form-control" rows="3" placeholder="Optional notes..."></textarea>
					</div>

					<div class="text-end">
						<button type="submit" class="btn btn-success">Save Designation</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Edit Designation Modal (Static) -->
<div class="modal fade" id="editDesignationModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header py-3">
				<h5 class="modal-title">Edit Designation</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form>
					@csrf

					<div class="mb-3">
						<label class="form-label">Designation Title</label>
						<input type="text" class="form-control" value="UI/UX Designer">
					</div>

					<div class="row">
						<div class="col-md-6 mb-3">
							<label class="form-label">Department</label>
							<select class="form-select">
								<option>Development</option>
								<option selected>Design</option>
								<option>Marketing</option>
								<option>Sales</option>
								<option>HR</option>
								<option>Finance</option>
							</select>
						</div>
						<div class="col-md-6 mb-3">
							<label class="form-label">Level</label>
							<select class="form-select">
								<option>Junior</option>
								<option selected>Mid</option>
								<option>Senior</option>
								<option>Lead</option>
								<option>Manager</option>
								<option>Director</option>
							</select>
						</div>
					</div>

					<div class="mb-3">
						<label class="form-label">Status</label>
						<select class="form-select">
							<option selected>Active</option>
							<option>Inactive</option>
						</select>
					</div>

					<div class="text-end">
						<button type="submit" class="btn btn-primary">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection
